<form id="formPage" action="/php/login.php" method="post">
    <input type="text" name="username" placeholder="Username"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <input type="submit" name="submit" value="Login">

    <?php

        session_start();

        if (isset($_SESSION['connError'], $_SESSION['fieldsSet'], $_SESSION['incorrect'])) {
            $connError = $_SESSION['connError'];
            $fieldsSet = $_SESSION['fieldsSet'];
            $incorrect = $_SESSION['incorrect'];

            if ($connError)
            echo '<p class="red">Failed to connect to database. Please try again later.</p>';

            if (!$fieldsSet)
            echo '<p class="red">All fields are required.</p>';

            if ($incorrect)
            echo '<p class="red">That username or password is incorrect.</p>';
        }

        session_unset();
        session_destroy();
    ?>

    <a href="/register">Register an account</a>
</form>
