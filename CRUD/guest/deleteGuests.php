<?php
session_start();
//DELETE ARTIST FILE

//FILE EXECUTED AFTER DELETION IS INVOKED
if (isset($_POST["id"]) && !empty($_POST["id"])) {
  require_once("../../DBInitialisation/config.php");

  $query = "DELETE FROM guests where id = ?";

  if ($stmt = mysqli_prepare($link, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $id);

    $id = trim($_POST["id"]);

    if (mysqli_stmt_execute($stmt)) {
      //SUCCESSFUL DELETION
      $_SESSION['deleteGuestNotification'] = "Guest deleted successfully!";

      //REDIRECTS BACK TO LANDING/HOME PAGE
      header("location: guestTable.php");
      exit();
    } else {
      echo "Guest deletion was unsuccessful. Please try again";
    }
  }

  mysqli_stmt_close($stmt);
  mysqli_close($link);
} else {
  if (empty(trim($_GET["id"]))) {
    //ID IS NOT IN URL, REDIRECTING BACK TO LANDING PAGE
    echo "ERROR : Redirecting to home page";
    header("location : ../../authentication/landingPage.php");
    exit();
  }
}
