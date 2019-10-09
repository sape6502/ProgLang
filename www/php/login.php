<?php

    // Connect to database
    include 'db_connect.php';
    $dbconn = new DBConn();

    session_start();
    include 'initmsgs.php';

    if ($dbconn->conn_err()) {
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

            $verified = $dbconn->verify_user($username, $password);
            $result = $dbconn->get_where('user', 'username', ValType::STRING, $username);

            if ($verified) {
                $_SESSION['username'] = $username;
                $_SESSION['description'] = $result['description'];
                $_SESSION['trustScore'] = $result['trustScore'];
                $_SESSION['joinDate'] = $result['joinDate'];
                $_SESSION['picture'] = $result['picture'];

                header('Location: ' . $redirect, true, 301);
                exit;
            } else {
                $_SESSION['incorrect'] = true;
            }

        }

    }

    header('Location: /login', true, 301);
    exit;
