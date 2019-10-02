<form id="formPage" action="../php/register.php" method="post">
    <input type="text" name="username" placeholder="Username"><br>
    <input type="password" name="password_1" placeholder="Password"><br>
    <input type="password" name="password_2" placeholder="Confirm Password"><br>
    <input type="submit" name="submit" value="Register">

    <?php
        session_start();

        if (isset($_SESSION['connError'], $_SESSION['fieldsSet'],
            $_SESSION['nametaken'], $_SESSION['passmatch'])) {

            $connError = $_SESSION['connError'];
            $fieldsSet = $_SESSION['fieldsSet'];
            $nametaken = $_SESSION['nametaken'];
            $passmatch = $_SESSION['passmatch'];

            if ($connError)
            echo '<p class="red">Failed to connect to database. Please try again later.</p>';

            if (!$fieldsSet)
            echo '<p class="red">All fields are required.</p>';

            if ($nametaken)
            echo '<p class="red">That username has already been registered.</p>';

            if (!$passmatch)
            echo '<p class="red">Both passwords must match.</p>';
        }

        session_unset();
        session_destroy();
    ?>
</form>
