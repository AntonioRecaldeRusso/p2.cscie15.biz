<?php if (isset($user_name)): ?>
<h1>This is the profile for <?php echo $user_name?></h1>
<br>

<?php else: ?>
<h1>Hello <?php echo $user->username; ?>.<br> This is your profile page</h1>
<table>

<?php endif; ?>

<table id="tfhover" class="tftable" border="1">
<tr>
<tr><td>First Name</td><td><?php echo $user->first_name; ?></td></tr>
<tr><td>Last Name</td><td><?php echo $user->last_name; ?></td></tr>
<tr><td>Followers</td><td><?php echo $followed; ?></td></tr>
<tr><td>Follows</td><td><?php echo $follows; ?></td></tr>
<tr><td>Posts</td><td><?php echo $posted; ?></td></tr>
</table>

