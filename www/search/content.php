<?php
    if (isset($_GET['q'])) {
        $query = '%' . strtolower($_GET['q']) . '%';

        include '../php/db_connect.php';

        if ($conn_err) {
            echo '<h2 class="red">Failed to connect to database. Please try again later</h2>';
        }

        $stmt = $conn->prepare('SELECT * FROM article WHERE LOWER(name) LIKE ?');
        $stmt->bind_param('s', $query);
        $stmt->execute();
        $articleResults = $stmt->get_result();
        $stmt->close();

        $stmt = $conn->prepare('SELECT * FROM post WHERE LOWER(contentTitle) LIKE ?');
        $stmt->bind_param('s', $query);
        $stmt->execute();
        $postResults = $stmt->get_result();
        $stmt->close();

        $stmt = $conn->prepare('SELECT * FROM user WHERE LOWER(username) LIKE ?');
        $stmt->bind_param('s', $query);
        $stmt->execute();
        $userResults = $stmt->get_result();
        $stmt->close();

        $results = $articleResults->num_rows + $postResults->num_rows + $userResults->num_rows;

    }
?>

<form id="searchForm" action="../search/index.php" method="get">
    <input type="text" name="q" placeholder="Search...">
    <input type="submit" name="submit" value="🔍">
</form>
<hr>
    <?= $results ?> Results:
</hr>

<h2>Articles</h2>
<?php

    if (isset($articleResults)) {
        while ($row = $articleResults->fetch_assoc()) {
            $name = $row['name'];
            $f = fopen('../article/langs/' . $name . '/' . $name . '.ad', 'r');
            $thumbtext = fread($f, 100) . '...';
            fclose($f);

            echo '<a href="../article/?lang=' . $name . '"><h4>' . $name . '</h4></a>';
            echo '<i>' . $thumbtext . '</i>';
        }
    }

?>

<h2>Posts</h2>
<?php

    if (isset($postResults)) {
        while ($row = $postResults->fetch_assoc()) {
            $name = $row['contentTitle'];
            $thumbtext = substr($row['contentText'], 0, 100) . '...';

            echo '<a href="../post/?id=' . $row['ID_Post'] . '"><h4>' . $name . '</h4></a>';
            echo '<i>' . $thumbtext . '</i>';
            echo '<hr>';
        }
    }

?>

<h2>Users</h2>
<?php

    if (isset($userResults)) {
        while ($row = $userResults->fetch_assoc()) {
            $name = $row['username'];
            $thumbtext = substr($row['description'], 0, 100) . '...';

            echo '<a href="../user/?user=' . $name . '"><h4>' . $name . '</h4></a>';
            echo '<i>' . $thumbtext . '</i>';
        }
    }

    // End page for people not logged in and with too low of a trust score
    include '../php/trustconfig.php';
    if (!isset($_SESSION['username']) || $_SESSION['trustScore'] < $min_make_articles) {
        exit;
    }

?>

<hr>
<h5>Can't find your language?</h5>
<h6>Why not <a href="../create">create a new article?</a></h6>
