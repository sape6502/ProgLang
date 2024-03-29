<?php

    // Check session variables
    session_start();

    $_SESSION['err_dbconn'] = false;
    $_SESSION['err_fields'] = false;
    $_SESSION['err_passwd'] = false;

    // Check user's logged in and that the post id's set
    if (!isset($_SESSION['username'], $_SESSION['pid'])) {
        header('Location: /page/main', true, 301);
        exit;
    }

    $postid = $_SESSION['pid'];
    $hasParent = isset($_SESSION['cid']);
    unset($_SESSION['pid']);
    if ($hasParent) {
        $parent = $_SESSION['cid'];
        unset($_SESSION['cid']);
    }

    // Check fields are set
    if (!isset($_POST['comment'], $_POST['password']) ||
        strcmp($_POST['comment'], '') == 0 ||
        strcmp($_POST['password'], '') == 0) {

        $_SESSION['err_fields'] = true;
        header('Location: /page/comment/?pid=' . $postid, true, 301);
        exit;
    }

    // Check database connection
    include '../config/db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: /page/comment/?pid=' . $postid, true, 301);
        exit;
    }

    // Check user's password
    if (!$dbconn->verify_user($_SESSION['username'], $_POST['password'])) {
        $_SESSION['err_passwd'] = true;
        header('Location: /page/comment/?pid=' . $postid, true, 301);
        exit;
    }

    // Create new comment
    $uid = $dbconn->get_cell('SELECT ID_User FROM user WHERE username = ?', ValType::STRING, $_SESSION['username']);

    if ($hasParent) {
        $dbconn->insert_row('comment',
            'thread_Post_ID', ValType::INT, $postid,
            'parent_Comment_ID', ValType::INT, $parent,
            'creator_User_ID', ValType::INT, $uid,
            'contentText', ValType::STRING, $_POST['comment']
        );
    } else {
        $dbconn->insert_row('comment',
            'thread_Post_ID', ValType::INT, $postid,
            'creator_User_ID', ValType::INT, $uid,
            'contentText', ValType::STRING, $_POST['comment']
        );
    }

    // Redirect back to post
    header('Location: /page/post/?id=' . $postid, true, 301);
    exit;
