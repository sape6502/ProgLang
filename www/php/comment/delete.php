<?php

    // Check session variables
    session_start();

    $_SESSION['err_dbconn'] = false;
    $_SESSION['err_fields'] = false;
    $_SESSION['err_passwd'] = false;

    if (!isset($_SESSION['postid'], $_SESSION['username'])) {
        header('Location: /main', true, 301);
        exit;
    }

    $username = $_SESSION['username'];
    $postid = $_SESSION['postid'];

    // Get post id and parent comment (if set)
    $comment = $_GET['cid'];

    // Check fields are set
    if (!isset($_POST['password'])) {
        $_SESSION['err_fields'] = true;
        header('Location: /post/?id=' . $postid, true, 301);
        exit;
    }

    // Check database connection
    include '../db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: /post/?id=' . $postid, true, 301);
        exit;
    }

    // Check if it's the comment poster or the local moderator
    $user = $dbconn->get_cell('SELECT username FROM user JOIN comment ON creator_User_ID = ID_User WHERE ID_Comment = ?', ValType::INT, $comment);
    $moduser = $dbconn->get_cell('SELECT username FROM comment
        JOIN post ON thread_Post_ID = ID_Post
        JOIN article ON lang_Article_ID = ID_Article
        JOIN user ON author_User_ID = ID_User
        WHERE ID_Comment = ?', ValType::INT, $comment);
    if (strcmp($username, $user) != 0 && !strcmp($username, $moduser) != 0) {
        header('Location: /post/?id=' . $postid, true, 301);
        exit;
    }

    // Check user's password
    if (!$dbconn->verify_user($user, $_POST['password']) && !$dbconn->verify_user($moduser, $_POST['password'])) {
        $_SESSION['err_passwd'] = true;
        header('Location: /post/?id=' . $postid, true, 301);
        exit;
    }

    // Delete comment contents
    $dbconn->update_cell('comment', 'contentText', ValType::STRING, '[This comment was deleted]', 'ID_Comment', ValType::INT, $comment);
    $dbconn->nullify_cell('comment', 'creator_User_ID', 'ID_Comment', ValType::INT, $comment);

    // Redirect back to post
    header('Location: /post/?id=' . $postid, true, 301);
    exit;
