<?php
    // Check if user is logged in and has enough trustscore to rate posts
    include '../../php/config/minimum_trust.php';
    $loggedIn = isset($_SESSION['username'], $_SESSION['trustScore']) && $_SESSION['trustScore'] >= $min_rate_posts;

    $moduser = $dbconn->get_cell('SELECT username from user JOIN article ON author_User_ID = ID_User WHERE name = ?', ValType::STRING, $post_plang);

?>
<div class="post">
    <div class="titlebar">
        <a href="/page/forum/?lang=<?= $post_plang ?>">Back to <?= $post_plang ?> Forum</a>
        <i>Posted by <a href="/page/user/?user=<?= $post_authu ?>"><?= $post_authu ?></a> at <?= $post_ctime ?></i>
    </div>

    <?php
        if ($loggedIn && (strcmp($_SESSION['username'], $moduser) == 0 ||
            strcmp($_SESSION['username'], $post_authu) == 0)) {

            $_SESSION['postid'] = $post_idnum;
            $_SESSION['moduser'] = $moduser;
            $_SESSION['postuser'] = $post_authu;
            echo '
                <hr>
                <form action="/php/post/delete.php" method="post">
                    <input type="password" name="password" placeholder="Password">
                    <input type="submit" name="submit" value="Delete Post" class="bg-red">
                </form>
            ';

            if (isset($_SESSION['err_dbconn']) && $_SESSION['err_dbconn'])
                echo '<h6 class="red">Failed to connect to the database.
                Please try again later.</h6>';

            if (isset($_SESSION['err_passwd']) && $_SESSION['err_passwd'])
                echo '<h6 class="red">That Password is incorrect.</h6>';

            if (isset($_SESSION['err_fields']) && $_SESSION['err_fields'])
                echo '<h6 class="red">Password is required to delete this post.</h6>';
        }
    ?>

    <hr>
    <div class="statbox">
        <?php
            if ($loggedIn) {
                echo '
                    <a href="/php/post/vote.php/?id=' . $post_idnum . '&u=y"><img class="votebtn" src="../../assets/img/upvote.png"></a>
                    <h3>' . $post_score . '</h3>
                    <a href="/php/post/vote.php/?id=' . $post_idnum . '&u=n"><img class="votebtn" src="../../assets/img/downvote.png"></a>
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
<?php
    if ($loggedIn)
    echo '
        <a href="/page/comment/index.php/?pid=' . $post_idnum . '">Comment on this post</a>
        <hr>
    ';
?>
<div class="comments">
    <?php

        // Loads all comments for the set post id and comment ID num
        function loadComments($comment_idnm, $post_idnum, $dbconn, $post_plang) {
            if ($comment_idnm == NULL) {
                $results = $dbconn->get_full('SELECT  * FROM comment WHERE thread_Post_ID = ? AND parent_Comment_ID IS NULL',
                ValType::INT, $post_idnum);
            } else {
                $results = $dbconn->get_full('SELECT  * FROM comment WHERE thread_Post_ID = ? AND parent_Comment_ID = ?',
                ValType::INT, $post_idnum, ValType::INT, $comment_idnm);
            }

            // Display each comment (Recursively loads each comment's comments as well)
            if ($results != NULL) {
                while ($comment = $results->fetch_assoc()) {
                    $comment_idnm = $comment['ID_Comment'];
                    $comment_text = $comment['contentText'];
                    $comment_time = $comment['timeCreated'];
                    $userid = $comment['creator_User_ID'];

                    if ($userid != NULL) {
                        $comment_auth = $dbconn->get_cell('SELECT username FROM user WHERE ID_User = ?', ValType::INT, $userid);
                    } else {
                        $comment_auth = '[deleted]';
                    }

                    include 'comment.php';
                }
            }
        }

        loadComments(NULL, $post_idnum, $dbconn, $post_plang);

        unset($_SESSION['err_dbconn'], $_SESSION['err_fields'], $_SESSION['err_passwd']);
    ?>
</div>
