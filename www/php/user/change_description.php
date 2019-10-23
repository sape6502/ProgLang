<?php

    session_start();

    if (!isset($_SESSION['username'])) {
        header('Location: /page/user?user=' . $username, true, 301);
        exit;
    }

    include 'initmsgs.php';
    $username = $_SESSION['username'];

    if (isset($_POST['submit'], $_POST['description'])) {

        include '../config/db_connect.php';
        $dbconn = new DBConn();

        if ($dbconn->conn_err) {
            $_SESSION['err_dbconn'] = true;
            header('Location: /page/user?user=' . $username, true, 301);
            exit;
        }

        $dbconn->update_cell('user', 'description', ValType::STRING, $_POST['description'], 'username', ValType::STRING, $username);

    }

    $_SESSION['succ_descchange'] = true;
    $_SESSION['description'] = $_POST['description'];
    header('Location: /page/user?user=' . $username, true, 301);
    exit;
