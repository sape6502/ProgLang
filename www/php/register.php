<?php

    // Connect to database
    include 'db_connect.php';

    session_start();
    $_SESSION['connError'] = false;
    $_SESSION['fieldsSet'] = false;
    $_SESSION['nametaken'] = false;
    $_SESSION['passmatch'] = strcmp($_POST['password_1'], $_POST['password_2']) == 0;

    if ($conn_err) {
        $_SESSION['connError'] = true;
    } else {

        // Check form data is filled in
        if (isset($_POST['username'], $_POST['password_1'], $_POST['password_2']) &&
            (strcmp($_POST['username'], '') != 0) &&
            (strcmp($_POST['password_1'], '') != 0) &&
            (strcmp($_POST['password_2'], '') != 0)) {

            $_SESSION['fieldsSet'] = true;

            $username = $_POST['username'];
            $password = $_POST['password_1'];
            $password_con = $_POST['password_2'];

            // Get username
            $stmt = $conn->prepare('SELECT username FROM user WHERE username = ?');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $_SESSION['nametaken'] = $result->num_rows;

            if (!$_SESSION['nametaken'] && $_SESSION['passmatch']) {
                $password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare('INSERT INTO user (username, passwordHash) VALUES (?, ?)');
                $stmt->bind_param('ss', $username, $password);
                $stmt->execute();
                $stmt->close();

                $_SESSION['username'] = $username;
                //TODO: Add proper validation with login ids etc.

                $conn->close();
                header('Location: ../user?user=' . $username, true, 301);
                exit;
            }

        }

    }

    $conn->close();
    header('Location: ../register', true, 301);
    exit;
