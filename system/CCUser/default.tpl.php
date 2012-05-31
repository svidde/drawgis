<h1>User Controller Index</h1>
<p>One controller to manage the user actions, mainly login, logout, view and edit profile. Use the menu in 
the upper right corner to interact with these controller.</p>
<ul>
  <li><a href='<?=create_url('user/init')?>'>Init</a>
  <li><a href='<?=create_url('user/login')?>'>Login</a>
  <li><a href='<?=create_url('user/logout')?>'>Logout</a>
 <?php if($is_authenticated): ?>
  <li><a href='<?=create_url('user/profile')?>'>Profile</a>
  <?php endif; ?>
</ul>
