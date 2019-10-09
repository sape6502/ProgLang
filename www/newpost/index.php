<?php

    // Check the lang has been set
    if (!isset($_GET['lang'])) {
        header('Location: /main', true, 301);
    }

    $page_title = 'New Post';
    $page_content = 'content.php';
    include '../layout/smallbox.php';
?>
