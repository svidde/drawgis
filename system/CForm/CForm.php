<?php



class CForm implements ArrayAccess {

  
  public $form;     // array with settings for the form
  public $elements; // array with all form elements
  
  public function __construct($form=array(), $elements=array()) {
    $this->form = $form;
    $this->elements = $elements;
  }

  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->elements[] = $value; } else { $this->elements[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->elements[$offset]); }
  public function offsetUnset($offset) { unset($this->elements[$offset]); }
  public function offsetGet($offset) { return isset($this->elements[$offset]) ? $this->elements[$offset] : null; }


  public function addElement($element) {
    $this[$element['name']] = $element;
    return $this;
  }
  

  public function setValidation($element, $rules) {
    $this[$element]['validation'] = $rules;
    return $this;
  }
  

  public function getHTML($attributes=null) {
    if(is_array($attributes)) {
      $this->form = array_merge($this->form, $attributes);
    }
    $id 	  = isset($this->form['id'])      ? " id='{$this->form['id']}'" : null;
    $class 	= isset($this->form['class'])   ? " class='{$this->form['class']}'" : null;
    $name 	= isset($this->form['name'])    ? " name='{$this->form['name']}'" : null;
    $action = isset($this->form['action'])  ? " action='{$this->form['action']}'" : null;
    $method = " method='post'";

    if(isset($attributes['start']) && $attributes['start']) {
      return "<form{$id}{$class}{$name}{$action}{$method}>";
    }
    
    $elements = $this->getHTMLForElements();
    $html = <<< EOD
\n<form{$id}{$class}{$name}{$action}{$method}>
<fieldset>
{$elements}
</fieldset>
</form>
EOD;
    return $html;
  }
 

  /**
   * Return HTML for the elements
   */
  public function getHTMLForElements() {
    $html = null;
    foreach($this->elements as $element) {
      $html .= $element->getHTML();
    }
    return $html;
  }
  

  /**
   * check if a form was submitted and perform validation and call callbacks.
   *
   * The form is stored in the session if validation or callback fails. The page should then be redirected
   * to the original form page, the form will populate from the session and should be rendered again.
   *
   * @returns boolean true if submitted&validates and callbacks are successfull, false if not validate or callback fails, null if not submitted.
   */
  public function check() {
    $validates = null;
    $callbackStatus = null;
    $values = array();
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      unset($_SESSION['form-failed']);
      $validates = true;
      foreach($this->elements as $element) {
        if(isset($_POST[$element['name']])) {
          $values[$element['name']]['value'] = $element['value'] = $_POST[$element['name']];
          if(isset($element['validation'])) {
            $element['validation-pass'] = $element->validate($element['validation']);
            if($element['validation-pass'] === false) {
              $values[$element['name']] = array('value'=>$element['value'], 'validation-messages'=>$element['validation-messages']);
              $validates = false;
            }
          }
          if(isset($element['callback']) && $validates) {
            if(isset($element['callback-args'])) {
    					if(call_user_func_array($element['callback'], array_merge(array($this), $element['callback-args'])) === false) {
    					  $callbackStatus = false;
    					}
  	  			} else {
              if(call_user_func($element['callback'], $this) === false) {
    					  $callbackStatus = false;
              }
            }
          }
        }
      }
    } else if(isset($_SESSION['form-failed'])) {
      foreach($_SESSION['form-failed'] as $key => $val) {
        $this[$key]['value'] = $val['value'];
        if(isset($val['validation-messages'])) {
          $this[$key]['validation-messages'] = $val['validation-messages'];
          $this[$key]['validation-pass'] = false;
        }
      }
      unset($_SESSION['form-failed']);
    }
    if($validates === false || $callbackStatus === false) {
      $_SESSION['form-failed'] = $values;
    }
    if($callbackStatus === false)
      return false;
    else 
      return $validates;
  }
  
  
}




