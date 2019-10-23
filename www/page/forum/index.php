<?php

    // Check lang is set
    if (!isset($_GET['lang'])) {
        header('Location: /page/main', true, 301);
        exit;
    }

    $lang = $_GET['lang'];

    $page_title = $lang . ' - Forum';
    $page_content = 'content.php';
    include '../../layout/default.php';
    $_SESSION['loginRedirect'] = '/page/forum/?lang=' . $lang;
?>
