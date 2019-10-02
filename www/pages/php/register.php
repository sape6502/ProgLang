<?php
  include '../config/db.php';

  $submitted = isset($_POST['submit']);
  $allFieldsSet = isset($_POST['username'], $_POST['password1'], $_POST['password2']);
  $passwordsMatch = true;

  $unameTaken = false;

  if ($allFieldsSet && $submitted) {
    $passwordsMatch = strcmp($_POST['password1'], $_POST['password2']) == 0;

    $username = $_POST['username'];
    $password = $_POST['password1'];

    $conn = new mysqli($db_hostname, $db_username, $db_password, $db_name);

    //Check connection
    if ($conn->connect_error) {
      die("Connection to database failed.");
    }

    //Check how many users have this username already
    if ($stmt = $conn->prepare("SELECT COUNT(*) AS count FROM user WHERE username = ?")) {
      $stmt->bind_param("s", $username);
      $stmt->execute();

      $count = $stmt->get_result()->fetch_assoc()['count'];

      $unameTaken = $count > 0;
    }

    //If the username isn't taken, insert new user into database
    if ($stmt = $conn->prepare("INSERT INTO user (username, passwordHash) VALUES (?, ?)")) {
      $stmt->bind_param("ss", $username, password_hash($password, PASSWORD_DEFAULT));
      $stmt->execute();

      session_start();
      $_SESSION['username'] = $username;
      header("Location: user.php?uname=$username");
    }

    $conn->close();
  }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Prog Lang | Register</title>
    <link rel="stylesheet" type="text/css" href="../../stylesheets/css/main.css">

    <!-- Bootstrap -->
    <link rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
      crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
      integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
      crossorigin="anonymous">
    </script>

  </head>
  <body>

    <!-- Gap -->
    <div class="row"><div class="gap"></div></div>

    <div class="row">
      <div class="col-md-4"></div>
      <div class="col-md-4">

        <div class="box">
          <h2>Register</h2>

          <form action="register.php" method="post">
            <input type="text" name="username" placeholder="Username"><br>
            <input type="password" name="password1" placeholder="Password"><br>
            <input type="password" name="password2" placeholder="Confirm Password"><br>
            <input type="submit" name="submit" value="Register">
          </form>

          <!-- Error Messages -->
          <?php
            if (!$allFieldsSet && $submitted) {
              echo "<i class='err'>All fields are required.</i>";
            } else if (!$passwordsMatch) {
              echo "<i class='err'>Those passwords don't match.</i>";
            } else if ($unameTaken) {
              echo "<i class='err'>That username is already in use.</i>";
            }
          ?>

          <br><a href="main.php">Back to main page</a>

        </div>

      </div>
      <div class="col-md-4"></div>
    </div>

  </body>
</html>
