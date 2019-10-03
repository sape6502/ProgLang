<?php

    // Connect to database
    include 'db_connect.php';

    session_start();
    include 'initmsgs.php';
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
            $stmt = $conn->prepare('SELECT * FROM user WHERE username = ?');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $_SESSION['nametaken'] = $result->num_rows;
            $_SESSION['picture'] = $result->fetch_assoc()['picture'];

            if (!$_SESSION['nametaken'] && $_SESSION['passmatch']) {
                $password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare('INSERT INTO user (username, passwordHash) VALUES (?, ?)');
                $stmt->bind_param('ss', $username, $password);
                $stmt->execute();
                $stmt->close();

                //Load user data into session
                $stmt = $conn->prepare('SELECT * FROM user WHERE username = ?');
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                $_SESSION['username'] = $username;
                $_SESSION['description'] = $result['description'];
                $_SESSION['trustScore'] = $result['trustScore'];
                $_SESSION['joinDate'] = $result['joinDate'];
                $_SESSION['picture'] = $result['picture'];
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
