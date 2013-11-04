<?php if ($user): Router::redirect('users/index');?>
		

<?php else: ?>
	<div id="welcome"><h1>Welcome to myBlog.<h1></div>
<?php endif; ?>

<div id="container">
	<a href="/users/login" class="button">Login ►</a>
	<a href="/users/signup" class="button">Register ♥</a>
</div>

<div id="features">
	<h3>+1 Features:</h3><br>
	<p>Upload picture<br>Another feature</p>
</div>



