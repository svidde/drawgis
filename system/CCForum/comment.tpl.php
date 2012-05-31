<h1>Forum</h1>


<?php foreach($forum as $val):
$id = $val['id'];
?>

<div id="tableBox">
<strong> <?=nl2br(CHTMLPurifier::purify($val['header']) )?> </strong><br />
<?=nl2br(CHTMLPurifier::purify($val['message']) )?> 
<div class="small" >Postad <?=$val['created']?>  av: <?=$val['author']?> </div>
</div>

<?php endforeach;?>


<div id="comment" >
<h2>Skriv ny kommentar</h2>

<form action="<?=$formAction?>" method='post'>
  <p>
    <label>Meddelande: <br/>
    <textarea name='message' style='height:50px;'></textarea></label>
    <input type="hidden" name="author" value="<?=getNameOfUser()?>" /> 
    <input type="hidden" name="id" value="<?=$id?>" /> 
  </p>
  <p>
  <input type='submit' name='doAddComment' value='Lägg till Kommentar' />
  </p>
</form>


</div>



<?php 
if ( $entries != null )
{
?>

<h2>Tidigare meddelanden</h2>
<?php foreach($entries as $val):?>

<div id="tableBox">
<?=nl2br(CHTMLPurifier::purify($val['message']) )?> 
<div class="small" >Postad <?=$val['created']?>  av: <?=$val['author']?> </div>
<?php
if ( hasAdmRole() )
{?>
<form action="<?=$formAction?>" method='post'>
<input type="hidden" name="id" value="<?=$val['id']?>" /> 
<input type='submit' name='doDelForumComment' value='radera' class="submitbtn" />
  </form> 
<?php
}
?>

</div>
 
<?php endforeach;?>
<?php }
?>
