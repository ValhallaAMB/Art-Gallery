<?php
session_start();
require_once("../../DBInitialisation/config.php");

if (isset($_GET['id'])) {
    $employeeId = mysqli_real_escape_string($link, $_GET['id']);

    $sql = "SELECT * FROM employees WHERE id='$employeeId' ";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);


        if (isset($_REQUEST['update'])) {
            $firstName = mysqli_real_escape_string($link, $_REQUEST['firstName']);
            $lastName = mysqli_real_escape_string($link, $_REQUEST['lastName']);
            $birthDay = date('Y-m-d', strtotime(mysqli_real_escape_string($link, $_REQUEST['birthDay'])));
            $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
            $country = $_POST['country'];
            $address = mysqli_real_escape_string($link, $_REQUEST['address']);
            $position = $_POST['position'];
            $email = mysqli_real_escape_string($link, $_REQUEST['email']);


            $updateSql = "UPDATE employees SET firstName=?, lastName=?, birthDay=?, gender=?, country=?,  
     `address`=?, position=?, email=? WHERE id=?";

            $stmt = mysqli_prepare($link, $updateSql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssssssssi", $firstName, $lastName, $birthDay, $gender, $country, $address, $position, $email, $employeeId);
                mysqli_stmt_execute($stmt);

                mysqli_stmt_close($stmt);
            } else {
                echo "Error in preparing the update statement: " . mysqli_error($link);
            }
            $_SESSION['updateEmployeeNotification'] = "Employee updated successfully!";

            header('location: employeeTable.php');
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
    <title>Update Employee</title>
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
    <main class="">
        <div class="container d-flex px-5 align-items-center justify-content-center min-vh-100 mt-3 mb-3">
            <div class="row rounded-2">
                <div class="d-inline-flex">
                    <h2 class="mt-3">Update Employee</h2>
                    <span class="mt-3 ms-auto"><a href="employeeTable.php"><button class="btn btn-color-secondary">Back</button></a></span>
                </div>

                <form action="<?php echo $_SERVER["PHP_SELF"] . '?id=' . $employeeId; ?>" method="post" class="needs-validation" novalidate>
                    <div>
                        <input type="hidden" name="id" value="<?= $employee['id']; ?>">
                    </div>

                    <div class="form-floating mt-3">
                        <input type="text" name="firstName" value="<?= $employee['firstName']; ?>" placeholder="" class="form-control" required />
                        <label for="name" class="form-label">First Name:</label>
                        <div class="invalid-feedback">Please enter a first name.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="text" name="lastName" value="<?= $employee['lastName']; ?>" placeholder="" class="form-control" required />
                        <label for="name" class="form-label">Last Name:</label>
                        <div class="invalid-feedback">Please enter a last name.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="date" name="birthDay" value="<?= $employee['birthDay']; ?>" placeholder="Birthday" class="form-control" required />
                        <label for="name" class="form-label">Birthday:</label>
                        <div class="invalid-feedback">Please select a birthday.</div>
                    </div>

                    <div class="mt-3 ms-2 text-center">
                        <label for="gender" id="gender" class="form-label me-2">Gender:
                        </label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Male" <?= ($employee['gender'] == 'Male') ? 'checked' : ''; ?> id="flexRadioMale" required />
                            <label class="form-check-label" for="flexRadioMale">
                                Male
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Female" <?= ($employee['gender'] == 'Female') ? 'checked' : ''; ?> id="flexRadioFemale" required />
                            <label class="form-check-label" for="flexRadioFemale">
                                Female
                            </label>
                        </div>
                        <div class="invalid-feedback">Please select a gender.</div>
                    </div>

                    <div class="form-floating mt-2">
                        <select class="form-select" name="country" value="<?= $employee['country']; ?>" id="floatingSelect" required>
                            <option selected disabled value="">Choose...</option>
                            <option value="Afghanistan" <?= ($employee['country'] == 'Afghanistan') ? 'selected' : ''; ?>>Afghanistan</option>
                            <option value="Argentina" <?= ($employee['country'] == 'Argentina') ? 'selected' : ''; ?>>Argentina</option>
                            <option value="Australia" <?= ($employee['country'] == 'Australia') ? 'selected' : ''; ?>>Australia</option>
                            <option value="Austria" <?= ($employee['country'] == 'Austria') ? 'selected' : ''; ?>>Austria</option>
                            <option value="Bahrain" <?= ($employee['country'] == 'Bahrain') ? 'selected' : ''; ?>>Bahrain</option>
                            <option value="Belgium" <?= ($employee['country'] == 'Belgium') ? 'selected' : ''; ?>>Belgium</option>
                            <option value="Brazil" <?= ($employee['country'] == 'Brazil') ? 'selected' : ''; ?>>Brazil</option>
                            <option value="Bulgaria" <?= ($employee['country'] == 'Bulgaria') ? 'selected' : ''; ?>>Bulgaria</option>
                            <option value="Canada" <?= ($employee['country'] == 'Canada') ? 'selected' : ''; ?>>Canada</option>
                            <option value="China" <?= ($employee['country'] == 'China') ? 'selected' : ''; ?>>China</option>
                            <option value="Croatia" <?= ($employee['country'] == 'Croatia') ? 'selected' : ''; ?>>Croatia</option>
                            <option value="Czech Republic" <?= ($employee['country'] == 'Czech Republic') ? 'selected' : ''; ?>>Czech Republic</option>
                            <option value="Denmark" <?= ($employee['country'] == 'Denmark') ? 'selected' : ''; ?>>Denmark</option>
                            <option value="Dominican Republic" <?= ($employee['country'] == 'Dominican Republic') ? 'selected' : ''; ?>>Dominican Republic</option>
                            <option value="Egypt Arab Rep." <?= ($employee['country'] == 'Egypt Arab Rep.') ? 'selected' : ''; ?>>Egypt Arab Rep.</option>
                            <option value="Finland" <?= ($employee['country'] == 'Finland') ? 'selected' : ''; ?>>Finland</option>
                            <option value="France" <?= ($employee['country'] == 'France') ? 'selected' : ''; ?>>France</option>
                            <option value="Germany" <?= ($employee['country'] == 'Germany') ? 'selected' : ''; ?>>Germany</option>
                            <option value="Greece" <?= ($employee['country'] == 'Greece') ? 'selected' : ''; ?>>Greece</option>
                            <option value="Hungary" <?= ($employee['country'] == 'Hungary') ? 'selected' : ''; ?>>Hungary</option>
                            <option value="India" <?= ($employee['country'] == 'India') ? 'selected' : ''; ?>>India</option>
                            <option value="Indonesia" <?= ($employee['country'] == 'Indonesia') ? 'selected' : ''; ?>>Indonesia</option>
                            <option value="Ireland" <?= ($employee['country'] == 'Ireland') ? 'selected' : ''; ?>>Ireland</option>
                            <option value="Italy" <?= ($employee['country'] == 'Italy') ? 'selected' : ''; ?>>Italy</option>
                            <option value="Japan" <?= ($employee['country'] == 'Japan') ? 'selected' : ''; ?>>Japan</option>
                            <option value="Jordan" <?= ($employee['country'] == 'Jordan') ? 'selected' : ''; ?>>Jordan</option>
                            <option value="Lebanon" <?= ($employee['country'] == 'Lebanon') ? 'selected' : ''; ?>>Lebanon</option>
                            <option value="Libya" <?= ($employee['country'] == 'Libya') ? 'selected' : ''; ?>>Libya</option>
                            <option value="Malaysia" <?= ($employee['country'] == 'Malaysia') ? 'selected' : ''; ?>>Malaysia</option>
                            <option value="Mexico" <?= ($employee['country'] == 'Mexico') ? 'selected' : ''; ?>>Mexico</option>
                            <option value="Morocco" <?= ($employee['country'] == 'Morocco') ? 'selected' : ''; ?>>Morocco</option>
                            <option value="Netherlands" <?= ($employee['country'] == 'Netherlands') ? 'selected' : ''; ?>>Netherlands</option>
                            <option value="North Korea" <?= ($employee['country'] == 'North Korea') ? 'selected' : ''; ?>>North Korea</option>
                            <option value="Norway" <?= ($employee['country'] == 'Norway') ? 'selected' : ''; ?>>Norway</option>
                            <option value="Pakistan" <?= ($employee['country'] == 'Pakistan') ? 'selected' : ''; ?>>Pakistan</option>
                            <option value="Poland" <?= ($employee['country'] == 'Poland') ? 'selected' : ''; ?>>Poland</option>
                            <option value="Portugal" <?= ($employee['country'] == 'Portugal') ? 'selected' : ''; ?>>Portugal</option>
                            <option value="Romania" <?= ($employee['country'] == 'Romania') ? 'selected' : ''; ?>>Romania</option>
                            <option value="Russian Federation" <?= ($employee['country'] == 'Russian Federation') ? 'selected' : ''; ?>>Russian Federation</option>
                            <option value="Saudi Arabia" <?= ($employee['country'] == 'Saudi Arabia') ? 'selected' : ''; ?>>Saudi Arabia</option>
                            <option value="Singapore" <?= ($employee['country'] == 'Singapore') ? 'selected' : ''; ?>>Singapore</option>
                            <option value="South Africa" <?= ($employee['country'] == 'South Africa') ? 'selected' : ''; ?>>South Africa</option>
                            <option value="South Korea" <?= ($employee['country'] == 'South Korea') ? 'selected' : ''; ?>>South Korea</option>
                            <option value="Spain" <?= ($employee['country'] == 'Spain') ? 'selected' : ''; ?>>Spain</option>
                            <option value="Sweden" <?= ($employee['country'] == 'Sweden') ? 'selected' : ''; ?>>Sweden</option>
                            <option value="Switzerland" <?= ($employee['country'] == 'Switzerland') ? 'selected' : ''; ?>>Switzerland</option>
                            <option value="Thailand" <?= ($employee['country'] == 'Thailand') ? 'selected' : ''; ?>>Thailand</option>
                            <option value="Tunisia" <?= ($employee['country'] == 'Tunisia') ? 'selected' : ''; ?>>Tunisia</option>
                            <option value="Turkey" <?= ($employee['country'] == 'Turkey') ? 'selected' : ''; ?>>Turkey</option>
                            <option value="Ukraine" <?= ($employee['country'] == 'Ukraine') ? 'selected' : ''; ?>>Ukraine</option>
                            <option value="United Kingdom" <?= ($employee['country'] == 'United Kingdom') ? 'selected' : ''; ?>>United Kingdom</option>
                            <option value="United States" <?= ($employee['country'] == 'United States') ? 'selected' : ''; ?>>United States</option>
                            <option value="Vietnam" <?= ($employee['country'] == 'Vietnam') ? 'selected' : ''; ?>>Vietnam</option>
                        </select>
                        <label for="floatingSelect">Country:</label>
                        <div class="invalid-feedback">Please select a country.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <textarea class="form-control" name="address" placeholder="" id="floatingAddress" style="height: 100px" required><?= $employee['address']; ?></textarea>
                        <label for="floatingTextarea2">Address:</label>
                        <div class="invalid-feedback">Please enter Address.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <select class="form-select" name="position" value="<?= $employee['position']; ?>" id="floatingSelect" required>
                            <option selected disabled value="">Choose...</option>
                            <option value="Manager" <?= ($employee['position'] == 'Manager') ? 'selected' : ''; ?>>Event Coordinator/Manager</option>
                            <option value="Marketing Manager" <?= ($employee['position'] == 'Marketing Manager') ? 'selected' : ''; ?>>
                                Public Relations/Marketing Manager
                            </option>
                            <option value="Guest Service" <?= ($employee['position'] == 'Guest Service') ? 'selected' : ''; ?>>
                                Guest Services/Visitor Experience Coordinator
                            </option>
                            <option value="Installation" <?= ($employee['position'] == 'Installation') ? 'selected' : ''; ?>>
                                Installation/Preparator Team
                            </option>
                            <option value="Catering" <?= ($employee['position'] == 'Catering') ? 'selected' : ''; ?>>Catering and Hospitality Team</option>
                            <option value="Security" <?= ($employee['position'] == 'Security') ? 'selected' : ''; ?>>Security Personnel</option>
                            <option value="Photographer" <?= ($employee['position'] == 'Photographer') ? 'selected' : ''; ?>>Photographer/Videographer</option>
                            <option value="Sales Associate" <?= ($employee['position'] == 'Sales Associates') ? 'selected' : ''; ?>>Sales Associates</option>
                            <option value="AVTeam" <?= ($employee['position'] == 'AVTeam') ? 'selected' : ''; ?>>Technical Support/AV Team</option>
                            <option value="Intern" <?= ($employee['position'] == 'Intern') ? 'selected' : ''; ?>>Volunteers/Interns</option>
                        </select>
                        <label for="floatingSelect">Position:</label>
                        <div class="invalid-feedback">Please select a position.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="email" name="email" value="<?= $employee['email']; ?>" placeholder="" class="form-control" required />
                        <label for="email" class="form-label">Email: </label>
                        <div class="invalid-feedback">Please enter an email.</div>
                    </div>

                    <hr class="mx-5" />

                    <div class="d-grid col-4 mx-auto my-3">
                        <input class="btn btn-color" name="update" type="submit" value="Update Employee" />
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
    </script>
</body>

</html>