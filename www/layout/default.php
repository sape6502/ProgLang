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
                <div class="col-lg-3" id="sideBar">
                    <img id="logo" src="/assets/img/logo_large.png" alt="ProgLang logo">
                    <hr>

                    <!-- Login Form -->
                    <?php
                        if (isset($_SESSION['username'])) {
                            include '../../page/login/small_info.php';
                        } else {
                            include '../../page/login/small_form.html';
                        }
                    ?>
                    <hr>

                    <!-- Search Bar -->
                    <form id="searchForm" action="/page/search/index.php" method="get">
                        <input type="text" name="q" placeholder="Search...">
                        <input type="submit" name="submit" value="🔍">
                    </form>
                    <hr>

                    <ul>
                        <li><a href="/page/main">Main Page</a></li>
                        <li><a href="/page/random">Random Page</a></li>
                        <li><a href="/page/guidelines">Community Guidelines</a></li>
                    </ul>

                </div>

                <!-- Right Side -->
                <div class="col-lg-9 col-lg-offset-3" id="outerContent">

                    <!-- Page Title -->
                    <div class="row"><div class="gap"></div></div>
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-11">
                            <h1 class="box"><?= $page_title ?></h1>
                        </div>
                    </div>
                    <div class="row"><div class="gap"></div></div>

                    <div class="row">
                        <div class="col-lg-1"></div>

                        <!-- Main Content -->
                        <div class="col-lg-11">
                            <div class="box">
                                <?php include $page_content ?>
                            </div>
                        </div>

                    </div>
                    <div class="row"><div class="gap"></div></div>

                </div>

            </div>

        </div>

    </body>
</html>
