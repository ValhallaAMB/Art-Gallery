<?php
//CONFIG FILE
require_once ("config.php");

//QUERY TO CREATE GUEST TABLE
$query = "CREATE TABLE guests(
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  firstName VARCHAR(80) NOT NULL,
  lastName VARCHAR(80) NOT NULL,
  email VARCHAR(100),
  phoneNumber VARCHAR(20) NOT NULL,
  dateOfAttendance DATE NOT NULL,
  paidOrInvited ENUM ('Paid', 'Invited') NOT NULL,
  plusOne VARCHAR(10)
  )";             
  
  if(mysqli_query($link, $query)){
  echo "Table was created successfully";
  }
  else {
    echo ("Table creation was unsuccessful. ERROR : " . mysqli_error($link));
  }
  mysqli_close($link);

?>