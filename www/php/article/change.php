<?php

    // Check the session is set
    session_start();

    $_SESSION['err_fieldsset'] = false;
    $_SESSION['err_password'] = false;
    $_SESSION['err_dbconn'] = false;

    if (!isset($_SESSION['proglang'])) {
        header('Location: /page/main', true, 301);
        exit;
    }

    // Check the user is logged in
    if (!isset($_SESSION['username'], $_POST['password'], $_POST['article']) ||
        strcmp($_POST['password'], '') == 0) {

        $_SESSION['err_fieldsset'] = true;
        header('Location: /page/edit', true, 301);
        exit;
    }

    $username = $_SESSION['username'];
    $password = $_POST['password'];
    $proglang = $_SESSION['proglang'];
    $article = $_POST['article'];

    // Check the user's Password
    include '../../php/config/db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: /page/edit', true, 301);
        exit;
    }

    $verified = $dbconn->verify_user($username, $password);
    $uName = $dbconn->get_cell('SELECT username FROM user JOIN article ON author_User_ID = ID_User WHERE name = ?', ValType::STRING, $proglang);

    // Verify user's credentials
    if (!$verified || strcmp($username, $uName) != 0) {
        $_SESSION['err_password'] = true;
        header('Location: /page/edit', true, 301);
        exit;
    }

    // Save changed text to AsciiDoc file
    $proglang = $_SESSION['proglang'];
    $asciidocFile = '../../page/article/langs/' . $proglang . '/' . $proglang . '.ad';
    $adFile = fopen($asciidocFile, 'w');
    fwrite($adFile, $article);
    fclose($adFile);

    // Covert to html and pdf
    exec('asciidoctor -a stylesheet! -a last-update-label! ../../page/article/langs/' . $proglang . '/' . $proglang . '.ad ');
    exec('asciidoctor-pdf -a last-update-label! ../../page/article/langs/' . $proglang . '/' . $proglang . '.ad ');

    // Return to article page
    header('Location: /page/article/?lang=' . $proglang, true, 301);
    exit;
