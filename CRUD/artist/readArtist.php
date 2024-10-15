<?php
require_once("../../DBInitialisation/config.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>View Artist</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../../style/style.css" />

  <style>
    .container {
      max-width: 800px;
    }

    body{
      background: url(https://images.pexels.com/photos/4253267/pexels-photo-4253267.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2);
    }
  </style>
</head>

<body>
  <main class="">
    <div class="container d-flex px-5 align-items-center justify-content-center min-vh-100">


      <div class="row rounded-2">
        <h2 class="mt-2">Artist Information</h2>
        <?php
        if (isset($_GET['id'])) {
          $artistId = mysqli_real_escape_string($link, $_GET['id']);
          $query = "SELECT * FROM artists WHERE id='$artistId' ";
          $query_run = mysqli_query($link, $query);

          if (mysqli_num_rows($query_run) > 0) {
            $artist = mysqli_fetch_array($query_run);

        ?>


            <form action="<?php echo $_SERVER["PHP_SELF"] . '?id=' . $artistId; ?>" method="post">

              <div>
                <input type="hidden" name="id" value="<?= $artist['id']; ?>">
              </div>

              <div class="form-floating mt-3">
                <p class="form-control">
                  <?= $artist['firstName']; ?>
                </p>
                <label for="name" class="form-label">First Name:</label>
              </div>

              <div class="form-floating mt-3">
                <p class="form-control">
                  <?= $artist['lastName']; ?>
                </p>
                <label for="name" class="form-label">Last Name:</label>
              </div>

              <div class="form-floating mt-3">
                <p class="form-control">
                  <?= $artist['dateOfShow']; ?>
                </p>
                <label for="name" class="form-label">Event date:</label>
              </div>

              <div class="form-floating mt-3">
                <p class="form-control">
                  <?= $artist['email']; ?>
                </p>
                <label for="email" class="form-label">Email: </label>
              </div>

              <div style="background-color: #ffff">
                <div class="form-floating mt-3">
                  <p class="form-control">
                    <?= $artist['artPieces']; ?>
                  </p>
                  <label for="artPieces" id="artPieces" class="form-label ms-1 mb-0">Number of Art Pieces:
                  </label>
                </div>
              </div>

              <div style="background-color: #ffff">
                <div class="col">
                  <div class="form-floating mt-3">
                    <p class="form-control">
                      <?= $artist['allArtStyles']; ?>
                    </p>
                    <label for="text" class="form-label">Type of Art:</label>
                  </div>
                </div>
              </div>

              <hr class="mx-5" />

              <div class="d-grid col-4 mx-auto my-3">
                <a href="artistTable.php" type="button" class="btn btn-color">Back</a>

              </div>
            </form>
        <?php
          } else {
            echo "No such ID was found";
          }
        }
        ?>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <script>
    function rangeSlider(value) {
      document.getElementById("rangeValue").innerHTML = value;
    }
  </script>
</body>

</html>