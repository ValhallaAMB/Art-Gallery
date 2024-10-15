<?php
session_start();
require_once("../DBInitialisation/config.php");


if ($link === false) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['submit'])) {
  $email = trim($_POST['email']);
  $sql = "SELECT * FROM employees WHERE email = '$email'";
  $result = mysqli_query($link, $sql);

  // Check if there is a result
  if (mysqli_num_rows($result) > 0) {
    $code = rand(1111, 9999);
    $query = "UPDATE employees SET code = '$code' WHERE email = '$email'";

    $mail = new PHPMailer(true);
    try {
      //Server settings
      $mail->SMTPDebug = 2;
      $mail->isSMTP();
      $mail->Host       = 'smtp-mail.outlook.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'artgallery23@outlook.com';
      $mail->Password   = 'artgalleryPass';
      $mail->SMTPSecure = 'tls';
      $mail->Port       = 587;


      $mail->setFrom('artgallery23@outlook.com', 'Art Gallery');
      $mail->addAddress($email);


      $mail->isHTML(true);
      $mail->Subject = 'Password Reset';
      $mail->Body    = 'Your OTP is : ' . $code;

      if ($mail->send()) {
        if ($stmt = mysqli_prepare($link, $query)) {
          if (mysqli_stmt_execute($stmt)) {

            // Redirect to the code verification page
            header("Location: verificationCode.php");
            exit();
          } else {
            echo "ERROR: Could not execute query: $query. " . mysqli_error($link);
          }
        } else {
          echo "ERROR: Could not prepare query: $query. " . mysqli_error($link);
        }
      }
    } catch (Exception $e) {
      echo 'Error sending email: ', $mail->ErrorInfo;
    }
  } else {
    $_SESSION['emailError'] = "Incorrect email.";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../style/style.css">
  <style>
    body {
      background: url(https://images.pexels.com/photos/4252523/pexels-photo-4252523.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2);
    }

    a {
      color: #829e90;
    }
  </style>
</head>

<body>
  <!-- display alert -->
  <?php
  if (isset($_SESSION['emailError'])) {
  ?>
    <div class="alert alert-danger rounded-bottom-0 text-center" role="alert">
      <div>
        <i class="bi bi-exclamation-circle me-1"></i><?php echo $_SESSION['emailError']; ?>
      </div>
    </div>
  <?php
    unset($_SESSION['emailError']);
  }
  ?>

  <main class="container d-flex align-items-center justify-content-center min-vh-100">

    <div class="row col-10 col-md-7 col-lg-5 rounded-2 p-2">
      <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <div class="text-center mt-3">
          <h2>Forgot Password</h2>
          <p>Enter your email address</p>
        </div>

        <div class="mt-2">
          <input type="email" id="email" name="email" placeholder="Enter your email address" class="form-control" required />
          <div class="invalid-feedback">Please enter your email.</div>
        </div>

        <hr class="mx-5" />

        <div class="d-grid col-4 mx-auto my-3">
          <input class="btn btn-color" type="submit" name="submit" id="submit" value="Submit" />
        </div>

        <div class="mt-3 text-center">
          <p>Already have an account? <a href="login.php">Click here</a></p>
        </div>
      </form>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>