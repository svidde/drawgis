<h1>Forum</h1>
<p>Här kan du skriva ett nytt inlägg.</p>

<form action="<?=$formAction?>" method='post'>
  <p>
    <label> Titel:<br/> <input type="text" name="title" /> </label>
    <label><br/>Innehåll: <br/>
    <textarea name='message' style='width:500px;height:100px;'></textarea></label>
    <input type="hidden" name="author" value="<?=getNameOfUser()?>" /> 
  </p>
  <p>
  <input type='submit' name='doAddForum' value='Lägg till foruminlägg' />
  </p>
</form>




<?php 
if ( $entries != null )
{

?>
<h2>Current messages</h2>
<?php
foreach($entries as $val):
?>
<div id="tableBox">
<form action="<?=$formAction?>"  method='post'>
<input type="hidden" name="id" value="<?=$val['id']?>" /> 
<input type='submit' name='doAddForumComment' value='<?=nl2br(CHTMLPurifier::purify($val['header']) )?>' class="submitbtn"  /> 
<div class="small" >Posted on <?=$val['created']?>  by: <?=$val['author']?> </div>

  
  <?php 
  if ( hasAdmRole() )
  {  
  ?>
    <input type='submit' name='doDelForumComment' value='Radera' class="submitbtn" />
  <?php }  ?>
  </form>
</div>
<?php endforeach;?>
<?php } ?>

