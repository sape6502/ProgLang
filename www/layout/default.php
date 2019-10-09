<?php session_start() ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Prog Lang | <?= $page_title ?></title>
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
                    <img id="logo" src="/assets/img/logo_large.png" alt="ProgLang logo">
                    <hr>

                    <!-- Login Form -->
                    <?php
                        //TODO: Make login page return you to where you were
                        if (isset($_SESSION['username'])) {
                            include '/login/small_info.php';
                        } else {
                            include '/login/small_form.html';
                        }
                    ?>

                    <!-- Search Bar -->
                    <form id="searchForm" action="/search/index.php" method="get">
                        <input type="text" name="q" placeholder="Search...">
                        <input type="submit" name="submit" value="🔍">
                    </form>
                    <hr>

                    <ul>
                        <li><a href="/main">Main Page</a></li>
                        <li><a href="/php/random">Random Page</a></li>
                        <li><a href="/guidelines">Community Guidelines</a></li>
                    </ul>
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
                            <h1 class="box"><?= $page_title ?></h1>
                        </div>
                    </div>
                    <div class="row"><div class="gap"></div></div>

                    <div class="row">
                        <div class="col-md-1"></div>

                        <!-- Main Content -->
                        <div class="col-md-11">
                            <div class="box">
                                <?php include $page_content ?>
                            </div>
                        </div>

                    </div>
                    <div class="row"><div class="gap"></div></div>

                </div>

            </div>
        </div>

        <!-- TODO: Add footer -->

    </body>
</html>
