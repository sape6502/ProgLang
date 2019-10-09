<?php

    session_start();

    // Check user is logged in and has a high enough trust score
    include '../php/trustconfig.php';
    if (!isset($_SESSION['username'], $_SESSION['trustScore']) || $_SESSION['trustScore'] < $min_make_posts) {
        header('Location: /forum/?lang=' . $lang, true, 301);
        exit;
    }

    // Include form
    $_SESSION['lang'] = $lang;
    $_SESSION['isText'] = $isText;

    if ($isText) {
        include 'textform.html';
    } else {
        include 'imgform.html';
    }

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
