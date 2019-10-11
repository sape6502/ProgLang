<?php

    // Check GET variables
    if (!isset($_GET['pid'])) {
        header('Location: /main', true, 301);
        exit;
    }

    session_start();

    $_SESSION['pid'] = $_GET['pid'];
    if (isset($_GET['cid'])) $_SESSION['cid'] = $_GET['cid'];

    $page_title = 'New Comment';
    $page_content = 'content.php';
    include '../layout/largebox.php';
?>
