<?php
    // Check if user is logged in and has enough trustscore to rate posts
    include '../php/trustconfig.php';
    $loggedIn = isset($_SESSION['username'], $_SESSION['trustScore']) && $_SESSION['trustScore'] >= $min_rate_posts;
?>
<div class="post">
    <div class="titlebar">
        <a href="/forum/?lang=<?= $lang ?>">Back to <?= $lang ?> Forum</a>
        <i>Posted by <a href="/user/?user=<?= $post_authu ?>"><?= $post_authu ?></a> at <?= $post_ctime ?></i>
    </div>
    <hr>
    <div class="statbox">
        <?php
            if ($loggedIn) {
                echo '
                    <a href="/php/vote.php/?id=' . $post_idnum . '&u=y"><img class="votebtn" src="/assets/img/upvote.png"></a>
                    <h3>' . $post_score . '</h3>
                    <a href="/php/vote.php/?id=' . $post_idnum . '&u=n"><img class="votebtn" src="/assets/img/downvote.png"></a>
                ';
            }
        ?>
    </div>
    <div class="contentbox">
        <?php
            if (isset($post_image)) {
                echo '<img src="' . $post_image . '" alt="Image failed to load.">';
            } else if (isset($post_ctext)) {
                echo '<p>' . $post_ctext . '</p>';
            } else {
                echo '<h5 class="orange">Something went wrong...</h5>';
            }
        ?>
    </div>
</div>
<hr>
<div class="comments">

</div>
