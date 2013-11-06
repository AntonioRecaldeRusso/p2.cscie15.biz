<div id="wrapper">
	<!-- Form -->
	<form method='post' action='/posts/p_add'>
		<textarea name='content' id="textarea"></textarea>
		<br>
		<input type='Submit' value='Add new post'>
		<br>

		<!-- output the last post added -->
		<div>
			<h2 id="last_input_title"> <?php if(isset($last_input)) echo 'Post Added:'; ?> </h2>
			<p id="last_input"><?php if(isset($last_input)) echo nl2br($last_input) ?></p>
		</div>
	</form>
</div>
