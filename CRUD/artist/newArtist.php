<?php
session_start();

require_once("../../DBInitialisation/config.php");

if ($link === false) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

$firstName = $lastName = $dateOfShow = $email =  $allArtStyles = $artPieces  = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['submit'])) {
  $firstName = mysqli_real_escape_string($link, $_REQUEST['firstName']);
  $lastName = mysqli_real_escape_string($link, $_REQUEST['lastName']);
  $dateOfShow = date('Y-m-d', strtotime(mysqli_real_escape_string($link, $_REQUEST['dateOfShow'])));
  $email = mysqli_real_escape_string($link, $_REQUEST['email']);
  if (isset($_REQUEST['submit'])) {
    $artStyle = $_POST['artStyle'];
    $allArtStyles = implode(", ", $artStyle);
  }
  $artPieces = mysqli_real_escape_string($link, $_REQUEST['artPieces']);



  $sql = "INSERT INTO artists (firstName, lastName, dateOfShow, email, allArtStyles, artPieces) VALUES (?, ?, ?, ?, ?, ?)";

  if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "sssssi", $firstName, $lastName, $dateOfShow, $email, $allArtStyles, $artPieces);

    if (mysqli_stmt_execute($stmt)) {
      $_SESSION['newArtistNotification'] = "Artist added successfully!";

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
  <title>New Artist</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../../style/style.css" />

  <style>
    .container {
      max-width: 800px;
      min-height: 100vh;
    }

    body {
      background: url('https://images.pexels.com/photos/4253267/pexels-photo-4253267.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2');
    }
  </style>
</head>

<body>
  <main>
    <div class="container d-flex px-5 align-items-center justify-content-center  mt-3 mb-3">
      <div class="row rounded-2">
        <!-- display alert -->
        <?php
        if (isset($_SESSION['newArtistNotification'])) {
        ?>
          <div class="alert alert-success mb-0 rounded-bottom-0" role="alert">
            <div>
              <i class="bi bi-person-add me-1"></i><?php echo $_SESSION['newArtistNotification']; ?>
            </div>
          </div>
        <?php
          unset($_SESSION['newArtistNotification']);
        }
        ?>
        <div class="d-inline-flex">
          <h2 class="mt-2">New Artist</h2>
          <span class="mt-3 ms-auto"><a href="artistTable.php"><button class="btn btn-color-secondary">Back</button></a></span>
        </div>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="needs-validation" onsubmit="return validateForm()" novalidate>
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
            <input type="date" name="dateOfShow" placeholder="" class="form-control" required />
            <label for="name" class="form-label">Event date:</label>
            <div class="invalid-feedback">Please select a event date.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="email" name="email" placeholder="" class="form-control" required />
            <label for="email" class="form-label">Email: </label>
            <div class="invalid-feedback">Please enter an email.</div>
          </div>

          <div class="mt-3 p-2 rounded-2" id="artPiecesContainer" style="background-color: #ffff; border: 1px solid #ced4da;">
            <label for="artPieces" class="form-label ms-1 mb-0">Number of Art Pieces:
            </label>
            <div class="d-flex align-items-center ms-1">
              <input type="range" id="artPieces" name="artPieces" class="form-range" value="0" min="0" max="30" onmousemove="rangeSlider(this.value)" />
              <span class="border rounded-2 py-1 px-2 mb-1 me-2 ms-3" id="rangeValue">0</span>
            </div>
          </div>
          <p id="hiddenAPText" style="color: #dc3545; display: none; margin-top: .1rem; font-size: .875em;">Please a number of art pieces</p>

          <div class="row py-1 rounded-2 mt-3 mx-0" id="artStyle" style="background-color: #ffff; border: 1px solid #ced4da;">
            <label for="text" class="form-label">Type of Art</label>
            <div class="col">
              <div class="form-check ">
                <input class="form-check-input" name="artStyle[]" type="checkbox" value="Paintings" id="paintingsCheckbox" />
                <label class="form-check-label" for="paintingsCheckbox">
                  Paintings
                </label>
              </div>

              <div class="form-check ">
                <input class="form-check-input" class="check" name="artStyle[]" type="checkbox" value="Sculptures" id="sculpturesCheckbox" />
                <label class="form-check-label" for="sculpturesCheckbox">
                  Sculptures
                </label>
              </div>

              <div class="form-check ">
                <input class="form-check-input" class="check" name="artStyle[]" type="checkbox" value="Drawings" id="drawingsCheckbox" />
                <label class="form-check-label" for="drawingsCheckbox">
                  Drawings
                </label>
              </div>

              <div class="form-check ">
                <input class="form-check-input" class="check" name="artStyle[]" type="checkbox" value="Photography" id="photographyCheckbox" />
                <label class="form-check-label" for="photographyCheckbox">
                  Photography
                </label>
              </div>

              <div class="form-check ">
                <input class="form-check-input" name="artStyle[]" type="checkbox" value="Print" id="printCheckbox" />
                <label class="form-check-label" for="printsCheckbox">
                  Print
                </label>
              </div>
            </div>
            <div class="col">
              <div class="form-check ">
                <input class="form-check-input" name="artStyle[]" type="checkbox" value="Decorative Art" id="decorativeCheckbox" />
                <label class="form-check-label" for="decorativeCheckbox">
                  Decorative Art
                </label>
              </div>

              <div class="form-check ">
                <input class="form-check-input" name="artStyle[]" type="checkbox" value="Textiles and Textile Art" id="textilesCheckbox" />
                <label class="form-check-label" for="textilesCheckbox">
                  Textiles and Textile Art
                </label>
              </div>

              <div class="form-check ">
                <input class="form-check-input" name="artStyle[]" type="checkbox" value="Installation Art" id="installationCheckbox" />
                <label class="form-check-label" for="installationCheckbox">
                  Installation Art
                </label>
              </div>

              <div class="form-check ">
                <input class="form-check-input" name="artStyle[]" type="checkbox" value="Ceramic and Pottery" id="ceramicCheckbox" />
                <label class="form-check-label" for="ceramicCheckbox">
                  Ceramic and Pottery
                </label>
              </div>

              <div class="form-check ">
                <input class="form-check-input" name="artStyle[]" type="checkbox" value="New Media and Digital Art" id="digitalCheckbox" />
                <label class="form-check-label" for="digitalCheckbox">
                  New Media and Digital Art
                </label>
              </div>
            </div>
          </div>
          <p id="hiddenASText" class="" style="color: #dc3545; display: none; margin-top: .1rem; font-size: .875em;">Please select art style</p>

          <hr class="mx-5" />

          <div class="d-grid col-4 mx-auto my-3">
            <input class="btn btn-color" type="submit" name="submit" value="Add Artist" />
          </div>
        </form>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <script>
    function rangeSlider(value) {
      document.getElementById("rangeValue").innerHTML = value;
    }

    function validateForm() {
      var checkboxes = document.querySelectorAll('input[name="artStyle[]"]:checked');

      if (checkboxes.length === 0) {
        document.getElementById("artStyle").style.border = "solid 1px #dc3545";
        document.getElementById("hiddenASText").style.display = "block";
        return false; // Prevent form submission
      } else {
        document.getElementById("artStyle").style.border = "solid 1px #ced4da";
        document.getElementById("hiddenASText").style.display = "none";
      }

      // Check if range is selected
      var rangeValue = document.getElementById("artPieces").value;
      if (rangeValue === "0") {
        document.getElementById("artPiecesContainer").style.border = "solid 1px #dc3545";
        document.getElementById("hiddenAPText").style.display = "block";
        return false; // Prevent form submission
      } else {
        document.getElementById("artPiecesContainer").style.border = "solid 1px #ced4da";
        document.getElementById("hiddenAPText").style.display = "none";
      }

      // Rest of the existing validation logic
      var forms = document.querySelectorAll('.needs-validation');
      Array.prototype.slice.call(forms).forEach(function(form) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      });

      return true; // Allow form submission
    }
  </script>
</body>

</html>