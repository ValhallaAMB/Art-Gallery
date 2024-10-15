<?php
session_start();

require_once("../../DBInitialisation/config.php");

if ($link === false) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

$firstName = $lastName = $dateOfAttendance = $paidOrInvited =  $plusOne = $email = $phoneNumber  = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['submit'])) {
  $firstName = mysqli_real_escape_string($link, $_REQUEST['firstName']);
  $lastName = mysqli_real_escape_string($link, $_REQUEST['lastName']);
  $dateOfAttendance = date('Y-m-d', strtotime(mysqli_real_escape_string($link, $_REQUEST['dateOfAttendance'])));
  if (isset($_REQUEST['submit'])) {
    $paidOrInvited = $_POST['paidOrInvited'];
  }
  if (isset($_REQUEST['submit'])) {
    $plusOne = $_POST['plusOne'];
  }
  $email = mysqli_real_escape_string($link, $_REQUEST['email']);
  $phoneNumber = mysqli_real_escape_string($link, $_REQUEST['phoneNumber']);

  $sql = "INSERT INTO guests (firstName, lastName, dateOfAttendance, paidOrInvited, plusOne, email, phoneNumber) VALUES (?, ?, ?, ?, ?, ?, ?)";

  if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "sssssss", $firstName, $lastName, $dateOfAttendance, $paidOrInvited, $plusOne, $email, $phoneNumber);

    if (mysqli_stmt_execute($stmt)) {
      $_SESSION['newGuestNotification'] = "Guest added successfully!";

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
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>New Guest</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../../style/style.css" />


  <style>
    .container {
      margin-top: 4%;
    }

    body {
      background: url(https://images.pexels.com/photos/4252672/pexels-photo-4252672.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2);
    }
  </style>
</head>

<body>
  <main class="">
    <div class="container d-flex px-5 align-items-center justify-content-center mb-5">
      <div class="row rounded-2">
        <!-- display alert -->
        <?php
        if (isset($_SESSION['newGuestNotification'])) {
        ?>
          <div class="alert alert-success mb-0 rounded-bottom-0" role="alert">
            <div>
              <i class="bi bi-person-add me-1"></i><?php echo $_SESSION['newGuestNotification']; ?>
            </div>
          </div>
        <?php
          unset($_SESSION['newGuestNotification']);
        }
        ?>

        <div class="d-inline-flex">
          <h2 class="mt-2">New Guest</h2>
          <span class="mt-3 ms-auto"><a href="guestTable.php"><button class="btn btn-color-secondary">Back</button></a></span>
        </div>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="needs-validation" novalidate>
          <div class="form-floating mt-3">
            <input type="text" name="firstName" placeholder="" class="form-control" required />
            <label for="text" class="form-label">First Name:</label>
            <div class="invalid-feedback">Please enter a first name.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="text" name="lastName" placeholder="" class="form-control" required />
            <label for="text" class="form-label">Last Name:</label>
            <div class="invalid-feedback">Please enter a last name.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="date" name="dateOfAttendance" id="eventDate" placeholder="" class="form-control" required />
            <label for="date" class="form-label">Event Date:</label>
            <div class="invalid-feedback">Please select a Event Date.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="email" name="email" id="email" placeholder="" class="form-control" required />
            <label for="email" id="email" class="form-label">Email: </label>
            <div class="invalid-feedback">Please enter an email.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="number" name="phoneNumber" id="phoneNumber" placeholder="" class="form-control" required />
            <label for="number" class="form-label">Phone Number: </label>
            <div class="invalid-feedback">Please enter an phone number.</div>
          </div>

          <div class="mt-3 text-center">
            <label for="entryType" id="entryType" class="form-label">Entry Type:
            </label>
            <div class="form-check form-check-inline ms-2">
              <input class="form-check-input" name="paidOrInvited" value="Paid" type="radio" id="flexRadioPaid" required />
              <label class="form-check-label" for="flexRadioPaid">
                Paid
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" name="paidOrInvited" value="Invited" type="radio" id="flexRadioInvited" required />
              <label class="form-check-label" for="flexRadioInvited">
                Invited
              </label>
            </div>
            <div class="form-check-inline border border-color rounded-2 px-2">
              <input class="form-check-input" name="plusOne" value="Yes" type="checkbox" id="inlineChkPlus1" disabled>
              <label class="form-check-label" for="inlineChkPlus1">Plus One (+1 )</label>
            </div>
          </div>

          <hr class="mx-5" />

          <div class="d-grid col-4 mx-auto my-3">
            <input class="btn btn-color" name="submit" type="submit" value="Add Guest" />
          </div>
        </form>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var invitedRadio = document.getElementById("flexRadioInvited");
      var plusOneCheckbox = document.getElementById("inlineChkPlus1");
      var paidRadio = document.getElementById("flexRadioPaid");

      if (invitedRadio.checked) {
        plusOneCheckbox.disabled = false;
      }

      invitedRadio.addEventListener("change", function() {
        plusOneCheckbox.disabled = false;
      });

      paidRadio.addEventListener("change", function() {
        plusOneCheckbox.disabled = true;
        plusOneCheckbox.checked = false;
      });
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