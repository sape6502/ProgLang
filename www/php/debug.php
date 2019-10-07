<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Prog Lang | DEBUG PAGE</title>
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

                    <h2>Debug Page</h2>
                    <strong class="red"> Don't forget to remove this before release </strong>
                    <hr>
                    <h3>$_POST</h3>
                    <?= var_dump($_POST) ?>
                    <hr>
                    <h3>$_GET</h3>
                    <?= var_dump($_GET) ?>
                    <hr>
                    <h3>$_SESSION</h3>
                    <?php
                        session_start();
                        var_dump($_SESSION);
                    ?>
                    <hr>

                </div>
            </div>
            <div class="col-md-4"></div>
        </div>

    </body>
</html>
