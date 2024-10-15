<?php
require_once("../../DBInitialisation/config.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Guest</title>

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" type="text/css" href="../../style/style.css" />
    <style>
      body{
        background: url(https://images.pexels.com/photos/4252672/pexels-photo-4252672.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2);
      }
    </style>
  </head>

  <body>
    <main class="">
      
      <div class="container d-flex px-5 align-items-center justify-content-center min-vh-100">
        <div class="row rounded-2">
          <h2 class="mt-2">Guest Information</h2>

          <?php
            if(isset($_GET['id'])){
                $guestId = mysqli_real_escape_string($link, $_GET['id']);
                $query = "SELECT * FROM guests WHERE id='$guestId' ";
                $query_run = mysqli_query($link, $query);

                if(mysqli_num_rows($query_run) > 0){
                    $guest = mysqli_fetch_array($query_run);
                    
                    ?>

                <form action="<?php echo $_SERVER["PHP_SELF"] . '?id=' . $guestId;?>" method = "post">

                <div>
                <input type="hidden" name="id" value="<?= $guest['id'];?>">
                </div>
                    <div class="form-floating mt-3">
                    <p class="form-control">
                        <?= $guest['firstName'];?>
                    </p>
                    <label for="text" class="form-label">First Name:</label>
                    </div>

                    <div class="form-floating mt-3">
                    <p class="form-control">
                        <?= $guest['lastName'];?>
                    </p>
                    <label for="text" class="form-label">Last Name:</label>
                    </div>

                    <div class="form-floating mt-3">
                    <p class="form-control">
                        <?= $guest['dateOfAttendance'];?>
                    </p>
                    <label for="date" class="form-label">Event Date:</label>
                    </div>

                    <div class="form-floating mt-3">
                    <p class="form-control">
                        <?= $guest['email'];?>
                    </p>
                    <label for="email" id="email" class="form-label">Email: </label>
                    </div>

                    <div class="form-floating mt-3">
                    <p class="form-control">
                        <?= $guest['phoneNumber'];?>
                    </p>
                        <label for="number" class="form-label">Phone Number: </label>
                    </div>

                    <div>
                        <div class="form-floating mt-3">
                            <p class="form-control">
                                <?= $guest['paidOrInvited'];?>
                            </p>
                            <label for="entryType" id="entryType" class="form-label"
                                >Entry Type:
                            </label>
                        </div>
                    </div>
                    <div>
                        <div class="form-floating mt-3">
                            <p class="form-control">
                                <?= $guest['plusOne'];?>
                            </p>
                            <label class="form-label" for="inlineChkPlus1">Plus One (+1 ):</label>
                        </div>
                    </div>

                    <hr class="mx-5" />

                    <div class="d-grid col-4 mx-auto my-3">
                    <a href="guestTable.php" type="button" class="btn btn-color">Back</a>
                    </div>
                </form>
            <?php
            }
                else{
                    echo"No such ID was found";
                }
            }
            ?>
        </div>
      </div>
    </main>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
      crossorigin="anonymous"
    ></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var invitedRadio = document.getElementById("flexRadioInvited");
            var plusOneCheckbox = document.getElementById("inlineChkPlus1");
            var paidRadio = document.getElementById("flexRadioPaid");
    
            invitedRadio.addEventListener("change", function () {
                plusOneCheckbox.disabled = false;
            });

            paidRadio.addEventListener("change", function(){
                plusOneCheckbox.disabled = true;
                plusOneCheckbox.checked = false;
            })
        });
    </script>
    
  </body>
</html>
