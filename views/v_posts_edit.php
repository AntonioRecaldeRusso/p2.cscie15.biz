<div id="wrapper">
	<form method='post' <?php echo "action='/posts/p_edit/".$post_id."'"; ?> >
		<textarea name='content' id="textarea"><?php if(isset($post_id)) echo $post; else echo 'unset';?></textarea>
		<br>
		<input type='Submit' value=' Commit '>
		<br>
</div>
</form>