<?php

    session_start();
    $_SESSION['err_dbconn'] = false;
    $_SESSION['err_fields'] = false;
    $_SESSION['err_passwd'] = false;

    // Check user is logged in
    if (!isset($_SESSION['username'])) {
        header('Location: ../main', true, 301);
        exit;
    }

    // Check all fields were filled
    if (!isset($_POST['lang'], $_POST['article'], $_POST['password']) ||
        strcmp($_POST['lang'], '') == 0 ||
        strcmp($_POST['article'], '') == 0 ||
        strcmp($_POST['password'], '') == 0) {

        $_SESSION['err_fields'] = true;
        header('Location: ../create', true, 301);
        exit;
    }

    $lang = $_POST['lang'];
    $article = $_POST['article'];

    // Connect to database
    include 'db_connect.php';
    if ($conn_err) {
        $_SESSION['err_dbconn'] = true;
        header('Location: ../create', true, 301);
        exit;
    }

    // Verify credentials
    $stmt = $conn->prepare('SELECT ID_User, passwordHash FROM user WHERE username = ?');
    $stmt->bind_param('s', $_SESSION['username']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $pHash = $res['passwordHash'];
    $UID = $res['ID_User'];
    $stmt->close();

    if (!password_verify($_POST['password'], $pHash)) {
        $_SESSION['err_passwd'] = true;
        header('Location: ../create', true, 301);
        exit;
    }

    // Create new article
    $stmt = $conn->prepare('INSERT INTO article (name, author_User_ID) VALUES(?, ?)');
    $stmt->bind_param('si', $lang, $UID);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Save in AsciiDoc File
    mkdir('../article/langs/' . $lang);
    $asciidocFile = '../article/langs/' . $lang . '/' . $lang . '.ad';
    $adFile = fopen($asciidocFile, 'w');
    fwrite($adFile, $article);
    fclose($adFile);

    // Covert to html and pdf
    exec('asciidoctor -a stylesheet! -a last-update-label! ../article/langs/' . $lang . '/' . $lang . '.ad ');
    exec('asciidoctor-pdf -a last-update-label! ../article/langs/' . $lang . '/' . $lang . '.ad ');

    // Redirect to article page
    header('Location: ../article/?lang=' . $lang);
    exit;
