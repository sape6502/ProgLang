<?php

    // Connect to database
    include 'db_connect.php';

    session_start();
    include 'initmsgs.php';

    if ($conn_err) {
        $_SESSION['connError'] = true;
    } else {

        // Check form data is filled in
        if (isset($_POST['username'], $_POST['password']) &&
            (strcmp($_POST['username'], '') != 0) &&
            (strcmp($_POST['password'], '') != 0)) {

            $_SESSION['fieldsSet'] = true;

            $username = $_POST['username'];
            $password = $_POST['password'];

            // Set target redirect if set
            $redirect = '/user/?user=' . $username;
            if (isset($_SESSION['loginRedirect'])) {
                $redirect = $_SESSION['loginRedirect'];
            }

            // Get user data
            $stmt = $conn->prepare('SELECT * FROM user WHERE username = ?');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $passHash = $result['passwordHash'];

            // Verify user's identity
            $verified = password_verify($password, $passHash);
            unset($password, $passHash);

            if ($verified) {
                $conn->close();
                $_SESSION['username'] = $username;
                $_SESSION['description'] = $result['description'];
                $_SESSION['trustScore'] = $result['trustScore'];
                $_SESSION['joinDate'] = $result['joinDate'];
                $_SESSION['picture'] = $result['picture'];
                //TODO: Add proper validation with login ids etc.

                header('Location: ' . $redirect, true, 301);
                exit;
            } else {
                $_SESSION['incorrect'] = true;
            }

        }

    }

    $conn->close();
    header('Location: /login', true, 301);
    exit;
