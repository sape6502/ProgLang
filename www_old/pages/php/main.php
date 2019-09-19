<?php
  $isLoggedIn = false;

  //Get username if already logged in
  session_start();
  if (isset($_SESSION['username'])) {
    $isLoggedIn = true;
    $currUsername = $_SESSION['username'];
  }
  session_abort();

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Prog Lang | Main Page</title>
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
        <div class="col-lg-3" id="sideBar">
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
        <div class="col-lg-9">

          <!-- Page Title -->
          <div class="row"><div class="gap"></div></div>
          <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-11">
              <h1 class="box">Prog Lang - Main Page</h1>
            </div>
          </div>
          <div class="row"><div class="gap"></div></div>

          <div class="row">
            <div class="col-lg-1"></div>

            <!-- Main Content -->
            <div class="col-lg-11">
              <div class="box">
                <h2>About ProgLang</h2>
                <p>
                  This website was made as a way for both experienced and inexperienced
                  programmers to learn, support one another and entertain each other.
                  Every programming language has a quick, wikipedia-like article explaining
                  a bit about the history of the language, with a short tutorial at the end.
                  Each language also has a little forum where posts can be made. These posts
                  are only limited to the language they are about. All posts are allowed
                  from asking for help with your code to showing off your personal projects.
                </p>

                <h2>Trust-Score</h2>
                <p>
                  Every user is given a rating from 1 to 10 on how trustworthy we find them.
                  This is based on many factors like how long they have been a member, how
                  popular their posts are and how helpful their articles/article edits are.
                  To begin with, every user's Trust-Score is 5. the easiest way to raise
                  your TS is by making a post on the forum. Depending on how popular your
                  post is, you could gain up to 4 TS points.
                </p>

                <h2>Featured Article: ?</h2>
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                  do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                  Ut enim ad minim veniam, quis nostrud exercitation ullamco
                  laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                  irure dolor in reprehenderit in voluptate velit esse cillum
                  dolore eu fugiat nulla pariatur. Excepteur sint occaecat
                  cupidatat non proident, sunt in culpa qui officia deserunt
                  mollit anim id est laborum.
                </p>
              </div>
            </div>

          </div>
          <div class="row"><div class="gap"></div></div>

        </div>

      </div>
    </div>

  </body>
</html>
