<?php

    // Check the session is set
    session_start();

    $_SESSION['err_fieldsset'] = false;
    $_SESSION['err_password'] = false;
    $_SESSION['err_dbconn'] = false;

    if (!isset($_SESSION['proglang'])) {
        header('Location: ../main');
        exit;
    }

    // Check the user is logged in
    if (!isset($_SESSION['username'], $_POST['password'], $_POST['article']) ||
        strcmp($_POST['password'], '') == 0) {

        $_SESSION['err_fieldsset'] = true;
        header('Location: ../edit');
        exit;
    }

    $username = $_SESSION['username'];
    $password = $_POST['password'];
    $proglang = $_SESSION['proglang'];
    $article = $_POST['article'];

    // Check the user's Password
    include '../php/db_connect.php';

    if ($conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: ../edit');
        exit;
    }

    $stmt = $conn->prepare('SELECT passwordHash, username FROM user JOIN article ON author_User_ID = ID_User WHERE name = ?');
    $stmt->bind_param('s', $proglang);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $pHash = $result['passwordHash'];
    $uName = $result['username'];
    $stmt->close();

    // Verify user's credentials
    if (!password_verify($password, $pHash) || strcmp($username, $uName) != 0) {
        $_SESSION['err_password'] = true;
        header('Location: ../edit');
        exit;
    }

    // Save changed text to AsciiDoc file
    $proglang = $_SESSION['proglang'];
    $asciidocFile = '../article/langs/' . $proglang . '/' . $proglang . '.ad';
    $adFile = fopen($asciidocFile, 'w');
    fwrite($adFile, $article);
    fclose($adFile);

    // Covert to html and pdf
    exec('asciidoctor -a stylesheet! -a last-update-label! ../article/langs/' . $proglang . '/' . $proglang . '.ad ');
    exec('asciidoctor-pdf -a last-update-label! ../article/langs/' . $proglang . '/' . $proglang . '.ad ');

    // Return to article page
    header('Location: ../article/?lang=' . $proglang, true, 301);
    exit;
