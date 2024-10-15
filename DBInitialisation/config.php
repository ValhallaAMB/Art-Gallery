<?php
// CONFIG FILE USED FOR CONNECTION PURPOSES

if(!defined('SERVER')){
  define("SERVER", "localhost");
}
if(!defined('USERNAME')){
  define("USERNAME", "root");
}
if(!defined('PASSWORD')){
  define("PASSWORD", "");
}
if(!defined('DB_NAME')){
  define("DB_NAME", "Art_Gallery");
}
// define("SERVER", "localhost");
// define("USERNAME", "root");
// define("PASSWORD", "");
// define("DB_NAME", "Art_Gallery");

// CONNECTING TO DATABASE
$link = mysqli_connect(SERVER, USERNAME, PASSWORD, DB_NAME);

if($link == false){
  die("Could not connect to the database. ERROR: " . mysqli_connect_error());
}
?>
