<?php

    // Send user back to main page if no article is specified
    if (!isset($_GET['lang']) || strcmp($_GET['lang'], '') == 0)
        header('Location: /page/main' . $username, true, 301);
    $proglang = $_GET['lang'];

    $page_title = 'Article - ' . $proglang;
    $page_content = 'content.php';
    include '../../layout/default.php';
    $_SESSION['loginRedirect'] = '/page/article/?lang=' . $proglang;
?>
