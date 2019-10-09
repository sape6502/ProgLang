<?php

    // Check lang is set
    session_start();
    if (!isset($_SESSION['proglang'])) {
        header('Location: /main');
        exit;
    }

    // Get article text content
    $proglang = $_SESSION['proglang'];
    $asciidocFile = '/article/langs/' . $proglang . '/' . $proglang . '.ad';
    $adFile = fopen($asciidocFile, 'r');
    $asciidocContent = fread($adFile, filesize($asciidocFile));
    fclose($adFile);

?>

<form action="/php/changearticle.php" method="post" id="articleform">
    <textarea name="article" rows="8" cols="80" form="articleform"><?= $asciidocContent ?></textarea>
    <input type="password" name="password" placeholder="Password">
    <input type="submit" name="submit" value="Change Article">
</form>
<br><a href="/article/?lang=<?= $proglang ?>">Back to article</a>

<?php

    if (isset($_SESSION['err_fieldsset']) && $_SESSION['err_fieldsset']) {
        echo '<h6 class="red">Password is required to edit the article.</h6>';
    }

    if (isset($_SESSION['err_password']) && $_SESSION['err_password']) {
        echo '<h6 class="red">That password is incorrect.</h6>';
    }

    if (isset($_SESSION['err_dbconn']) && $_SESSION['err_dbconn']) {
        echo '<h6 class="red">Failed to connect to database. Please try again later.</h6>';
    }

?>
