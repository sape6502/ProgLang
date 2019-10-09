<?php

    // Check post ID is set
    if (!isset($_GET['id'])) {
        header('Location: /main', true, 301);
        exit;
    }

    // Connect to database
    include '/php/db_connect.php';

    if ($conn_err) {
        header('Location: ../main', true, 301);
        exit;
    }

    // Load post info
    $stmt = $conn->prepare('SELECT * FROM post JOIN article ON lang_Article_ID = ID_Article JOIN user ON creator_User_ID = ID_User WHERE ID_Post = ?');
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->close();

    $post_idnum = $result['ID_Post'];
    $post_title = $result['contentTitle'];
    $post_ctext = $result['contentText'];
    $post_image = $result['image'];
    $post_score = $result['score'];
    $post_ctime = $result['timeCreated'];
    $post_authu = $result['username'];
    $lang = $result['name'];

    $page_title = $post_title;
    $page_content = 'content.php';
    include '/layout/default.php';
?>
