<?php
//CONFIG FILE
require_once ("config.php");

//QUERY TO CREATE ARTIST TABLE
$query = "CREATE TABLE artists( 
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  firstName VARCHAR(80) NOT NULL,
  lastName VARCHAR(80) NOT NULL,
  email VARCHAR(100) NOT NULL,
  dateOfShow DATE NOT NULL,
  artPieces INT NOT NULL,
  allArtStyles VARCHAR(200) NOT NULL
  )";             
  
  if(mysqli_query($link, $query)){
  echo "Table was created successfully";
  }
  else {
    echo ("Table was not created successfully. ERROR : " . mysqli_error($link));
  }
  mysqli_close($link);

?>