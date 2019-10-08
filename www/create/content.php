<?php

    session_start();

    // Check user is logged in
    if (!isset($_SESSION['username'])) {
        header('Location: ../main', true, 301);
        exit;
    }

?>

<form action="../php/createarticle.php" method="post" id="createarticle">
    <input type="text" name="lang" placeholder="Language Name" readonly onfocus="this.removeAttribute('readonly');"><br>
    Article Content:<br>
    <textarea name="article" rows="8" cols="80" form="createarticle"></textarea>
    <input type="password" name="password" placeholder="Password">
    <input type="submit" name="submit" value="Create Article">
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

?>

<hr>
<h5>Need help?</h5>
<p>
    Proglang uses AsciiDoc for markup and layout. To learn the
    basics of AsciiDoc, see <a href="https://asciidoctor.org/docs/asciidoc-writers-guide/">this</a>
    writer's guide by Asciidoctor. AsciiDoc was chosen because
    the plaintext is still quite pleasing to look at while
    offering more options than plain markdown.
</p>
