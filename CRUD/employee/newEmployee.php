<?php
session_start();
require_once("../../DBInitialisation/config.php");

if ($link === false) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

$firstName = $lastName = $birthDay = $position =  $gender = $country = $address  = $email = $password = $code = $salt = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['submit'])) {
  $firstName = mysqli_real_escape_string($link, $_REQUEST['firstName']);
  $lastName = mysqli_real_escape_string($link, $_REQUEST['lastName']);
  $birthDay = date('Y-m-d', strtotime(mysqli_real_escape_string($link, $_REQUEST['birthDay'])));
  $position = $_POST['position'];
  if (isset($_REQUEST['submit'])) {
    $gender = $_POST['gender'];
  }
  $country = $_POST['country'];
  $address = mysqli_real_escape_string($link, $_REQUEST['address']);
  $email = mysqli_real_escape_string($link, $_REQUEST['email']);
  $password = mysqli_real_escape_string($link, $_REQUEST['password']);
  $salt = generateSalt();
  $hashedPassword = hashPassword($password, $salt);
  $code = null;


  $sql = "INSERT INTO employees (firstName, lastName, birthDay, position, gender, country, `address`, email, `password`, `storedsalt`, `code`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "sssssssssss", $firstName, $lastName, $birthDay, $position, $gender, $country, $address, $email, $hashedPassword, $salt, $code);

    if (mysqli_stmt_execute($stmt)) {
      $_SESSION['newEmployeeNotification'] = "Employee added successfully!";

      // Redirect to the same page to prevent form resubmission
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    } else {
      echo "ERROR: Could not execute query. " . mysqli_error($link);
    }
  } else {
    echo "ERROR: Could not prepare query: $sql. " . mysqli_error($link);
  }

  mysqli_stmt_close($stmt);
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
  <title>Register</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../../style/style.css" />

  <style>
    body {
      background: url('https://images.pexels.com/photos/4252163/pexels-photo-4252163.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2');
    }
  </style>
</head>

