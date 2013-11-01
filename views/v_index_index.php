<?php if ($user): Router::redirect('users/index');?>
		

<?php else: ?>
	Welcome to myBlog. Please <a href="users/signup">sign up</a> or <a href="users\login">log in</a>
<?php endif; ?>