#CFormElement
class CFormElement implements ArrayAccess{

 
  public $attributes;
  public $characterEncoding;
  

  
  public function __construct($name, $attributes=array()) {
    $this->attributes = $attributes;    
    $this['name'] = $name;
    if(is_callable('CDrawgis::getInstance()')) {
      $this->characterEncoding = CDrawgis::getInstance()->config['character_encoding'];
    } else {
      $this->characterEncoding = 'UTF-8';
    }
  }
  
  
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->attributes[] = $value; } else { $this->attributes[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->attributes[$offset]); }
  public function offsetUnset($offset) { unset($this->attributes[$offset]); }
  public function offsetGet($offset) { return isset($this->attributes[$offset]) ? $this->attributes[$offset] : null; }


  public function getHTML() {
    $id = isset($this['id']) ? $this['id'] : 'form-element-' . $this['name'];
    $class = isset($this['class']) ? " {$this['class']}" : null;
    $validates = (isset($this['validation-pass']) && $this['validation-pass'] === false) ? ' validation-failed' : null;
    $class = (isset($class) || isset($validates)) ? " class='{$class}{$validates}'" : null;
    $name = " name='{$this['name']}'";
    $label = isset($this['label']) ? ($this['label'] . (isset($this['required']) && $this['required'] ? "<span class='form-element-required'>*</span>" : null)) : null;
    $autofocus = isset($this['autofocus']) && $this['autofocus'] ? " autofocus='autofocus'" : null;    
    $readonly = isset($this['readonly']) && $this['readonly'] ? " readonly='readonly'" : null;    
    $type 	= isset($this['type']) ? " type='{$this['type']}'" : null;
    $onlyValue 	= isset($this['value']) ? htmlentities($this['value'], ENT_COMPAT, $this->characterEncoding) : null;
    $value 	= isset($this['value']) ? " value='{$onlyValue}'" : null;

    $messages = null;
    if(isset($this['validation-messages'])) {
      $message = null;
      foreach($this['validation-messages'] as $val) {
        $message .= "<li>{$val}</li>\n";
      }
      $messages = "<ul class='validation-message'>\n{$message}</ul>\n";
    }
    
    if($type && $this['type'] == 'submit') {
        return "<p><input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} /></p>\n";
    } else if($type && $this['type'] == 'textarea') {
        return "<p><label for='$id'>$label</label><br><textarea id='$id'{$type}{$class}{$name}{$autofocus}{$readonly}>{$onlyValue}</textarea></p>\n"; 
    } else if($type && $this['type'] == 'hidden') {
        return "<input id='$id'{$type}{$class}{$name}{$value} />\n"; 
    } else {
      return "<p><label for='$id'>$label</label><br><input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} />{$messages}</p>\n";			  
    }
  }

  
  public function validate($rules) {
    $tests = array(
      'fail' => array('message' => 'Will always fail.', 'test' => 'return false;'),
      'pass' => array('message' => 'Will always pass.', 'test' => 'return true;'),
      'not_empty' => array('message' => 'Can not be empty.', 'test' => 'return $value != "";'),
    );
    $pass = true;
    $messages = array();
    $value = $this['value'];
    foreach($rules as $key => $val) {
      $rule = is_numeric($key) ? $val : $key;
      if(!isset($tests[$rule])) throw new Exception('Validation of form element failed, no such validation rule exists.');
      if(eval($tests[$rule]['test']) === false) {
        $messages[] = $tests[$rule]['message'];
        $pass = false;
      }
    }
    if(!empty($messages)) $this['validation-messages'] = $messages;
    return $pass;
  }


  
  public function useNameAsDefaultLabel() {
    if(!isset($this['label'])) {
      $this['label'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name']))).':';
    }
  }


  public function useNameAsDefaultValue() {
    if(!isset($this['value'])) {
      $this['value'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name'])));
    }
  }


}

#CFormElementText
class CFormElementText extends CFormElement {
  
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'text';
    $this->useNameAsDefaultLabel();
  }
}

#CFormElementTextarea
class CFormElementTextarea extends CFormElement {
  
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'textarea';
    $this->useNameAsDefaultLabel();
  }
}

#CFormElementHidden
class CFormElementHidden extends CFormElement {
  
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'hidden';
  }
}

#CFormElementPassword
class CFormElementPassword extends CFormElement {
  
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'password';
    $this->useNameAsDefaultLabel();
  }
}

#CFormElementSubmit
class CFormElementSubmit extends CFormElement {
  
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'submit';
    $this->useNameAsDefaultValue();
  }
}


