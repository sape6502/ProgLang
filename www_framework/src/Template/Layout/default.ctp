<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>ProgLang | <?= $this->fetch('title'); ?></title>
        <?= $this->Html->css('main'); ?>

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
                    <img id="logo" src="img/logo_small.png" alt="ProgLang logo">
                    <hr>

                    <!-- Login Form -->
                    <?= $this->element('login'); ?>
                    <hr>

                    <!-- Search Bar -->
                    <h5>Search:</h5>
                    <form id="searchForm" action="search" method="get">
                        <input type="text" name="query" placeholder="Search...">
                        <input type="submit" name="submit" value="🔍">
                    </form>
                    <hr>

                    <h5>Links:</h5>
                    <ul>
                        <li><a class="buttonLink" href="/">Main Page</a></li>
                        <li><a class="buttonLink" href="random.php">Random Page</a></li>
                        <li><a class="buttonLink" href="guidelines">Community Guidelines</a></li>
                    </ul>
                </div>

                <!-- Right Side -->
                <div class="col-lg-9" id="content">

                    <!-- Page Title -->
                    <div class="row"><div class="gap"></div></div>
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-11">
                            <h1 class="box"><?= $this->fetch('title'); ?></h1>
                        </div>
                    </div>
                    <div class="row"><div class="gap"></div></div>

                    <div class="row">
                        <div class="col-lg-1"></div>

                        <!-- Main Content -->
                        <div class="col-lg-11">
                            <div class="box">
                                <?= $this->fetch('content'); ?>
                            </div>
                        </div>

                    </div>
                    <div class="row"><div class="gap"></div></div>

                </div>

            </div>
        </div>

    </body>
</html>
