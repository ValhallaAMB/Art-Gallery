<?php
//CONFIG FILE
require_once ("config.php");

//QUERY TO CREATE EMPLOYEE TABLE
$query = "CREATE TABLE employees(
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  firstName VARCHAR(80) NOT NULL,
  lastName VARCHAR(80) NOT NULL,
  birthDay DATE NOT NULL,
  gender ENUM ('Male', 'Female') NOT NULL,
  country VARCHAR(50) NOT NULL,
  `address` VARCHAR(200) NOT NULL,
  position VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  storedsalt VARCHAR(100) NOT NULL,
  code VARCHAR(64)
  )";             
  
  if(mysqli_query($link, $query)){
  echo "Table was created successfully";
  }
  else {
    echo ("Table creation was unsuccessful. ERROR : " . mysqli_error($link));
  }
  mysqli_close($link);

?>