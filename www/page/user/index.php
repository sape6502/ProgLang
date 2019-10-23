<?php

    // Log user out if logout option is set
    if (isset($_GET['log']) && strcmp($_GET['log'], 'out') == 0) {
        session_start();

        if (isset($_SESSION['loginRedirect'])) {
            $redirect = $_SESSION['loginRedirect'];
        }

        session_unset();
        session_destroy();

        if (isset($redirect)) {
            header('Location: ' . $redirect, true, 301);
            exit;
        }
    }

    if (!isset($_GET['user'])) {
        $user = "placeholder";
    } else {
        $user = $_GET['user'];
    }

    $page_title = $user . '\'s User Page';
    $page_content = 'content.php';
    include '../../layout/default.php';
    $_SESSION['loginRedirect'] = '/page/user/?user=' . $user;
?>
