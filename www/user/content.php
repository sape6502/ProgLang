<?php

    include '../php/db_connect.php';

    if ($conn_err) {
        echo '<h3 class="red">Failed to reach database.<br>Please try again later.</h3>';
        exit;
    }

    // Log user out if logout option is set
    if (isset($_GET['log']) && strcmp($_GET['log'], 'out') == 0) {
        session_unset();
        session_destroy();
    }

    // Get user info from database
    $stmt = $conn->prepare('SELECT * FROM user WHERE username = ?');
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // If the user doesn't exist
    if ($result == NULL) {
        echo '<h3 class="orange">This user doesn\'t exist</h3>';
        exit;
    }

    // User information:
    $description = $result['description'];
    $trustScore = $result['trustScore'];
    $joinDate = $result['joinDate'];
    $picture = $result['picture'];
    //TODO: Add more stats like total posts, articles etc.

    $isMyPage = isset($_SESSION['username']) && strcmp($_SESSION['username'], $user) == 0;

    if ($isMyPage) {
        $_SESSION['description'] = $description;
        $_SESSION['trustScore'] = $trustScore;
        $_SESSION['joinDate'] = $joinDate;
        $_SESSION['picture'] = $picture;
    }

    // Success Messages
    if (isset($_SESSION['succ_passchange']) && $_SESSION['succ_passchange']) {
        echo '<h5 class="green">Password successfully changed.</h5>';
        unset($_SESSION['succ_passchange']);
    } else {
        unset($_SESSION['succ_passchange']);
    }

    if (isset($_SESSION['succ_descchange']) && $_SESSION['succ_descchange']) {
        echo '<h5 class="green">Description successfully changed.</h5>';
        unset($_SESSION['succ_descchange']);
    } else {
        unset($_SESSION['succ_descchange']);
    }

?>

<div id='userPage'>
    <img src="../assets/img/<?= $picture ?>">
    <table>
        <tr>
            <td><strong>Username:</strong></td>
            <td><?= $user ?></td>
        </tr>
        <tr>
            <td><strong>Trust Score:</strong></td>
            <td><?= $trustScore ?>/10</td>
        </tr>
        <tr>
            <td><strong>Joined on:</strong></td>
            <td><?= $joinDate ?></td>
        </tr>
    </table>

    <h2>Description:</h2>
    <?php
        if ($isMyPage) {
            if (isset($_SESSION['err_dbconn']) && $_SESSION['err_dbconn'])
                echo '<h5 class="red">Failed to connect to database.
                Please try again later</h5>';

            include 'desc_form.php';
            echo '<h2 id="opts">Options:</h2><h4>Change password</h4>';
            include 'pass_form.html';

            if (isset($_SESSION['err_passmatch']) && $_SESSION['err_passmatch'])
                echo '<h5 class="red">The passwords must match</h5>';

            if (isset($_SESSION['err_passwrong_ch']) && $_SESSION['err_passwrong_ch'])
                echo '<h5 class="red">Incorrect password</h5>';

            if (isset($_SESSION['err_fields_ch']) && $_SESSION['err_fields_ch'])
                echo '<h5 class="red">All fields are required.</h5>';

            echo '<h4>Delete Account</h4>';
            include 'dele_form.html';

            if (isset($_SESSION['err_passwrong_dl']) && $_SESSION['err_passwrong_dl'])
                echo '<h5 class="red">Incorrect password</h5>';

            if (isset($_SESSION['err_fields_dl']) && $_SESSION['err_fields_dl'])
                echo '<h5 class="red">All fields are required.</h5>';
        } else {
            echo '<p>' . $description . '</p>';
        }
    ?>
</div>
