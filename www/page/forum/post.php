<div class="post box">
    <div class="titlebar">
        <a href="/page/post/?id=<?= $post_idnum ?>"><h3><?= $post_title ?></h3></a>
        <i>Posted by <a href="/page/user/?user=<?= $post_authu ?>"><?= $post_authu ?></a> at <?= $post_ctime ?></i>
    </div>
    <hr>
    <div class="statbox">
        <?php
            if ($loggedIn) {
                echo '
                    <a href="../../php/post/vote.php/?id=' . $post_idnum . '&u=y"><img class="votebtn" src="../../assets/img/upvote.png"></a>
                    <h3>' . $post_score . '</h3>
                    <a href="../../php/post/vote.php/?id=' . $post_idnum . '&u=n"><img class="votebtn" src="../../assets/img/downvote.png"></a>
                ';
            } else {
                echo '
                <img class="votebtn" src="../../assets/img/upvote-grey.png">
                <h3>' . $post_score . '</h3>
                <img class="votebtn" src="../../assets/img/downvote-grey.png">';
            }
        ?>
    </div>
    <div class="contentbox">
        <?php
            if (isset($post_image)) {
                echo '<img src="' . $post_image . '" alt="Image failed to load.">';
            } else {
                echo '<p>' . $post_ctext . '</p>';
            }
        ?>
    </div>
</div>
