<?php

$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "video_db";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connect fail: " .mysqli_connect_error());
}