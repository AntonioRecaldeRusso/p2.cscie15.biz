<?php if ($user): Router::redirect('users/index');?>
		

<?php else: ?>
	<div id="welcome"><h1>Welcome to myBlog.</h1></div>
<?php endif; ?>

<!-- buttons -->
<div id="container">
	<a href="/users/login" class="button">Login ►</a>
	<a href="/users/signup" class="button">Register ♥</a>
</div>

<div id="features">
	<h3>+1 Features:</h3><br>
	<p>Upload picture (via "Profile" page)<br>Edit Posts (via "My Posts" page)<br>View user profiles (via "View Users" page)</p>
</div>



