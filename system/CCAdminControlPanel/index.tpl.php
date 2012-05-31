<?php /*
if ( hasAdmRole() )
{
?>
<h1>Administrative</h1>

<a href="acp/remove">Ta bort användare</a> <br />
<a href="acp/editNews">Editera page</a> <br />
<a href="acp/createPage">Lägg till page</a> <br />
<a href="acp/editImage">Editera bilder</a> <br />
<?php
}
else
{
	?>
	<h1>Ej tillträde</h1>
	<p>Du måste vara inloggad och admin för att få vara här.<p>
	<?php
}*/
?>

<h1>Administrative</h1>

<p>Remove user</p>

<form action="<?=$formAction?>" method='post'>
  <p>
    <label>Email on User you wanna remove: <br/>
    <input type="text" name="email">
  </p>
  <p>
    <input type='submit' name='doDel' value='Remove User' />
  </p>
</form>

<h2>All users exept root</h2>

<div style="border:1px solid;">
<table>
<?php foreach($entries as $val):?>
	<?php if ( $val['acronym'] != "root" )
	{ ?>
	
  <tr>
    <td><strong> acronym: <?=$val['acronym']?> </strong></td>
    <td> name: <?=$val['name']?> </td>
    <td><em> email: <?=$val['email']?></em> </td> 
  </tr>
  <tr>
    <td> algorithm: <?=$val['algorithm']?> </td> 
    <td> created: <?=$val['created']?>  </td> 
  <?php
  if ( $val['updated'] != "" )
  {?>
  <td> updated: <?=$val['updated']?> </td>
  <?php } ?>
  </tr>
  	<?php } ?>
<?php endforeach;?>
</table>

</div>
