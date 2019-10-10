<?php

    session_start();

    if (!isset($_SESSION['username'])) {
        header('Location: /user?user=' . $username, true, 301);
        exit;
    }

    include 'initmsgs.php';
    $username = $_SESSION['username'];

    if (!isset($_POST['password']) ||
        (strcmp($_POST['password'], '') == 0)) {

        $_SESSION['err_fields_dl'] = true;
        header('Location: /user?user=' . $username, true, 301);
        exit;
    }

    $password = $_POST['password'];

    include 'db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: /user?user=' . $username, true, 301);
        exit;
    }

    // Get old user password
    if (!$dbconn->verify_user($username, $password)) {
        $_SESSION['err_passwrong_dl'] = true;
        header('Location: /user?user=' . $username, true, 301);
        exit;
    }

	// Delete profile picture
    $picture = $dbconn->get_cell('SELECT picture FROM user WHERE username = ?', ValType::STRING, $username);
	if (strcmp($picture, '/assets/img/profilepic/placeholder.png') != 0 && file_exists($picture)) {
        unlink($picture);
    }

    // Delete user from database
    $dbconn->delete_row('user', 'username', ValType::STRING, $username);

    session_unset();
    session_destroy();
    header('Location: /main', true, 301);
    exit;
