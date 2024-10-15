<?php
require_once("../DBInitialisation/config.php");

// Start or resume the session
session_start();

if ($link === false) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Check if the 'email' is set in the session
if (!isset($_SESSION['email'])) {
  // Redirect to login.php if the session 'email' is not set
  header("location: login.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['employeeTable'])) {
  // Get the email from the session
  $email = $_SESSION['email'];
  echo $email;

  // SQL query to retrieve the 'position' of the user
  $sql = "SELECT position FROM employees WHERE email='$email'";

  if ($stmt = mysqli_prepare($link, $sql)) {
    if (mysqli_stmt_execute($stmt)) {
      mysqli_stmt_bind_result($stmt, $position);
      mysqli_stmt_fetch($stmt);
      $position = trim($position);

      if ($position == "Manager" || $position == "Marketing Manager") {
        $_SESSION['employeeTableAccess'] = "Access granted.";

        // Redirect to employeeTable.php if the user is a manager
        header("location: ../CRUD/employee/employeeTable.php");
        exit();
      } else {
        // Display an error message if the user does not have permission
        $_SESSION['employeeTableError'] = "You do not have the permission to access this page.";
        header("location: landingPage.php");
        exit();
      }
    } else {
      echo "ERROR: Could not execute query. " . mysqli_error($link);
    }
  } else {
    // Display an error message if there is an issue with the query
    echo "Error in the query: " . mysqli_error($link);
  }

  mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Landing Page</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" type="text/css" href="../style/style.css" />

  <style>
    body {
      background: url('https://images.pexels.com/photos/4252521/pexels-photo-4252521.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2');
    }

    .row {
      background-color: transparent;
      margin-top: 3%;
    }

    .col {
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      transition: all 0.3s ease;
    }

    .col:hover {
      transform: scale(1.05);
    }

    .card {
      background-color: #fffdfb;
    }

    @media only screen and (max-width: 768px) {
      .col {
        margin-left: auto;
        margin-right: auto;
        width: 380px;
      }

      main {
        margin-top: 16px;

      }
    }
  </style>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark fs-5">
      <div class="container">
        <a class="navbar-brand fs-4 mb-1">Art Gallery</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav ">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" href="landingPage.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../CRUD/artist/artistTable.php">Artists</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../CRUD/guest/guestTable.php">Guests</a>
            </li>
            <li class="nav-item">
              <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                <input class="nav-link" type="submit" name="employeeTable" value="Employees">
              </form>
            </li>
          </ul>

          <!-- Log Out Button -->
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="../CRUD/employee/employeeProfile.php">Your Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Log Out</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main>
    <div class="container mb-3">
      <div class="row d-grid col-11 mx-auto">
        <!-- display alert -->
        <?php
        if (isset($_SESSION['updateProfile'])) {
        ?>
          <div class="alert alert-warning mb-0" role="alert">
            <div>
              <i class="bi bi-person-gear me-1"></i><?php echo $_SESSION['updateProfile']; ?>
            </div>
          </div>
        <?php
          unset($_SESSION['updateProfile']);
        } 
        else if (isset($_SESSION['employeeTableError'])) {
        ?>
          <div class="alert alert-danger mb-0" role="alert">
            <div>
              <i class="bi bi-exclamation-triangle-fill me-1"></i><?php echo $_SESSION['employeeTableError']; ?>
            </div>
          </div>
        <?php
          unset($_SESSION['employeeTableError']);
        }
        ?>
      </div>
      <div class="row row-cols-1 row-cols-md-2 g-3">
        <div class="col">
          <div class="card ms-0 ms-md-5">
            <a href="../CRUD/artist/artistTable.php"><img src="https://cdn.dribbble.com/userupload/9988488/file/original-2445da144f039d8be606ffab2536e89f.png?resize=900x600" class="card-img-top" alt="Artist image" /></a>
            <div class="card-body">
              <h5 class="card-title">Artists Table</h5>
              <p class="card-text">
                This table showcases artists' details and their artworks.
              </p>
              <a href="../CRUD/artist/artistTable.php"><button class="btn btn-color">Open Table</button></a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card me-0 me-md-5">
            <a href="../CRUD/guest/guestTable.php"><img src="http://www.chinadaily.com.cn/culture/art/img/attachement/jpg/site1/20121114/0023ae69624d120d3afc09.jpg" class="card-img-top" alt="Guests Image" /></a>
            <div class="card-body">
              <h5 class="card-title">Guests Table</h5>
              <p class="card-text">
                This table showcases guests' details and their bookings.
              </p>
              <a href="../CRUD/guest/guestTable.php"><button class="btn btn-color">Open Table</button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>