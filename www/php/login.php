<?php

    // Connect to database
    include 'db_connect.php';

    session_start();
    $_SESSION['connError'] = false;
    $_SESSION['fieldsSet'] = false;
    $_SESSION['incorrect'] = false;

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

            // Get user data
            $stmt = $conn->prepare('SELECT passwordHash FROM user WHERE username = ?');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $passHash = $result->fetch_assoc()['passwordHash'];

            // Verify user's identity
            $verified = password_verify($password, $passHash);
            unset($password, $passHash);

            if ($verified) {
                $conn->close();
                $_SESSION['username'] = $username;
                //TODO: Add proper validation with login ids etc.
                header('Location: ../user?user=' . $username, true, 301);
                exit;
            } else {
                $_SESSION['incorrect'] = true;
            }

        }

    }

    $conn->close();
    header('Location: ../login', true, 301);
    exit;
