<h1>Blog</h1>
<p>All nice news and blogposts about me.</p>

<?php if($contents != null):?>
  <?php foreach($contents as $val):?>
    <h2><?=esc($val['title'])?></h2>
    <p class='smaller-text'><em>Posted on <?=$val['created']?> by <?=$val['owner']?></em></p>
    <p><?=filter_data($val['data'], $val['filter'])?></p>
  <?php endforeach; ?>
<?php else:?>
  <p>No posts exists.</p>
<?php endif;?>


  
