<?php

    session_start();

    if (!isset($_SESSION['username'])) {
        header('Location: ../user?user=' . $username, true, 301);
        exit;
    }

    include 'initmsgs.php';
    $username = $_SESSION['username'];

    if (!isset($_POST['password']) ||
        (strcmp($_POST['password'], '') == 0)) {

        $_SESSION['err_fields_dl'] = true;
        header('Location: ../user?user=' . $username, true, 301);
        exit;
    }

    $password = $_POST['password'];

    include 'db_connect.php';

    if ($conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: ../user?user=' . $username, true, 301);
        exit;
    }

    // Get old user password
    $stmt = $conn->prepare('SELECT passwordHash, picture FROM user WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $passHash = $result['passwordHash'];
	$picture = $result['picture'];

    if (!password_verify($password, $passHash)) {
        $_SESSION['err_passwrong_dl'] = true;
        header('Location: ../user?user=' . $username, true, 301);
        exit;
    }

	// Delete profile picture
	if (strcmp($picture, '../assets/img/profilepic/placeholder.png') != 0 && file_exists($picture)) {
        unlink($picture);
    }

    // Delete user from database
    $stmt = $conn->prepare('DELETE FROM user WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->close();

    session_unset();
    session_destroy();
    header('Location: ../main', true, 301);
    exit;
