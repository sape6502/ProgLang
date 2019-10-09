<?php

    // Connect to database
    include '../php/db_connect.php';
    $dbconn = new DBConn();
    if ($dbconn->conn_err) {
        header('Location: /main', true, 301);
        exit;
    }

    // Check if user is logged in
    include '../php/trustconfig.php';
    $loggedIn = isset($_SESSION['username'], $_SESSION['trustScore']) && $_SESSION['trustScore'] >= $min_rate_posts;

    // Get all posts for this language
    $result = $dbconn->get_full('SELECT * FROM post JOIN article ON lang_Article_ID = ID_Article JOIN user ON creator_User_ID = ID_User WHERE name',
        ValType::STRING, $lang);

?>

<a href="/article/?lang=<?= $lang ?>">Article</a>
<a href="#">Forum</a>
<hr>

<?php

    // Display link to create new posts
    if ($loggedIn && $_SESSION['trustScore'] >= $min_make_posts) {
        echo '
            <a href="/newpost/?lang=' . $lang . '&type=text">Make New Text Post</a>
            <a href="/newpost/?lang=' . $lang . '&type=img">Make New Image Post</a>
            <hr>
        ';
    }

    // Loop through every post
    while ($row = $result->fetch_assoc()) {
        $post_idnum = $row['ID_Post'];
        $post_title = $row['contentTitle'];
        $post_ctext = $row['contentText'];
        $post_image = $row['image'];
        $post_score = $row['score'];
        $post_ctime = $row['timeCreated'];
        $post_authu = $row['username'];

        include 'post.php';
    }

?>
