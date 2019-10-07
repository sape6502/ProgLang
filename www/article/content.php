<?php

    // Connect to the database
    include '../php/db_connect.php';

    if ($conn_err) {
        echo '<h4 class="red">Failed to connect to database. Please try again later</h4>';
        exit;
    }

    // Get article from database
    $stmt = $conn->prepare('SELECT * FROM article JOIN user ON author_User_ID = ID_User WHERE name = ?');
    $stmt->bind_param('s', $proglang);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Check article exists
    if ($result == NULL) {
        echo '<h4 class="orange">That article doesn\'t exist</h4>';
        exit;
    }

    // Get article and author data from result
    $helpfulness = $result['helpfulness'];
    $timeCreated = $result['timeCreated'];
    $author = $result['username'];

    $htmlFile = 'langs/' . $proglang . '/' . $proglang . '.html';
    $asciidocFile = 'langs/' . $proglang . '/' . $proglang . '.ad';
    $pdfFile = 'langs/' . $proglang . '/' . $proglang . '.pdf';

    $_SESSION['lang'] = $proglang;

?>

<a href="../article/?lang=<?= $proglang ?>" class="greyed">Article</a>
<a href="../forum/?lang=<?= $proglang ?>">Forum</a>
<hr>
<?php include $htmlFile; ?>
<hr>
<h5>Downloads:</h5>
<a href="langs/<?= $proglang ?>/<?= $proglang ?>.ad" download>AsciiDoc Download</a>
<a href="langs/<?= $proglang ?>/<?= $proglang ?>.pdf" download>PDF Download</a>
<hr>
<i>Created by <a href="../user/?user=<?= $author ?>"><?= $author ?></a> on <?= $timeCreated ?>.</i>
<br><hr>

<h5>Did you find the article helpful?</h5>
<form action="../php/changehelp.php/?lang=<?= $proglang ?>" method="post">
    <input type="submit" name="h" value="Yes">
    <input type="submit" name="h" value="No">
</form>

<i><?= $helpfulness ?> people found this helpful.</i>

<?php
    // Stop displaying page for everybody but the author
    if (!isset($_SESSION['username']) ||
    strcmp($_SESSION['username'], $author) != 0) {
        exit;
    }

    $_SESSION['proglang'] = $proglang;
?>

<hr>
<a href="../edit">Edit Article</a>

<form action="../php/delarticle.php" method="post">
    <input type="password" name="password" placeholder="Password">
    <input type="submit" name="submit" value="Delete Article" class="bg-red">
</form>

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
