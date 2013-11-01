<?php if (isset($user_name)): ?>
<h1>This is the profile for <?php echo $user_name?></h1>
<br>

<?php else: ?>
<h1>Hello <?php echo $user->username; ?>.<br> This is your profile page</h1>
<table>

<?php endif; ?>


<table id="tfhover" class="tftable" border="1">
<tr>
<tr><td>First Name</td><td>Row:1 Cell:2</td></tr>
<tr><td>Last Name</td><td>Row:1 Cell:2</td></tr>
<tr><td>Followers</td><td>Row:1 Cell:2</td></tr>
<tr><td>Follows</td><td>Row:1 Cell:2</td></tr>
<tr><td>Posts</td><td>Row:1 Cell:2</td></tr>
</table>

