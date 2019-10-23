<?php

    // Check post id is set
    if (!isset($_GET['id']) || strcmp($_GET['id'], '') == 0) {
        header('Location: /page/main', true, 301);
        exit;
    }

    $postid = $_GET['id'];

    // Check user login
    include 'trustconfig.php';
    session_start();
    if (!isset($_SESSION['username'], $_SESSION['trustScore']) || $_SESSION['trustScore'] < $min_rate_posts) {
        header('Location: /page/post/?id=' . $postid, true, 301);
        exit;
    }

    $username = $_SESSION['username'];

    // Check fields are set
    if (!isset($_GET['u']) || (strcmp($_GET['u'], 'y') != 0 && strcmp($_GET['u'], 'n') != 0)) {
        header('Location: /page/post/?id=' . $postid, true, 301);
        exit;
    }

    $isUpvote = strcmp($_GET['u'], 'y') == 0;

    // Connect to database
    include '../config/db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        header('Location: /page/post/?id=' . $postid, true, 301);
        exit;
    }

    // Calculate new post score
    $post_score = $dbconn->get_cell('SELECT score FROM post WHERE ID_Post = ?', ValType::INT, $postid);
    if ($isUpvote) {
        $upvoteNum = 1;
    } else {
        $upvoteNum = 0;
    }

    // Update database
    $uid = $dbconn->get_cell('SELECT ID_User FROM user WHERE username = ?', ValType::STRING, $username);
    echo '<br>User ID: ' . $uid;
    $vid = $dbconn->get_query('SELECT ID_Vote FROM vote WHERE User_ID = ? AND Post_ID = ?', ValType::INT, $uid, ValType::INT, $postid)['ID_Vote'];
    echo '<br>Vote ID: ' . $vid;
    if (!$vid) {
        $dbconn->insert_row('vote', 'Post_ID', ValType::INT, $postid, 'User_ID', ValType::INT, $uid, 'isUpvote', ValType::INT, $upvoteNum);
    } else {
        $dbconn->update_cell('vote', 'isUpvote', ValType::INT, $upvoteNum, 'ID_Vote', ValType::INT, $vid);
    }

    // Update post score
    $post_score = $dbconn->get_query('SELECT
                        (SELECT COUNT(*) FROM vote WHERE Post_ID = ? AND isUpvote = 1) -
                        (SELECT COUNT(*) FROM vote WHERE Post_ID = ? AND isUpvote = 0)
                        AS score', ValType::INT, $postid, ValType::INT, $postid)['score'];
    echo '<br>Post score: ' . $post_score;
    $dbconn->update_cell('post', 'score', ValType::INT, $post_score, 'ID_Post', ValType::INT, $postid);

    // Redirect back to post
    header('Location: /page/post/?id=' . $postid, true, 301);
    exit;
