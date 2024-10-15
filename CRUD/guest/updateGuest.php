<?php
session_start();
require_once("../../DBInitialisation/config.php");

if (isset($_GET['id'])) {
    $guestId = mysqli_real_escape_string($link, $_GET['id']);

    $sql = "SELECT * FROM guests WHERE id='$guestId' ";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);



        if (isset($_POST['update'])) {

            $firstName = mysqli_real_escape_string($link, $_REQUEST['firstName']);
            $lastName = mysqli_real_escape_string($link, $_REQUEST['lastName']);
            $email = mysqli_real_escape_string($link, $_REQUEST['email']);
            $phoneNumber = mysqli_real_escape_string($link, $_REQUEST['phoneNumber']);
            $dateOfAttendance = date('Y-m-d', strtotime(mysqli_real_escape_string($link, $_REQUEST['dateOfAttendance'])));
            $paidOrInvited = $_POST['paidOrInvited'];
            $plusOne = isset($_POST['plusOne']) ? $_POST['plusOne'] : 'No';


            $updateSql = "UPDATE guests SET firstName=?, lastName=?, email=?, phoneNumber=?, dateOfAttendance=?,  
                paidOrInvited=?, plusOne=? WHERE id=?";


            $stmt = mysqli_prepare($link, $updateSql);

            if ($stmt) {
                mysqli_stmt_bind_param(
                    $stmt,
                    "sssssssi",
                    $firstName,
                    $lastName,
                    $email,
                    $phoneNumber,
                    $dateOfAttendance,
                    $paidOrInvited,
                    $plusOne,
                    $guestId
                );

                mysqli_stmt_execute($stmt);

                mysqli_stmt_close($stmt);
            } else {
                echo "Error in preparing the update statement: " . mysqli_error($link);
            }
            $_SESSION['updateGuestNotification'] = "Guest updated successfully!";

            header("location: guestTable.php");
        }
    } else {
        echo "No such ID was found";
    }
} else {
    echo "Error in the query: " . mysqli_error($link);
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Guest</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
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
                <div class="d-inline-flex">
                    <h2 class="mt-2">Update Guest</h2>
                    <span class="mt-3 ms-auto"><a href="guestTable.php"><button class="btn btn-color-secondary">Back</button></a></span>
                </div>

                <?php
                if (isset($_GET['id'])) {
                    $guestId = mysqli_real_escape_string($link, $_GET['id']);
                    $query = "SELECT * FROM guests WHERE id='$guestId' ";
                    $query_run = mysqli_query($link, $query);

                    if (mysqli_num_rows($query_run) > 0) {
                        $guest = mysqli_fetch_array($query_run);

                ?>
                        <form action="<?php echo $_SERVER["PHP_SELF"] . '?id=' . $guestId; ?>" method="post" class="needs-validation" novalidate>

                            <div>
                                <input type="hidden" name="id" value="<?= $guest['id']; ?>">
                            </div>
                            <div class="form-floating mt-3">
                                <input type="text" name="firstName" value="<?= $guest['firstName']; ?>" placeholder="" class="form-control" required />
                                <label for="text" class="form-label">First Name:</label>
                                <div class="invalid-feedback">Please enter a first name.</div>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="text" name="lastName" value="<?= $guest['lastName']; ?>" placeholder="" class="form-control" required />
                                <label for="text" class="form-label">Last Name:</label>
                                <div class="invalid-feedback">Please enter a last name.</div>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="date" name="dateOfAttendance" value="<?= $guest['dateOfAttendance']; ?>" id="eventDate" placeholder="" class="form-control" required />
                                <label for="date" class="form-label">Event Date:</label>
                                <div class="invalid-feedback">Please select a event date.</div>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="email" name="email" value="<?= $guest['email']; ?>" id="email" placeholder="" class="form-control" required />
                                <label for="email" id="email" class="form-label">Email: </label>
                                <div class="invalid-feedback">Please enter an email.</div>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="number" name="phoneNumber" value="<?= $guest['phoneNumber']; ?>" id="phoneNumber" placeholder="" class="form-control" required />
                                <label for="number" class="form-label">Phone Number: </label>
                                <div class="invalid-feedback">Please enter an phone number.</div>
                            </div>

                            <div class="mt-3 text-center">
                                <label for="entryType" id="entryType" class="form-label">Entry Type:
                                </label>
                                <div class="form-check form-check-inline ms-2">
                                    <input class="form-check-input" name="paidOrInvited" value="Paid" <?= ($guest['paidOrInvited'] == 'Paid') ? 'checked' : ''; ?> type="radio" id="flexRadioPaid" required />
                                    <label class="form-check-label" for="flexRadioPaid">Paid</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="paidOrInvited" value="Invited" <?= ($guest['paidOrInvited'] == 'Invited') ? 'checked' : ''; ?> type="radio" id="flexRadioInvited" required />
                                    <label class="form-check-label" for="flexRadioInvited">Invited</label>
                                </div>
                                <div class="form-check-inline border border-color rounded-2 px-2">
                                    <input class="form-check-input" name="plusOne" value="Yes" <?= ($guest['plusOne'] == 'Yes') ? 'checked' : ''; ?> type="checkbox" id="inlineChkPlus1" disabled>
                                    <label class="form-check-label" for="inlineChkPlus1">Plus One (+1 )</label>
                                </div>
                                <div class="invalid-feedback">Please select an entry type.</div>
                            </div>

                            <hr class="mx-5" />

                            <div class="d-grid col-4 mx-auto my-3">
                                <input class="btn btn-color" name="update" type="submit" value="Update Guest" />
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