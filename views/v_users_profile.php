<div id="profile_content">

<?php if (isset($user_name)): ?>
<h1 class="title">This is the profile for<br> <?php echo $user_name?></h1>
<br>

<?php else: ?>
<h1 class="title">Hello <?php echo $user->username; ?>.</h1>
<table>

<?php endif; ?>



<table id="tfhover" class="tftable" border="1">
<tr>
<tr><td>First Name</td><td><?php echo $first_name; ?></td></tr>
<tr><td>Last Name</td><td><?php echo $last_name; ?></td></tr>
<tr><td>Followers</td><td><?php echo $followed; ?></td></tr>
<tr><td>Follows</td><td><?php echo $follows; ?></td></tr>
<tr><td>Posts</td><td><?php echo $posted; ?></td></tr>
</table>

<div>  
	<?php echo "<img id='image'src = '".AVATAR_PATH.$photo_link."' alt = 'User Photo'>"; ?>
</div>

<div <?php if (isset($user_name)) echo "id='upload_form'"; ?>> 
	<form method='POST' enctype="multipart/form-data" action='/users/p_upload_photo/'>
                <div class = "">
                    Upload a Profile Photo
                    <input type='file' name='file'>
                    <input type='submit'>
                </div>
    </form>
</div>
</div>

