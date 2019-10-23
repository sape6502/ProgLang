<?php

    session_start();

    if (!isset($_SESSION['username'])) {
        header('Location: /page/main', true, 301);
        exit;
    }

    include 'initmsgs.php';
    $username = $_SESSION['username'];

    if (!isset($_POST['oldPass'], $_POST['newPass1'], $_POST['newPass2']) ||
        (strcmp($_POST['oldPass'], '') == 0) ||
        (strcmp($_POST['newPass1'], '') == 0) ||
        (strcmp($_POST['newPass2'], '') == 0)) {

        $_SESSION['err_fields_ch'] = true;
        header('Location: /page/user?user=' . $username, true, 301);
        exit;
    }

    $oldPass = $_POST['oldPass'];
    $newPass1 = $_POST['newPass1'];
    $newPass2 = $_POST['newPass2'];

    include '../config/db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: /page/user?user=' . $username, true, 301);
        exit;
    }

    if (strcmp($newPass1, $newPass2)) {
        $_SESSION['err_passmatch'] = true;
        header('Location: /page/user?user=' . $username, true, 301);
        exit;
    }

    // Get old user password hash

    if (!$dbconn->verify_user($username, $oldPass)) {
        $_SESSION['err_passwrong_ch'] = true;
        header('Location: /page/user?user=' . $username, true, 301);
        exit;
    }

    // Update password with new one
    $newHash = password_hash($newPass1, PASSWORD_DEFAULT);
    $dbconn->update_cell('user', 'passwordHash', ValType::STRING, $newHash, 'username', ValType::STRING, $username);

    $_SESSION['succ_passchange'] = true;
    header('Location: /page/user?user=' . $username, true, 301);
    exit;
