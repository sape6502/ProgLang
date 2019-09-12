<?php
  include '../config/db.php';

  $submitted = isset($_POST['submit']);
  $allFieldsSet = isset($_POST['username'], $_POST['password'])
    && (strcmp($_POST['username'], "") != 0)
    && (strcmp($_POST['password'], "") != 0);
  $isValid = false;

  if ($allFieldsSet) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = new mysqli($db_hostname, $db_username, $db_password, $db_name);

    //Check connection
    if ($conn->connect_error) {
      die("Connection to database failed.");
    }

    //Prepared SQL statement
    if ($stmt = $conn->prepare("SELECT passwordHash FROM user WHERE username = ?")) {
      $stmt->bind_param("s", $username);
      $stmt->execute();

      $hash = $stmt->get_result()->fetch_assoc()['passwordHash'];

      $isValid = password_verify($password, $hash);

      if ($isValid) {
        session_start();
        $_SESSION['username'] = $username;
        header("Location: user.php?uname=$username");
      }
    }

    $conn->close();
  }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Prog Lang | Login</title>
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
          <h2>Login</h2>

          <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username"><br>
            <input type="password" name="password" placeholder="Password"><br>
            <input type="submit" name="submit" value="Login">
          </form>

          <!-- Error Messages -->
          <?php
            if (!$allFieldsSet && $submitted) {
              echo "<i class='err'>All fields are required.</i>";
            } else if (!$isValid && $submitted) {
              echo "<i class='err'>That username or password is incorrect.</i>";
            }
          ?>

          <br><a href="main.php">Back to main page</a>

        </div>

      </div>
      <div class="col-md-4"></div>
    </div>

  </body>
</html>
