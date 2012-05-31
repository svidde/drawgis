<!-- <h1>Index Controller</h1>
<p>This is what you can do for now.</p>


<?php /*foreach($menu as $val): ?>
<li><a href='<?=create_url($val)?>'><?=$val?></a>  
<?php endforeach; */?>  
-->




<h1>Index Controller</h1>
<p>Welcome to Drawgis index controller.</p>

<h2>Download</h2>
<p>You can download Drawgis from github.</p>
<blockquote>
<code>git clone git://github.com/svidde/drawgis.git</code>
</blockquote>
<p>You can review its source directly on github: <a href='https://github.com/svidde/drawgis'>https://github.com/svidde/drawgis</a></p>

<h2>Installation</h2>
<p>First you have to make the data-directory writable. This is the place where Drawgis needs
to be able to write and create files.</p>
<blockquote>
<code>cd drawgis; chmod 777 application/data</code>
</blockquote>
<p>
Set the .htaccess file and change RewriteBase if needed.</p>

<p>Second, Lydia has some modules that need to be initialised. You can do this through a 
controller. Point your browser to the following link.</p>
<blockquote>
<a href='<?=create_url('module/install')?>'>module/install</a>
</blockquote>
