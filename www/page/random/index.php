<?php

    // Connect to the database
    include '../../php/config/db_connect.php';
    $dbconn = new DBConn();

    // Check connection error
    if ($dbconn->conn_err) {
        header('Location: /page/main', true, 301);
    }

    // Select a random article from the database
    $articlename = $dbconn->get_cell('SELECT name FROM article ORDER BY RAND() LIMIT 1', ValType::INT, 0);

    // Redirect the user to the random article
    header('Location: /page/article/?lang=' . $articlename, true, 301);
