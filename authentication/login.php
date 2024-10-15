<?php
session_start();
require_once("../DBInitialisation/config.php");

if ($link === false) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

$email = $password = $storedsalt = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['submit'])) {
  $email = mysqli_real_escape_string($link, $_REQUEST['email']);
  $email = isset($email) ? trim($email) : "";
  $password = mysqli_real_escape_string($link, $_REQUEST['password']);
  $query = "SELECT `password`, storedsalt FROM employees WHERE email = ?";

  if ($stmt = mysqli_prepare($link, $query)) {
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $storedpassword, $storedsalt);
    mysqli_stmt_fetch($stmt);
    $password = isset($password) ? trim($password) : "";
    $storedpassword = isset($storedpassword) ? trim($storedpassword) : "";
    $storedsalt = isset($storedsalt) ? trim($storedsalt) : "";
    $hashP = hashPassword($password, $storedsalt);

    if ($storedpassword) {
      // Compare the entered password with the stored hashed password
      if ($hashP == $storedpassword) {
        $_SESSION['email'] = $email;

        // Login successful
        echo "Login successful!";
        header("location: landingPage.php");
        exit();
      } else {
        $_SESSION['loginError'] = "Incorrect password.";
      }
    } else {
      $_SESSION['loginError'] = "Incorrect email.";
    }

    mysqli_stmt_close($stmt);
  }
}

mysqli_close($link);

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
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../style/style.css" />
  <style>
    body {
      background: url(https://images.pexels.com/photos/4252523/pexels-photo-4252523.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2);
    }

    #side-img {
      border-radius: 5px 0px 0px 5px;
    }

    .col {
      max-width: 500px;
    }

    a {
      color: #829e90;
    }

    @media only screen and (max-width: 768px) {
      #side-img {
        max-height: 250px;
        /* Set to half of the original value */
        border-radius: 5px 5px 0px 0px;
        object-fit: cover;
        /* Adjust as needed based on your design preferences */
      }

      .col {
        max-height: 315px;
        /* Adjust as needed based on your design preferences */
        max-width: 472px;
      }
    }
  </style>
</head>

<body>
  <main>
    <!-- display alert -->
    <?php
    if (isset($_SESSION['loginError'])) {
    ?>
      <div class="alert alert-danger rounded-bottom-0 text-center" role="alert">
        <div>
          <i class="bi bi-exclamation-circle me-1"></i><?php echo $_SESSION['loginError']; ?>
        </div>
      </div>
    <?php
      unset($_SESSION['loginError']);
    }
    else if (isset($_SESSION['passwordChanged'])) {
    ?>
      <div class="alert alert-success rounded-bottom-0 text-center" role="alert">
        <div>
          <i class="bi bi-check-circle me-1"></i><?php echo $_SESSION['passwordChanged']; ?>
        </div>
      </div>
    <?php
      unset($_SESSION['passwordChanged']);
    }
    ?>

    <div class="container d-flex px-5 align-items-center justify-content-center min-vh-100">
      <div class="row row-cols-1 row-cols-md-2 rounded-2">


        <div class="col g-0">
          <div class="card border-0">
            <img src="https://images.unsplash.com/flagged/photo-1572392640988-ba48d1a74457?q=80&w=1528&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Side Image" class="img-fluid" id="side-img" />
          </div>
        </div>

        <div class="col my-md-auto mt-2">
          <h2>Log In</h2>
          <h5>Sign in with your account</h5>

          <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="needs-validation" novalidate>
            <div class="form-floating mt-3">
              <input type="email" placeholder="Enter your email" name="email" class="form-control" required />
              <label for="email" class="form-label">Email: </label>
            </div>

            <div class="input-group mt-3">
              <div class="form-floating">
                <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" required />
                <label for="password" class="form-label">Password:
                </label>
              </div>
              <span class="input-group-text" type="span"><i class="bi bi-eye-slash" id="togglePassword"></i></span>
            </div>

            <div class="mt-3">
              <button class="btn btn-color col-3" type="submit" name="submit">
                Log in
              </button>
            </div>

            <div class="mt-3">
              <p>Forgot your password? <a href="forgotPassword.php">Click here</a></p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <footer></footer>

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

    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
      form.addEventListener('submit', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }

          form.classList.add('was-validated');
        },
        false
      );
    });
  </script>
</body>

</html>