<!DOCTYPE html>
<html>
<head>
	<title><?php if(isset($title)) echo $title; ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
	
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<!-- Controller Specific JS/CSS -->
	<?php if(isset($client_files_head)) echo $client_files_head; ?>
	
</head>

<body>
	<div id="top">
		<!-- The first php block added for alignment purposes. Set to invisible -->	
		<h1 id="header"><div id="offset_balancer"><?php if (isset($user->username)) echo $user->username; ?></div>myBlog<a id="user_logged"><?php if (isset($user->username)) echo $user->username; ?></a></h1> <br>
			<div id="navbar"> 
		  	<ul> 
			<li><a href="/users/profile">Profile</a></li>
			<li><a href="/posts/add">Add Post</a></li>
			<li><a href="/posts/myposts">My Posts</a></li>  
			<li><a href="/posts/index">View Posts</a></li> 
			<li><a href="/posts/users">View Users</a></li> 
			<li><a href="/users/logout">Logout</a></li> 
		  	</ul> 
		</div>
	</div> 

	<?php if(isset($content)) echo $content; ?>

	<?php if(isset($client_files_body)) echo $client_files_body; ?>
</body>
</html>