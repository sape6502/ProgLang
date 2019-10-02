<?php

    session_start();

    if (!isset($_SESSION['username'])) {
        header('Location: ../user?user=' . $username, true, 301);
        exit;
    }

    $_SESSION['err_dbconn'] = false;
    $_SESSION['err_fields_ch'] = false;
    $_SESSION['err_fields_dl'] = false;
    $_SESSION['err_passmatch'] = false;
    $_SESSION['err_passwrong_ch'] = false;
    $_SESSION['err_passwrong_dl'] = false;
    $_SESSION['succ_passchange'] = false;
    $username = $_SESSION['username'];

    if (!isset($_POST['oldPass'], $_POST['newPass1'], $_POST['newPass2']) ||
        (strcmp($_POST['oldPass'], '') == 0) ||
        (strcmp($_POST['newPass1'], '') == 0) ||
        (strcmp($_POST['newPass2'], '') == 0)) {
        $_SESSION['err_fields_ch'] = true;
        header('Location: ../user?user=' . $username, true, 301);
        exit;
    }

    $oldPass = $_POST['oldPass'];
    $newPass1 = $_POST['newPass1'];
    $newPass2 = $_POST['newPass2'];

    include 'db_connect.php';

    if ($conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: ../user?user=' . $username, true, 301);
        exit;
    }

    if (strcmp($newPass1, $newPass2)) {
        $_SESSION['err_passmatch'] = true;
        header('Location: ../user?user=' . $username, true, 301);
        exit;
    }

    // Get old user password
    $stmt = $conn->prepare('SELECT passwordHash FROM user WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $passHash = $result->fetch_assoc()['passwordHash'];

    if (!password_verify($oldPass, $passHash)) {
        $_SESSION['err_passwrong_ch'] = true;
        header('Location: ../user?user=' . $username, true, 301);
        exit;
    }

    // Update password with new one
    $newHash = password_hash($newPass1, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('UPDATE user SET passwordHash = ? WHERE username = ?');
    $stmt->bind_param('ss', $newHash, $username);
    $stmt->execute();
    $stmt->close();

    $_SESSION['succ_passchange'] = true;
    header('Location: ../user?user=' . $username, true, 301);
    exit;
