<?php if($content['id']):?>
  <h1><?=esc($content['title'])?></h1>
  <p><?=$content->getFilteredData()?></p>
<?php else:?>
  <p>404: No such page exists.</p>
<?php endif;?>

