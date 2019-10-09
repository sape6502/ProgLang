<?php

    // Check if the form was set correctly
    if (!isset($_POST['h'], $_GET['lang']) ||
        (strcmp($_POST['h'], 'Yes') != 0 &&
        strcmp($_POST['h'], 'No') != 0)) {

        header('Location: /main', true, 301);
        exit;
    }

    $isHelpful = strcmp($_POST['h'], 'Yes') == 0;
    $lang = $_GET['lang'];

    // Connect to database
    include 'db_connect.php';

    if ($conn_err) {
        header('Location: /main', true, 301);
        exit;
    }

    // Get previous count
    $stmt = $conn->prepare('SELECT helpfulness FROM article WHERE name = ?');
    $stmt->bind_param('s', $lang);
    $stmt->execute();
    $helpfulness = $stmt->get_result()->fetch_assoc()['helpfulness'];
    $stmt->close();

    if ($isHelpful) {
        $helpfulness++;
    } else {
        $helpfulness--;
    }

    // Insert new helpfulness into database
    $stmt = $conn->prepare('UPDATE article SET helpfulness = ? WHERE name = ?');
    $stmt->bind_param('is', $helpfulness, $lang);
    $stmt->execute();

    // Redirect back to article page
    header('Location: /article/?lang=' . $lang, true, 301);
    exit;
