<?php

    include '../../php/config/db_connect.php';
    $dbconn = new DBConn();

    if ($dbconn->conn_err) {
        echo '<h3 class="red">Failed to reach database.<br>Please try again later.</h3>';
        exit;
    }

    // Get user info from database
    $result = $dbconn->get_where('user', 'username', ValType::STRING, $user);

    // If the user doesn't exist
    if ($result == NULL) {
        header('Location: /page/404', true, 301);
        exit;
    }

    // User information:
    $description = $result['description'];
    $trustScore = $result['trustScore'];
    $joinDate = $result['joinDate'];
    $picture = $result['picture'];
    //TODO: Add more stats like total posts, articles etc.

    $isMyPage = isset($_SESSION['username']) && strcmp($_SESSION['username'], $user) == 0;

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
    <div id="large_pic">
        <img src="<?= $picture ?>">
    </div>
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
            // Change Description
            if (isset($_SESSION['err_dbconn']) && $_SESSION['err_dbconn'])
                echo '<h5 class="red">Failed to connect to database.
                Please try again later</h5>';

            include 'desc_form.php';

            // Change Password
            echo '<h2 id="opts">Options:</h2><h4>Change password</h4>';
            include 'pass_form.html';

            if (isset($_SESSION['err_passmatch']) && $_SESSION['err_passmatch'])
                echo '<h5 class="red">The passwords must match</h5>';

            if (isset($_SESSION['err_passwrong_ch']) && $_SESSION['err_passwrong_ch'])
                echo '<h5 class="red">Incorrect password</h5>';

            if (isset($_SESSION['err_fields_ch']) && $_SESSION['err_fields_ch'])
                echo '<h5 class="red">All fields are required.</h5>';

            // Change Profile Picture
            echo '<h4>Change profile picture</h4>';
            include 'imgs_form.html';

            if (isset($_SESSION['err_passwrong_im']) && $_SESSION['err_passwrong_im'])
                echo '<h5 class="red">Incorrect password</h5>';

            if (isset($_SESSION['err_fields_im']) && $_SESSION['err_fields_im'])
                echo '<h5 class="red">All fields are required.</h5>';

            if (isset($_SESSION['err_ftoobig']) && $_SESSION['err_ftoobig'])
                echo '<h5 class="red">That image is too large.</h5>';

            if (isset($_SESSION['err_fupfail']) && $_SESSION['err_fupfail'])
                echo '<h5 class="red">Failed to upload file. Please try again later.</h5>';

            if (isset($_SESSION['err_fdims']) && $_SESSION['err_fdims'])
                echo '<h5 class="red">Profile picture must be square.</h5>';

            // Delete Account
            echo '<h4>Delete Account</h4>';
            include 'dele_form.html';

            if (isset($_SESSION['err_passwrong_dl']) && $_SESSION['err_passwrong_dl'])
                echo '<h5 class="red">Incorrect password</h5>';

            if (isset($_SESSION['err_fields_dl']) && $_SESSION['err_fields_dl'])
                echo '<h5 class="red">All fields are required.</h5>';

        } else {
            echo '<p>' . $description . '</p>';
        }

        include '../../php/config/init_msgs.php';
    ?>
</div>
