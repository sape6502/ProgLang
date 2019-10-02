<?php

    session_start();

    if (!isset($_SESSION['username'])) {
        header('Location: ../user?user=' . $username, true, 301);
        exit;
    }

    $_SESSION['err_dbconn'] = false;
    $_SESSION['succ_descchange'] = false;
    $username = $_SESSION['username'];

    if (isset($_POST['submit'], $_POST['description'])) {

        include 'db_connect.php';

        if ($conn_err) {
            $_SESSION['err_dbconn'] = true;
            header('Location: ../user?user=' . $username, true, 301);
            exit;
        }

        $stmt = $conn->prepare('UPDATE user SET description = ? WHERE username = ?');
        $stmt->bind_param('ss', $_POST['description'], $username);
        $stmt->execute();
        $stmt->close();

    }

    $_SESSION['succ_descchange'] = true;
    header('Location: ../user?user=' . $username, true, 301);
    exit;