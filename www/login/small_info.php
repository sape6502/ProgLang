<?php
    $username = $_SESSION['username'];
    $picture = $_SESSION['picture'];
?>

<div id="small_info">
    <img src="../assets/img/<?= $picture ?>" alt="User profile picture">
    <h4>logged in as:</h4><i><?= $username ?></i><br>
    <a href="../user?user=<?= $username ?>">My user page</a><br>
    <a href="../user?user=<?= $username ?>&log=out">Log out</a><br>
    <hr>
</div>