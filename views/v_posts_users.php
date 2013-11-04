<div id="wrapper">

    <div class="table" >
                <table >


    <?php foreach($users as $user): ?>
                    <tr>
                        <td class="first_name">

    <!-- Print this user's name -->
    <?php echo $user['first_name']?> <?php echo $user['last_name']?>

                        </td>
                        <td class="link">

    <!-- If there exists a connection with this user, show a unfollow link -->
    <?php if(isset($connections[$user['user_id']])): ?>
        <a href='/posts/unfollow/<?php echo $user['user_id']?>'>Unfollow</a>

    <!-- Otherwise, show the follow link -->
    <?php else: ?>
        <a href='/posts/follow/<?php echo $user['user_id']?>'>Follow</a>
    <?php endif; ?>
                    
                        </td>
                    </td>
    
    <?php endforeach; ?>
                
                </table>
</div>
            