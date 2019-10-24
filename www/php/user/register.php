<?php

    // Connect to database
    include '../config/db_connect.php';
    $dbconn = new DBConn();

    session_start();
    include '../config/init_msgs.php';
    $_SESSION['passmatch'] = strcmp($_POST['password_1'], $_POST['password_2']) == 0;

    if ($dbconn->conn_err) {
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
            $result = $dbconn->get_full('SELECT picture FROM user WHERE username = ?', ValType::STRING, $username);

            $_SESSION['nametaken'] = $result->num_rows;
            $_SESSION['picture'] = $result->fetch_assoc()['picture'];

            if (!$_SESSION['nametaken'] && $_SESSION['passmatch']) {
                $password = password_hash($password, PASSWORD_DEFAULT);

                $dbconn->insert_row('user', 'username', ValType::STRING, $username, 'passwordHash', ValType::STRING, $password);

                //Load user data into session
                $result = $dbconn->get_where('user', 'username', ValType::STRING, $username);

                $_SESSION['username'] = $username;
                $_SESSION['description'] = $result['description'];
                $_SESSION['trustScore'] = $result['trustScore'];
                $_SESSION['joinDate'] = $result['joinDate'];
                $_SESSION['picture'] = $result['picture'];
                //TODO: Add proper validation with login ids etc.

                header('Location: /page/user?user=' . $username, true, 301);
                exit;
            }

        }

    }

    header('Location: /page/register', true, 301);
    exit;
