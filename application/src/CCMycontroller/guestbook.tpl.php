<h1>My Guestbook</h1>
<p>Leave a message and be happy.</p>

<form action="<?=$formAction?>" method='post'>
  <p>
    <label>Title: <br/>
    <input type="text" name="title" /></label>
    <label><br/>Message: <br/>
    <textarea name='newEntry' style='width:500px;height:100px;'></textarea></label>
    <label><br/>Author: <br/>
    <input type="text" name="authour" /></label>
  </p>
  <p>
  <input type='submit' name='doAdd' value='Add message' />
  </p>
</form>

<h2>Current messages</h2>


<?php foreach($entries as $val):?>

<table style='background-color:#f6f6f6;padding:1em;'>
<tr>
  <td>Title: <?=$val['title']?></td>
  <td style='text-align:right;'>At: <?=$val['created']?></td>
  </tr>
  <tr>
  <td colspan="2"><?=nl2br(CHTMLPurifier::purify($val['entry']) )?> <td>
  </tr>
  <tr>
  <td colspan="2">Author: <?=$val['author']?><td>
 </table>
 
<?php endforeach;?>


