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
