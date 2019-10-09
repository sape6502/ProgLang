<?php

    // Check session variables
    session_start();

    $_SESSION['err_fieldsset'] = false;
    $_SESSION['err_password'] = false;
    $_SESSION['err_dbconn'] = false;

    if (!isset($_SESSION['lang'], $_SESSION['isText'])) {
        header('Location: /main', true, 301);
        exit;
    }

    $lang = $_SESSION['lang'];
    $isText = $_SESSION['isText'];

    // Check user is logged in
    if (!isset($_SESSION['username'])) {
        header('Location: /main', true, 301);
        exit;
    }

    $username = $_SESSION['username'];

    // Check all fields are filled in
    if (!isset($_POST['title'], $_POST['password']) ||
        (isset($_POST['text']) && isset($_POST['img']))) {

        $_SESSION['err_fieldsset'] = true;
        header('Location: /main', true, 301);
        exit;
    }

    // Verify user password
    include 'verifyuser.php';
    if ($conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: /main', true, 301);
        exit;
    }

    if ($verified) {
        $stmt = $conn->prepare();
    }
