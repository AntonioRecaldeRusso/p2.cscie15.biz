<?php foreach($posts as $post): ?>

<article>
	<br>
    <p><?php echo $post['first_name']?> <?php echo $post['last_name']?> posted:</p>

    <p><?php echo $post['content']?></p>

    <time datetime="<?php echo Time::display($post['created'],'Y-m-d G:i')?>">
        <?php echo Time::display($post['created'])?>
    </time>
    <br>

</article>

<?php endforeach; ?>