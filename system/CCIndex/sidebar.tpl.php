<h3>Controllers and methods</h3>
<p>The following controllers exists. </br> You enable and disable controllers in <code>application/config.php.</code></p>
<ul>
<?php
$temp=null;
foreach($menu as $item)
{ 
  if(is_array($item) && !empty($item))
  {
    echo "<ul id=''>\n";
    foreach($item as $i)
    {
      echo "<li>\n
      <a href='".create_url($temp."/".$i)."'>$i</a>\n
      </li>\n";
    }
    echo"</ul>\n";
  }
  else if(!is_array($item))
  {
    echo"<li><a href='".create_url($item)."'>".$item."</a></li>";
    $temp=$item;
  } 
}?>
</ul>

