<?php

    // Check the session is set
    session_start();

    $_SESSION['err_fieldsset'] = false;
    $_SESSION['err_password'] = false;
    $_SESSION['err_dbconn'] = false;

    if (!isset($_SESSION['proglang'])) {
        header('Location: /main');
        exit;
    }

    $proglang = $_SESSION['proglang'];

    // Check the user is logged in
    if (!isset($_SESSION['username'], $_POST['password']) ||
        strcmp($_POST['password'], '') == 0) {

        $_SESSION['err_fieldsset'] = true;
        header('Location: /article/?lang=' . $proglang);
        exit;
    }

    $username = $_SESSION['username'];
    $password = $_POST['password'];

    // Check the user's Password
    include '/php/db_connect.php';

    if ($conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: /article/?lang=' . $proglang);
        exit;
    }

    $stmt = $conn->prepare('SELECT passwordHash, username FROM user JOIN article ON author_User_ID = ID_User WHERE name = ?');
    $stmt->bind_param('s', $proglang);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $pHash = $result['passwordHash'];
    $uName = $result['username'];
    $stmt->close();

    // Verify user's credentials
    if (!password_verify($password, $pHash) || strcmp($username, $uName) != 0) {
        $_SESSION['err_password'] = true;
        header('Location: /article/?lang=' . $proglang);
        exit;
    }

    //TODO: Add a secondary 'are you sure' warning
    // Delete article
    unlink('/article/langs/' . $proglang . '/' . $proglang . '.ad');
    unlink('/article/langs/' . $proglang . '/' . $proglang . '.html');
    unlink('/article/langs/' . $proglang . '/' . $proglang . '.pdf');
    rmdir('/article/langs/' . $proglang);

    $stmt = $conn->prepare('DELETE FROM article WHERE name = ?');
    $stmt->bind_param('s', $proglang);
    $stmt->execute();
    $stmt->close();

    header('Location: /main');
    exit;
