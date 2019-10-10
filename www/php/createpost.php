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
    $type = 'img';
    if ($isText) $type = 'text';

    // Check user is logged in
    if (!isset($_SESSION['username'])) {
        header('Location: /newpost/?lang=' . $lang . '&type=' . $type, true, 301);
        exit;
    }

    $username = $_SESSION['username'];

    // Check all fields are filled in
    if (!isset($_POST['title'], $_POST['password']) ||
        (isset($_POST['text']) && isset($_POST['img']))) {

        $_SESSION['err_fieldsset'] = true;
        header('Location: /newpost/?lang=' . $lang . '&type=' . $type, true, 301);
        exit;
    }

    $password = $_POST['password'];
    $title = $_POST['title'];

    // Verify user's password
    include 'db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: /newpost/?lang=' . $lang . '&type=' . $type, true, 301);
        exit;
    }

    if (!$dbconn->verify_user($username, $password)) {
        $_SESSION['err_passwd'] = true;
        header('Location: /newpost/?lang=' . $lang . '&type=' . $type, true, 301);
        exit;
    }

    // Get database IDs
    $AID = $dbconn->get_cell('SELECT ID_Article FROM article WHERE name = ?', ValType::STRING, $lang);
    $UID = $dbconn->get_cell('SELECT ID_User FROM user WHERE username = ?', ValType::STRING, $username);

    // Create a new post
    if ($isText) {
        $dbconn->insert_row('post', 'lang_Article_ID', ValType::INT, $AID,
                                    'creator_User_ID', ValType::INT, $UID,
                                    'contentTitle', ValType::STRING, $title,
                                    'contentText', ValType::STRING, $_POST['text']);
    } else {
        $target_file = basename($_FILES['img']['name']);
        $filename = '../assets/img/postimgs/img_' . uniqid() . '.' . strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        move_uploaded_file($_FILES['img']['tmp_name'], $filename);

        $dbconn->insert_row('post', 'lang_Article_ID', ValType::INT, $AID,
                                    'creator_User_ID', ValType::INT, $UID,
                                    'contentTitle', ValType::STRING, $title,
                                    'image', ValType::STRING, $filename);
    }

    // Redirect to uploaded post
    $PID = $dbconn->get_cell('SELECT ID_Post FROM post WHERE creator_User_ID = ? ORDER BY timeCreated DESC LIMIT 1', ValType::INT, $UID);
    header('Location: /post/?id=' . $PID, true, 301);
    exit;
