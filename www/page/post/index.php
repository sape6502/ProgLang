<?php

    // Check post ID is set
    if (!isset($_GET['id'])) {
        header('Location: /page/main', true, 301);
        exit;
    }

    // Connect to database
    include '../../php/config/db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        header('Location: ../page/main', true, 301);
        exit;
    }

    // Load post info
    $result = $dbconn->get_query('SELECT * FROM post JOIN article ON lang_Article_ID = ID_Article JOIN user ON creator_User_ID = ID_User WHERE ID_Post = ?',
                        ValType::INT, $_GET['id']);

    $post_idnum = $result['ID_Post'];
    $post_title = $result['contentTitle'];
    $post_ctext = $result['contentText'];
    $post_image = $result['image'];
    $post_score = $result['score'];
    $post_ctime = $result['timeCreated'];
    $post_authu = $result['username'];
    $post_plang = $result['name'];

    $page_title = $post_title;
    $page_content = 'content.php';
    include '../../layout/default.php';
    $_SESSION['loginRedirect'] = '/page/post/?id=' . $post_idnum;
?>
