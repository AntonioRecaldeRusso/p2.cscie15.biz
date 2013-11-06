<div id="wrapper">
    <h4 id="table_title">Click the Users to visit their profiles or toggle Follow-Unfollow!</h4>

    <div class="table" >
                <table >


    <?php foreach($users as $user): ?>
                    <tr>
                        <td class="name">

    <!-- Print this user's name -->
    <a href=<?php echo "'/users/profile/".$user['username']."'" ?>><?php echo $user['first_name']?> <?php echo $user['last_name']?></a>

                        </td>
                        <td class="link">

    <!-- If there exists a connection with this user, show a unfollow link -->
    <?php if(isset($connections[$user['user_id']])): ?>
        <a class="unfollow" href='/posts/unfollow/<?php echo $user['user_id']?>'>Unfollow</a>

    <!-- Otherwise, show the follow link -->
    <?php else: ?>
        <a class="follow" href='/posts/follow/<?php echo $user['user_id']?>'>Follow</a>
    <?php endif; ?>
                    
                        </td>
                    </tr>
    
    <?php endforeach; ?>
                
                </table>
</div>
            