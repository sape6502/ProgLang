<?php

    session_start();

    if (!isset($_SESSION['username'])) {
        header('Location: /main', true, 301);
        exit;
    }

    include 'initmsgs.php';
    $username = $_SESSION['username'];

    if (!isset($_POST['password']) ||
        (strcmp($_POST['password'], '') == 0)) {

        $_SESSION['err_fields_im'] = true;
        header('Location: /user?user=' . $username, true, 301);
        exit;
    }

    $target_file = basename($_FILES['picture']['name']);
    $filename = '../assets/img/profilepic/pp_' . uniqid() . '.' . strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $password = $_POST['password'];

    include 'db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: /user?user=' . $username, true, 301);
        exit;
    }

    //Check file dimensions
    /*
    $width = @getimagesize($_FILES['picture']['tmp_name'])[0];
    $height = @getimagesize($_FILES['picture']['tmp_name'])[1];
    if (abs($width - $height) > 5) {
        $_SESSION['err_fdims'] = true;
        header('Location: /user?user=' . $username, true, 301);
        exit;
    }
    */

    // Verify password
    $oldPic = $dbconn->get_cell('SELECT picture FROM user WHERE username = ?', ValType::STRING, $username);

    if (!$dbconn->verify_user($username, $password)) {
        $_SESSION['err_passwrong_im'] = true;
        header('Location: /user?user=' . $username, true, 301);
        exit;
    }

    $dbconn->update_cell('user', 'picture', ValType::STRING, $filename, 'username', ValType::STRING, $username);

    //delete old picture
    if (strcmp($oldPic, '../assets/img/profilepic/placeholder.png') != 0 && file_exists($oldPic)) {
        unlink($oldPic);
    }

    // Upload file
    if ($_FILES['picture']['size'] > 500000) {
        $_SESSION['err_ftoobig'] = true;
        header('Location: /user?user=' . $username, true, 301);
        exit;
    }

    if (!move_uploaded_file($_FILES['picture']['tmp_name'], $filename))
        $_SESSION['err_fupfail'] = true;


    $_SESSION['picture'] = $filename;
    header('Location: /user?user=' . $username, true, 301);
    exit;
