<?php
// CREATE DATABASE FILE

$link = mysqli_connect("localhost", "root", "");

if ($link == false) {
    die("Could not connect to the server. ERROR: " . mysqli_connect_error());
}

// Create DB creation query
$query = "CREATE DATABASE IF NOT EXISTS Art_Gallery";

if (mysqli_query($link, $query)) {
    echo "Database creation was successful";
} else {
    echo ("Database creation was unsuccessful. ERROR: " . mysqli_error($link));
}

mysqli_close($link);
?>
