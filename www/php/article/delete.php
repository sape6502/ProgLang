<?php

    // Check the session is set
    session_start();

    $_SESSION['err_fieldsset'] = false;
    $_SESSION['err_password'] = false;
    $_SESSION['err_dbconn'] = false;

    if (!isset($_SESSION['proglang'])) {
        header('Location: /page/main');
        exit;
    }

    $proglang = $_SESSION['proglang'];

    // Check the user is logged in
    if (!isset($_SESSION['username'], $_POST['password']) ||
        strcmp($_POST['password'], '') == 0) {

        $_SESSION['err_fieldsset'] = true;
        header('Location: /page/article/?lang=' . $proglang);
        exit;
    }

    $username = $_SESSION['username'];
    $password = $_POST['password'];

    // Check the user's Password
    include '../../php/config/db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: /page/article/?lang=' . $proglang);
        exit;
    }

    $uName = $dbconn->get_cell('SELECT username FROM user JOIN article ON author_User_ID = ID_User WHERE name = ?', ValType::STRING, $proglang);

    // Verify user's credentials
    if (!$dbconn->verify_user($username, $password) || strcmp($username, $uName) != 0) {
        $_SESSION['err_password'] = true;
        header('Location: /page/article/?lang=' . $proglang);
        exit;
    }

    //TODO: Add a secondary 'are you sure' warning
    // Delete article
    unlink('../../page/article/langs/' . $proglang . '/' . $proglang . '.ad');
    unlink('../../page/article/langs/' . $proglang . '/' . $proglang . '.html');
    unlink('../../page/article/langs/' . $proglang . '/' . $proglang . '.pdf');
    rmdir('../../page/article/langs/' . $proglang);

    $dbconn->delete_row('article', 'name', ValType::STRING, $proglang);

    header('Location: /page/main');
    exit;
