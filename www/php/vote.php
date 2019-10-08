<?php

    // Check post id is set
    if (!isset($_GET['id']) || strcmp($_GET['id'], '') == 0) {
        header('Location: ../../main', true, 301);
        exit;
    }

    $postid = $_GET['id'];

    // Check user login
    include 'trustconfig.php';
    if (!isset($_SESSION['username'], $_SESSION['trustScore']) || $_SESSION['trustScore'] < $min_rate_posts) {
        header('Location: ../../post/?id=' . $postid, true, 301);
        exit;
    }

    $username = $_SESSION['username'];

    // Check fields are set
    if (!isset($_GET['u']) || (strcmp($_GET['u'], 'y') != 0 && strcmp($_GET['u'], 'n') != 0)) {
        header('Location: ../../post/?id=' . $postid, true, 301);
        exit;
    }

    $isUpvote = strcmp($_GET['u'], 'y') == 0;

    // Connect to database
    include 'db_connect.php';

    if ($conn_err) {
        header('Location: ../../post/?id=' . $postid, true, 301);
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
    $stmt = $conn->prepare('UPDATE post SET score = ? WHERE ID_Post = ?');
    $stmt->bind_param('ii', $post_score, $postid);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare('INSERT INTO vote (Post_ID, User_ID, isUpvote) VALUES (?, (SELECT ID_User FROM user WHERE username = ?), ?)');
    $stmt->bind_param('iii', $postid, $username, $upvoteNum);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Redirect back to post
    header('Location: ../../post/?id=' . $postid, true, 301);
    exit;
