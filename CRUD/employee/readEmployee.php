<?php
require_once("../../DBInitialisation/config.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Employee</title>
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
                <h2 class="mt-3">Employee Information</h2>

                <?php
                if (isset($_GET['id'])) {
                    $employeeId = mysqli_real_escape_string($link, $_GET['id']);
                    $query = "SELECT * FROM employees WHERE id='$employeeId' ";
                    $query_run = mysqli_query($link, $query);

                    if (mysqli_num_rows($query_run) > 0) {
                        $employee = mysqli_fetch_array($query_run);

                ?>

                        <form action="<?php echo $_SERVER["PHP_SELF"] . '?id=' . $employeeId; ?>" method="post">

                            <div>
                                <input type="hidden" name="id" value="<?= $employee['id']; ?>">
                            </div>

                            <div class="form-floating mt-3">
                                <p class="form-control">
                                    <?= $employee['firstName']; ?>
                                </p>
                                <label for="name" class="form-label">First Name:</label>
                            </div>

                            <div class="form-floating mt-3">
                                <p class="form-control">
                                    <?= $employee['lastName']; ?>
                                </p>
                                <label for="name" class="form-label">Last Name:</label>
                            </div>

                            <div class="form-floating mt-3">
                                <p class="form-control">
                                    <?= $employee['birthDay']; ?>
                                </p>
                                <label for="name" class="form-label">Birthday:</label>

                            </div>

                            <div>
                                <div class="form-floating mt-3">
                                    <p class="form-control">
                                        <?= $employee['gender']; ?>
                                    </p>
                                    <label for="gender" class="form-label">Gender:</label>
                                </div>
                            </div>

                            <div class="form-floating mt-2">
                                <p class="form-control">
                                    <?= $employee['country']; ?>
                                </p>
                                <label for="floatingSelect">Country:</label>
                            </div>

                            <div class="form-floating mt-3">
                                <p class="form-control">
                                    <?= $employee['address']; ?>
                                </p>
                                <label for="floatingTextarea2">Address:</label>
                            </div>

                            <div class="form-floating mt-3">
                                <p class="form-control">
                                    <?= $employee['position']; ?>
                                </p>
                                <label for="floatingSelect">Position:</label>
                            </div>

                            <div class="form-floating mt-3">
                                <p class="form-control">
                                    <?= $employee['email']; ?>
                                </p>
                                <label for="email" class="form-label">Email: </label>
                            </div>

                            <hr class="mx-5" />

                            <div class="d-grid col-4 mx-auto my-3">
                                <a href="employeeTable.php" type="button" class="btn btn-color">Back</a>

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