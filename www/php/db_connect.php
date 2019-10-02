<?php

    //Database variables
    $db_name = "proglang_db";
    $db_hostname = "localhost";
    $db_username = "proglang_user";
    $db_password = "This is a very long password, that cannot be unhashed easily.";

    error_reporting(E_ALL ^ E_WARNING);
    $conn = new mysqli($db_hostname, $db_username, $db_password, $db_name);
    $conn_err = $conn->connect_error;
    error_reporting(E_ALL);
