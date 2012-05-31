<?php 
if ( hasAdmRole() )
{

if($content['created']): ?>
  <h1>Edit Content</h1>
  <p>You can edit and save this content.</p>
<?php else: ?>
  <h1>Create Content</h1>
  <p>Create new content.</p>
<?php endif; ?>


<?=$form->getHTML(array('class'=>'content-edit'))?>

<p class='smaller-text'><em>
<?php if($content['created']): ?>
  This content were created by <?=$content['owner']?> at <?=$content['created']?>.
<?php else: ?>
  Content not yet created.
<?php endif; ?>

<?php if(isset($content['updated'])):?>
  Last updated at <?=$content['updated']?>.
<?php endif; ?>
</em></p>
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
