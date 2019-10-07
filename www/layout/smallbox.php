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

        <!-- Gap -->
        <div class="row"><div class="gap"></div></div>

        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">

                <div class="box">
                    <h2><?= $page_title ?></h2>
                    <?php include $page_content ?>
                    <hr>
                    <a href="/">Main Page</a>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>

    </body>
</html>
