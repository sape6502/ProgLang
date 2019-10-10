<?php
    if (isset($_GET['q'])) {
        $query = '%' . strtolower($_GET['q']) . '%';

        include '../php/db_connect.php';
        $dbconn = new DBConn();

        if ($dbconn->conn_err) {
            echo '<h2 class="red">Failed to connect to database. Please try again later</h2>';
        }

        $articleResults = $dbconn->get_full('SELECT * FROM article WHERE LOWER(name) LIKE ?', ValType::STRING, $query);
        $postResults = $dbconn->get_full('SELECT * FROM post WHERE LOWER(contentTitle) LIKE ?', ValType::STRING, $query);
        $userResults = $dbconn->get_full('SELECT * FROM user WHERE LOWER(username) LIKE ?', ValType::STRING, $query);

        $results = $articleResults->num_rows + $postResults->num_rows + $userResults->num_rows;

    }

    $page_title = 'Search';
    $page_content = 'content.php';
    include '../layout/default.php';
    if (isset($query)) $_SESSION['loginRedirect'] = '/search/?q=' . $query;
?>
