<?php

    // Check the lang has been set
    if (!isset($_GET['lang'], $_GET['type']) ||
        (strcmp($_GET['type'], 'text') != 0 &&
        strcmp($_GET['type'], 'img') != 0)) {

        header('Location: /page/main', true, 301);
        exit;
    }

    $lang = $_GET['lang'];
    $isText = strcmp($_GET['type'], 'text') == 0;

    $page_title = 'New Post';
    $page_content = 'content.php';
    include '../../layout/largebox.php';
?>
