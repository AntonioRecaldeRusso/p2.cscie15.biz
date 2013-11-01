
<div id="wrapper">
	<form method='post' action='/posts/p_add'>
		<textarea name='content' id="textarea"></textarea>
		<br>
		<input type='Submit' value='Add new post'>
		<br>
		<p id="last_input"><?php if(isset($message)) echo $message; ?></p>
</div>
</form>