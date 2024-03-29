<?php

    // Check session variables
    session_start();

    $_SESSION['err_dbconn'] = false;
    $_SESSION['err_passwd'] = false;
    $_SESSION['err_fields'] = false;

    if (!isset($_SESSION['postid'], $_SESSION['moduser'], $_SESSION['postuser'], $_SESSION['lang'])) {
        header('Location: /page/main', true, 301);
        exit;
    }

    $postid = $_SESSION['postid'];
    $postuser = $_SESSION['postuser'];
    $moduser = $_SESSION['moduser'];
    $lang = $_SESSION['lang'];

    unset($_SESSION['postid'], $_SESSION['moduser'], $_SESSION['postuser']);

    // Connect to the database
    include '../config/db_connect.php';
    $dbconn = new DBConn;

    if ($dbconn->conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: /page/post/?id=' . $postid, true, 301);
        exit;
    }

    // Check form variables
    if (!isset($_POST['password']) || strcmp($_POST['password'], '') == 0) {
        $_SESSION['err_fields'] = true;
        header('Location: /page/post/?id=' . $postid, true, 301);
        exit;
    }

    $password = $_POST['password'];

    // Validate user password
    if (!$dbconn->verify_user($postuser, $password) && !$dbconn->verify_user($moduser, $password)) {
        $_SESSION['err_passwd'] = true;
        header('Location: /page/post/?id=' . $postid, true, 301);
        exit;
    }

    // Delete post
    $img = $dbconn->get_cell('SELECT image FROM post WHERE ID_post = ?', ValType::INT, $postid);
    $dbconn->delete_row('post', 'ID_post', ValType::INT, $postid);

    if ($img != NULL && file_exists($img)) {
        unlink($img);
    }

    // Redirect back to forum
    header('Location: /page/forum/?lang=' . $lang, true, 301);
    exit;
