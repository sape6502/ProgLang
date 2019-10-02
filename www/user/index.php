<?php
    if (!isset($_GET['user'])) {
        $user = "placeholder";
    } else {
        $user = $_GET['user'];
    }

    $page_title = $user . '\'s User Page';
    $page_content = '../user/content.php';
    include '../layout/default.php';
?>
