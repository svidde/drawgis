<?php 
if ( hasAdmRole() )
{
?>

<form action="<?=$formAction?>" method="post"
enctype="multipart/form-data">
Filename:<br />
<input type="file" name="file" id="file" required="true"/> 
<br />
Fotograf:<br/>
<input type="text" name="photographer" required="true" />
<br />
Titel:<br/>
<input type="text" name="title" required="true" /><br/>
<input type="submit" name="doUploadImg" value="Lägg till Bild" />
</form>
<br/>
<?php
foreach($images as $val):?>
<table>
<form action="<?=$formAction?>" method="post">
<input type="hidden" name="id" value="<?=$val['id']?>" />
  <tr>
    <td colspan="2" style='text-align:center;'>
      <img src="<?=img_url( $val['filename'] )?>" alt="<?=$val['title']?>" width="300" />  
    </td>
  </tr>
  <tr>
    <td>
      Fotograf: <?=$val['photographer']?> 
    </td>
    <td style='text-align:right;'>
      <input type="submit" name="doRemoveImage" value="Delete" class="submitbtn"/>
    </td>
  </tr>
</form>
</table>
<?php endforeach;?>
<?php
}
else
{
	?>
	<h1>Ej tillträde</h1>
	<p>Du måste vara inloggad och admin för att få vara här.<p>
	<?php
}
?>
