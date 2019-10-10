<?php
    // Check if user is logged in and has enough trustscore to rate posts
    include '../php/trustconfig.php';
    $loggedIn = isset($_SESSION['username'], $_SESSION['trustScore']) && $_SESSION['trustScore'] >= $min_rate_posts;

    $moduser = $dbconn->get_cell('SELECT username from user JOIN article ON author_User_ID = ID_User WHERE name = ?', ValType::STRING, $post_plang);

?>
<div class="post">
    <div class="titlebar">
        <a href="/forum/?lang=<?= $lang ?>">Back to <?= $lang ?> Forum</a>
        <i>Posted by <a href="/user/?user=<?= $post_authu ?>"><?= $post_authu ?></a> at <?= $post_ctime ?></i>
    </div>

    <?php
        if ($loggedIn && (strcmp($_SESSION['username'], $moduser) == 0 ||
            strcmp($_SESSION['username'], $post_authu))) {

            $_SESSION['postid'] = $post_idnum;
            $_SESSION['moduser'] = $moduser;
            $_SESSION['postuser'] = $post_authu;
            echo '
                <hr>
                <form action="/php/delpost.php" method="post">
                    <input type="password" name="password" placeholder="Password">
                    <input type="submit" name="submit" value="Delete Post" class="bg-red">
                </form>
            ';

            if (isset($_SESSION['err_dbconn']) && $_SESSION['err_dbconn']) {
                echo '<h6 class="red">Failed to connect to the database.
                Please try again later.</h6>';
            }

            if (isset($_SESSION['err_passwd']) && $_SESSION['err_passwd']) {
                echo '<h6 class="red">That Password is incorrect.</h6>';
            }

            if (isset($_SESSION['err_fields']) && $_SESSION['err_fields']) {
                echo '<h6 class="red">Password is required to delete this post.</h6>';
            }
        }
    ?>

    <hr>
    <div class="statbox">
        <?php
            if ($loggedIn) {
                echo '
                    <a href="/php/changevote.php/?id=' . $post_idnum . '&u=y"><img class="votebtn" src="/assets/img/upvote.png"></a>
                    <h3>' . $post_score . '</h3>
                    <a href="/php/changevote.php/?id=' . $post_idnum . '&u=n"><img class="votebtn" src="/assets/img/downvote.png"></a>
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
