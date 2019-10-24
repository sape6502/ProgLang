<?php

    session_start();
    $_SESSION['err_dbconn'] = false;
    $_SESSION['err_fields'] = false;
    $_SESSION['err_passwd'] = false;

    // Check user is logged in
    if (!isset($_SESSION['username'])) {
        header('Location: /page/main', true, 301);
        exit;
    }

    // Check all fields were filled
    if (!isset($_POST['lang'], $_POST['article'], $_POST['password']) ||
        strcmp($_POST['lang'], '') == 0 ||
        strcmp($_POST['article'], '') == 0 ||
        strcmp($_POST['password'], '') == 0) {

        $_SESSION['err_fields'] = true;
        header('Location: /page/create', true, 301);
        exit;
    }

    $lang = $_POST['lang'];
    $article = $_POST['article'];

    // Connect to database
    include '../config/db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: /page/create', true, 301);
        exit;
    }

    // Verify credentials
    if (!$dbconn->verify_user($_SESSION['username'], $_POST['password'])) {
        $_SESSION['err_passwd'] = true;
        header('Location: /page/create', true, 301);
        exit;
    }

    // Create new article
    $UID = $dbconn->get_cell('SELECT ID_User FROM user WHERE username = ?', ValType::STRING, $_SESSION['username']);
    $dbconn->insert_row('article', 'name', ValType::STRING, $lang, 'author_User_ID', ValType::INT, $UID);

    // Save in AsciiDoc File
    mkdir('../../page/article/langs/' . $lang);
    $asciidocFile = '../../page/article/langs/' . $lang . '/' . $lang . '.ad';
    $adFile = fopen($asciidocFile, 'w');
    fwrite($adFile, $article);
    fclose($adFile);

    // Covert to html and pdf
    exec('asciidoctor -a stylesheet! -a last-update-label! ../../page/article/langs/' . $lang . '/' . $lang . '.ad ');
    exec('asciidoctor-pdf -a last-update-label! ../../page/article/langs/' . $lang . '/' . $lang . '.ad ');

    // Redirect to article page
    header('Location: /page/article/?lang=' . $lang, true, 301);
    exit;
