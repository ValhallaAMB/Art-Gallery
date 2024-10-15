<?php
session_start();
require_once("../DBInitialisation/config.php");

if ($link === false) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['submit'])) {
  $code = trim($_POST['code']);
  $newPassword = trim($_POST['password']);
  $sql = "SELECT * FROM employees WHERE code = '$code'";
  $result = mysqli_query($link, $sql);

  // Check if there is a result
  if (mysqli_num_rows($result) > 0) {
    $query = "SELECT storedsalt FROM employees WHERE code = '$code'";
    $result = mysqli_query($link, $query);
    if ($result) {
      // Check if there is a result
      if (mysqli_num_rows($result) > 0) {
        // Fetch the row as an associative array
        $row = mysqli_fetch_assoc($result);

        // Retrieve the 'storedsalt' value and store it in $salt
        $salt = $row['storedsalt'];

        // Free the result set
        mysqli_free_result($result);
      }
    } else {
      // Handle query execution errors
      echo "ERROR: Could not execute query - " . mysqli_error($link);
    }
    $salt = trim($salt);
    $newHashedPassword = hashPassword($newPassword, $salt);
    $query = "UPDATE employees SET code = null, `password` = '$newHashedPassword', storedsalt = '$salt' WHERE code = '$code'";

    if ($stmt = mysqli_prepare($link, $query)) {
      if (mysqli_stmt_execute($stmt)) {
        $_SESSION['passwordUpdate'] = "Password updated successfully!";
        // Redirect to the login page
        header("Location: ../CRUD/employee/employeeProfile.php");
        exit();
      } else {
        echo "ERROR : Could not execute query" . mysqli_error($link);
      }
    } else {
      echo "ERROR : Could not prepare query" . mysqli_error($link);
    }
  } else {
    $_SESSION['codeError'] = "Incorrect code.";

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  }
}


function hashPassword($password, $salt)
{
  $hashedPassword = hash('sha256', $password . $salt);
  return $hashedPassword;
}

function generateSalt($length = 16)
{
  // Generate a random binary string
  $randomBinary = random_bytes($length);
  $salt = bin2hex($randomBinary);

  return $salt;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Code Verification</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../style/style.css" />
  <style>
    body {
      background: url(https://images.pexels.com/photos/4252523/pexels-photo-4252523.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2);
    }

    a {
      text-decoration: none;
      color: white;
      z-index: 100;
    }
  </style>
</head>

<body>
  <main class="">
    <!-- display alert -->
    <?php
    if (isset($_SESSION['codeError'])) {
    ?>
      <div class="alert alert-danger rounded-bottom-0 text-center" role="alert">
        <div>
          <i class="bi bi-exclamation-circle me-1"></i><?php echo $_SESSION['codeError']; ?>
        </div>
      </div>
    <?php
      unset($_SESSION['codeError']);
    }
    ?>

    <div class="container d-flex p-5 align-items-center justify-content-center min-vh-100">
      <div class="row col-10 col-md-7 col-lg-5 rounded-2 p-2">
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
          <div class="text-center mt-3">
            <h2>Code Verification</h2>
            <p>Enter your verification code and your new password</p>
          </div>

          <div class="mt-2">
            <input type="number" id="reset-code" placeholder="Enter OTP here" class="form-control" name="code" required />
          </div>

          <div class="input-group mt-3">
            <div class="form-floating">
              <input type="password" id="password" name="password" placeholder="Enter your new password here" class="form-control" />
              <label for="password" class="form-label">New Password:
              </label>
            </div>
            <span class="input-group-text" type="span"><i class="bi bi-eye-slash" id="togglePassword"></i></span>
          </div>

          <hr class="mx-5" />

          <div class="d-grid col-4 mx-auto mt-3">
            <input class="btn btn-color" type="submit" value="Submit" name="submit" id="submit" />
            <button class="btn btn-color-secondary btn-sm mt-1" type="button"><a href="updatePassword.php">Return</a></button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    togglePassword.addEventListener("click", () => {
      const type =
        password.getAttribute("type") === "password" ? "text" : "password";
      password.setAttribute("type", type);
      togglePassword.classList.toggle("bi-eye-slash");
      togglePassword.classList.toggle("bi-eye");
    });
  </script>
</body>

</html>