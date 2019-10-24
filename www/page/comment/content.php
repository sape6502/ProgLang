<form action="/php/comment/create.php" method="post" id="commentform">
    <textarea name="comment" rows="8" cols="80" form="commentform"></textarea><br>
    <input type="password" name="password" placeholder="Password">
    <input type="submit" name="submit" value="Comment">
</form>

<?php

    if (isset($_SESSION['err_dbconn']) && $_SESSION['err_dbconn']) {
        echo '<h5 class="red">Failed to connect to database. Please try again later.</h5>';
    }

    if (isset($_SESSION['err_fields']) && $_SESSION['err_fields']) {
        echo '<h5 class="red">All fields are required.</h5>';
    }

    if (isset($_SESSION['err_passwd']) && $_SESSION['err_passwd']) {
        echo '<h5 class="red">That password is incorrect.</h5>';
    }

    unset($_SESSION['err_dbconn'], $_SESSION['err_fields'], $_SESSION['err_passwd']);

?>

<hr>
<a href="/page/post/?id=<?= $_GET['pid'] ?>">Back to post</a>
