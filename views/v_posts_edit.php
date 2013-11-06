<div id="wrapper">

	
	<form method='post' <?php echo "action='/posts/p_edit/".$post_id."'"; ?> >

		<!-- Place old post as inside of text area -->
		<textarea name='content' id="textarea"><?php if(isset($post_id)) echo $post;?></textarea>
		<br>
		<input type='Submit' value=' Commit '>
		<br>
	</form>


</div>