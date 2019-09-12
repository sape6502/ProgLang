<?php
  include '../config/db.php';

  $isLoggedIn = false;
  $isMyPage = false;
  $isValidPage = isset($_GET['uname']);

  //Get username if already logged in
  session_start();
  if (isset($_SESSION['username'])) {
    $isLoggedIn = true;
    $currUsername = $_SESSION['username'];
  }
  if (isset($_GET['log'])) {
    $isLoggedIn = false;
    session_destroy();
    header("Location: main.php");
  }
  session_abort();

  //Get username for this page
  if ($isValidPage) {

    $username = $_GET['uname'];
    $isMyPage = strcmp($username, $currUsername) == 0;

    //Get User details from Database
    $conn = new mysqli($db_hostname, $db_username, $db_password, $db_name);

    //Check connection
    if ($conn->connect_error) {
      die("Connection to database failed.");
    }

    //Prepared SQL statement
    if ($stmt = $conn->prepare("SELECT joinDate, trustScore, picture, description FROM user WHERE username = ?")) {
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $row = $stmt->get_result()->fetch_assoc();

      $joinDate = $row['joinDate'];
      $trustScore = $row['trustScore'];
      $picture = $row['picture'];
      $description = $row['description'];
    }

    $conn->close();

  } else {
    //Redirects users back to main page if no user is passed
    header("Location: main.html");
  }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Prog Lang | <?php echo $username; ?>'s Page</title>
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

    <div class="container" id=page>
      <div class="row">

        <!-- Sidebar -->
        <div class="col-md-3" id="sideBar">
          <img id="logo" src="../../assets/img/logo_large.png" alt="ProgLang logo">
          <hr>

          <!-- Login Form -->
          <?php
            if ($isLoggedIn) {
              echo '
              <img src="' . $picture . '" alt="User profile picture">
              <h4>logged in as: ' . $username . '</h4>
              <a href="user.php?uname=' . $username . '">My user page</a>
              <a href="user.php?uname=' . $username . '&log=out">Log out</a>
              <hr>';
            } else {
              echo '
              <form id="loginForm" action="login.php" method="post">
                <img src="../../assets/img/placeholder.png" alt="User placeholder image">
                <input type="text" name="username" placeholder="Username"><br>
                <input type="password" name="password" placeholder="Password"><br>
                <input type="submit" name="submit" value="Login">
              </form>
              <a href="register.php">Register</a>
              <hr>';
            }
          ?>

          <!-- Search Bar -->
          <form id="searchForm" action="search.php" method="post">
            <input type="text" name="query" placeholder="Search...">
            <input type="submit" name="submit" value="🔍">
          </form>
          <hr>

          <a class="buttonLink" href="main.php">Main Page</a>
          <a class="buttonLink" href="random.php">Random Page</a>
          <hr>

          <p>
            Contact:<br>
            <a href="mailto: samuel.pearce@crealogix.com">samuel.pearce@crealogix.com</a><br>
            +41 58 404 83 59
          </p>

        </div>

        <!-- Right Side -->
        <div class="col-md-9">

          <!-- Page Title -->
          <div class="row"><div class="gap"></div></div>
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-11">
              <h1 class="box"><?php echo $username; ?> - User Page</h1>
            </div>
          </div>
          <div class="row"><div class="gap"></div></div>

          <div class="row">
            <div class="col-md-1"></div>

            <!-- Main Content -->
            <div class="col-md-11">
              <div class="box" id="userPage">
                <img src="<?php echo $picture; ?>" alt="User profile picture">
                Username: <i><?php echo $username; ?></i><br>
                Join Date: <i><?php echo $joinDate; ?></i><br>
                Trust Score: <i><?php echo $trustScore; ?>/10</i><br>

                Description:
                <?php
                  if ($isMyPage) {
                    echo '
                    <textarea rows="5" cols="25 name="description" form="descForm">
                    ' . $description . '
                    </textarea>
                    <form action="desc.php" method="post" id="descForm">';
                  } else {
                    echo '
                    <p>
                    ' . $description . '
                    </p>';
                  }
                ?>
              </div>
            </div>

          </div>
          <div class="row"><div class="gap"></div></div>

        </div>

      </div>
    </div>

  </body>
</html>
