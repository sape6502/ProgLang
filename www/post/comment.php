<div class="box">
    <h6><?= $comment_auth ?> - <?= $comment_time ?></h6>
    <a href="/comment/index.php/?pid=<?= $post_idnum ?>&cid=<?= $comment_idnm ?>">Reply to this comment.</a>
    <hr>
    <p>
        <?= $comment_text ?>
    </p>
    <?php
        if (isset($_SESSION['username'])) {
            $user = $_SESSION['username'];
            $moduser = $dbconn->get_cell('SELECT username FROM user JOIN article ON author_User_ID = ID_User WHERE name = ?', ValType::STRING, $post_plang);

            if (strcmp($user, $comment_auth) == 0 || strcmp($user, $moduser) == 0) {
                echo '
                    <hr>
                    <form action="/php/comment/delete.php/?cid=' . $comment_idnm . '" method="post">
                        <input type="password" name="password" placeholder="Password">
                        <input class="bg-red" type="submit" name="submit" value="Delete Comment">
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
        }
    ?>
</div>
<div class="indented">
    <?php loadComments($comment_idnm, $post_idnum, $dbconn, $post_plang); ?>
</div>