<body>
  <main>
    <div class="container d-flex px-5 align-items-center justify-content-center min-vh-100 mt-3 mb-3">
      <div class="row rounded-2">
        <!-- display alert -->
        <?php
        if (isset($_SESSION['newEmployeeNotification'])) {
        ?>
          <div class="alert alert-success mb-0 rounded-bottom-0" role="alert">
            <div>
              <i class="bi bi-person-add me-1"></i><?php echo $_SESSION['newEmployeeNotification']; ?>
            </div>
          </div>
        <?php
          unset($_SESSION['newEmployeeNotification']);
        }
        ?>

        <div class="d-inline-flex">
          <h2 class="mt-2">New Employee</h2>
          <span class="mt-3 ms-auto"><a href="employeeTable.php"><button class="btn btn-color-secondary">Back</button></a></span>
        </div>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="needs-validation" novalidate>
          <div class="form-floating mt-3">
            <input type="text" name="firstName" placeholder="" class="form-control" required />
            <label for="name" class="form-label">First Name:</label>
            <div class="invalid-feedback">Please enter a first name.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="text" name="lastName" placeholder="" class="form-control" required />
            <label for="name" class="form-label">Last Name:</label>
            <div class="invalid-feedback">Please enter a last name.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="date" name="birthDay" placeholder="Birthday" class="form-control" required />
            <label for="name" class="form-label">Birthday:</label>
            <div class="invalid-feedback">Please select a birthday.</div>
          </div>

          <div class="mt-3 ms-2 text-center">
            <label for="gender" id="gender" class="form-label me-2">Gender:
            </label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" value="Male" id="flexRadioMale" required />
              <label class="form-check-label" for="flexRadioMale">
                Male
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" value="Female" id="flexRadioFemale" required />
              <label class="form-check-label" for="flexRadioFemale">
                Female
              </label>
            </div>
          </div>

          <div class="form-floating mt-2">
            <select class="form-select" name="country" id="floatingSelect" required>
              <option selected disabled value="">Choose...</option>
              <option value="Afghanistan">Afghanistan</option>
              <option value="Argentina">Argentina</option>
              <option value="Australia">Australia</option>
              <option value="Austria">Austria</option>
              <option value="Bahrain">Bahrain</option>
              <option value="Belgium">Belgium</option>
              <option value="Brazil">Brazil</option>
              <option value="Bulgaria">Bulgaria</option>
              <option value="Canada">Canada</option>
              <option value="China">China</option>
              <option value="Croatia">Croatia</option>
              <option value="Czech Republic">Czech Republic</option>
              <option value="Denmark">Denmark</option>
              <option value="Dominican Republic">Dominican Republic</option>
              <option value="Egypt Arab Rep.">Egypt Arab Rep.</option>
              <option value="Finland">Finland</option>
              <option value="France">France</option>
              <option value="Germany">Germany</option>
              <option value="Greece">Greece</option>
              <option value="Hungary">Hungary</option>
              <option value="India">India</option>
              <option value="Indonesia">Indonesia</option>
              <option value="Ireland">Ireland</option>
              <option value="Italy">Italy</option>
              <option value="Japan">Japan</option>
              <option value="Jordan">Jordan</option>
              <option value="Lebanon">Lebanon</option>
              <option value="Libya">Libya</option>
              <option value="Malaysia">Malaysia</option>
              <option value="Mexico">Mexico</option>
              <option value="Morocco">Morocco</option>
              <option value="Netherlands">Netherlands</option>
              <option value="North Korea">North Korea</option>
              <option value="Norway">Norway</option>
              <option value="Pakistan">Pakistan</option>
              <option value="Poland">Poland</option>
              <option value="Portugal">Portugal</option>
              <option value="Romania">Romania</option>
              <option value="Russian Federation">Russian Federation</option>
              <option value="Saudi Arabia">Saudi Arabia</option>
              <option value="Singapore">Singapore</option>
              <option value="South Africa">South Africa</option>
              <option value="South Korea">South Korea</option>
              <option value="Spain">Spain</option>
              <option value="Sweden">Sweden</option>
              <option value="Switzerland">Switzerland</option>
              <option value="Thailand">Thailand</option>
              <option value="Tunisia">Tunisia</option>
              <option value="Turkey">Turkey</option>
              <option value="Ukraine">Ukraine</option>
              <option value="United Kingdom">United Kingdom</option>
              <option value="United States">United States</option>
              <option value="Vietnam">Vietnam</option>
            </select>
            <label for="floatingSelect">Country:</label>
            <div class="invalid-feedback">Please select a country.</div>
          </div>

          <div class="form-floating mt-3">
            <textarea class="form-control" name="address" placeholder="" id="floatingAddress" style="height: 100px" required></textarea>
            <label for="floatingTextarea2">Address:</label>
            <div class="invalid-feedback">Please enter an address.</div>
          </div>

          <div class="form-floating mt-3">
            <select class="form-select" name="position" id="floatingSelect" required>
              <option selected disabled value="">Choose...</option>
              <option value="Manager">Event Coordinator/Manager</option>
              <option value="Marketing Manager">
                Public Relations/Marketing Manager
              </option>
              <option value="Guest Service">
                Guest Services/Visitor Experience Coordinator
              </option>
              <option value="Installation">
                Installation/Preparator Team
              </option>
              <option value="Catering">Catering and Hospitality Team</option>
              <option value="Security">Security Personnel</option>
              <option value="Photographer">Photographer/Videographer</option>
              <option value="Sales Associate">Sales Associates</option>
              <option value="AVTeam">Technical Support/AV Team</option>
              <option value="Intern">Volunteers/Interns</option>
            </select>
            <label for="floatingSelect">Position:</label>
            <div class="invalid-feedback">Please select a position.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="email" name="email" placeholder="" class="form-control" required />
            <label for="email" class="form-label">Email: </label>
            <div class="invalid-feedback">Please enter an email.</div>
          </div>

          <div class="input-group mt-3">
            <div class="form-floating">
              <input type="password" id="password" placeholder="" class="form-control" name="password" id="password" required />
              <label for="password" class="form-label">Password:</label>
              <div class="invalid-feedback">Please enter a password.</div>
            </div>
            <span class="input-group-text" type="span" style="max-height: 58px;"><i class="bi bi-eye-slash" id="togglePassword"></i></span>
          </div>

          <hr class="mx-5" />

          <div class="d-grid col-4 mx-auto my-3">
            <input class="btn btn-color" name="submit" type="submit" value="Register New Member" />
          </div>
        </form>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <script>
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