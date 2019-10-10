<?php

    // Check if the form was set correctly
    if (!isset($_POST['h'], $_GET['lang']) ||
        (strcmp($_POST['h'], 'Yes') != 0 &&
        strcmp($_POST['h'], 'No') != 0)) {

        header('Location: /main', true, 301);
        exit;
    }

    $isHelpful = strcmp($_POST['h'], 'Yes') == 0;
    $lang = $_GET['lang'];

    // Connect to database
    include 'db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        header('Location: /main', true, 301);
        exit;
    }

    // Get previous count
    $helpfulness = $dbconn->get_cell('SELECT helpfulness FROM article WHERE name = ?', ValType::STRING, $lang);

    if ($isHelpful) {
        $helpfulness++;
    } else {
        $helpfulness--;
    }

    // Insert new helpfulness into database
    $dbconn->update_cell('article', 'helpfulness', ValType::INT, $helpfulness, 'name', ValType::STRING, $lang);

    // Redirect back to article page
    header('Location: /article/?lang=' . $lang, true, 301);
    exit;
