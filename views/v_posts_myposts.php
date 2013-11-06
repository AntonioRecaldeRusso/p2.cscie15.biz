<div id="wrapper">

<?php foreach($posts as $post): ?>

<article>
	<br>
	<div class="each_post">
		<!-- Output: First_name Last_name -->
	    <div id="username"><?php echo $post['first_name']?> <?php echo $post['last_name']?> posted:</div>

	    <!-- Ensure line breaks are respected -->
	    <div class="post_boder"> <p class="post"><?php echo nl2br($post['content']) ?></p></div>

	    <div id="edit_link"><h5><a <?php echo "href='/posts/edit/".$post['post_id']."'"; ?> >edit</a></h5></div>

	    <time id="time" datetime="<?php echo Time::display($post['created'],'Y-m-d G:i')?>">
	        <?php echo Time::display($post['created'])?>
	    </time>

	</div>
    <br>

</article>

<?php endforeach; ?>

</div>