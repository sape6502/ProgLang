<?php

    // Check post id is set
    if (!isset($_GET['id']) || strcmp($_GET['id'], '') == 0) {
        header('Location: /main', true, 301);
        exit;
    }

    $postid = $_GET['id'];

    // Check user login
    include 'trustconfig.php';
    if (!isset($_SESSION['username'], $_SESSION['trustScore']) || $_SESSION['trustScore'] < $min_rate_posts) {
        header('Location: /post/?id=' . $postid, true, 301);
        exit;
    }

    $username = $_SESSION['username'];

    // Check fields are set
    if (!isset($_GET['u']) || (strcmp($_GET['u'], 'y') != 0 && strcmp($_GET['u'], 'n') != 0)) {
        header('Location: /post/?id=' . $postid, true, 301);
        exit;
    }

    $isUpvote = strcmp($_GET['u'], 'y') == 0;

    // Connect to database
    include 'db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        header('Location: /post/?id=' . $postid, true, 301);
        exit;
    }

    // Calculate new post score
    if ($isUpvote) {
        $upvoteNum = 1;
        $post_score++;
    } else {
        $upvoteNum = 0;
        $post_score--;
    }

    // Update database
    $dbconn->update_cell('post', 'score', ValType::INT, $post_score, 'ID_Post', ValType::INT, $postid);
    $uid = $dbconn->get_cell('SELECT ID_User FROM user WHERE username = ?', ValType::STRING, $username);
    $dbconn->insert_row('vote', 'Post_ID', ValType::INT, $postid, 'User_ID', ValType::INT, $uid, 'isUpvote', ValType::INT, $upvoteNum);

    // Redirect back to post
    header('Location: /post/?id=' . $postid, true, 301);
    exit;
