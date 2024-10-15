<?php
require_once("../../DBInitialisation/config.php");

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

// ------------------- pagination & search bar -------------------

$limit = isset($_GET['limit']) ? $_GET['limit'] : 10; // Change this value based on your preference
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search bar
if (isset($_POST["submit"]) && !empty($_POST["search"])) {
    $search = mysqli_real_escape_string($link, $_POST["search"]);
    $sql = "SELECT * FROM artists WHERE id LIKE '%$search%' OR firstName LIKE '%$search%' OR lastName LIKE '%$search%' OR email LIKE '%$search%' OR dateOfShow LIKE '%$search%' OR artPieces LIKE '%$search%' OR allArtStyles LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM artists LIMIT $offset, $limit";
}

$result = mysqli_query($link, $sql);

if ($result) {
    $artists = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($link);
}

// Count total number of records for pagination
$total_records_query = "SELECT COUNT(*) as count FROM artists";
$total_records_result = mysqli_query($link, $total_records_query);
$total_records = mysqli_fetch_assoc($total_records_result)['count'];
$pages = ceil($total_records / $limit);
$previous = ($page > 1) ? $page - 1 : 1;
$next = ($page < $pages) ? $page + 1 : $pages;

// ------------------- Employee Table -------------------

// to view employee table only if the user is a manager or marketing manager
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

                header("location: ../employee/employeeTable.php");
                exit();
            } else {
                // Display an error message if the user does not have permission
                $_SESSION['employeeTableError'] = "You do not have the permission to access this page.";
                header("location: artistTable.php");
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Artist Table</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" type="text/css" href="../../style/style.css" />
    <style>
        .page-link {
            color: #829e90;
        }

        body {
            background: url('https://images.pexels.com/photos/4253267/pexels-photo-4253267.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2');
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fs-5">
            <div class="container">
                <a class="navbar-brand fs-4">Art Gallery</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav ">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="../../authentication/landingPage.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="artistTable.php">Artists</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../guest/guestTable.php">Guests</a>
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
                            <a class="nav-link" href="../employee/employeeProfile.php">Your Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../authentication/login.php">Log Out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="container mt-5 mb-3">
            <div class="row mx-2 rounded-top-2">
                <!-- display alert -->
                <?php
                if (isset($_SESSION['deleteArtistNotification'])) {
                ?>
                    <div class="alert alert-warning mb-0 rounded-bottom-0" role="alert">
                        <div>
                            <i class="bi bi-person-x me-1"></i><?php echo $_SESSION['deleteArtistNotification']; ?>
                        </div>
                    </div>
                <?php
                    unset($_SESSION['deleteArtistNotification']);
                } else if (isset($_SESSION['updateArtistNotification'])) {
                ?>
                    <div class="alert alert-info mb-0 rounded-bottom-0" role="alert">
                        <div>
                            <i class="bi bi-person-gear me-1"></i><?php echo $_SESSION['updateArtistNotification']; ?>
                        </div>
                    </div>
                <?php
                    unset($_SESSION['updateArtistNotification']);
                } else if (isset($_SESSION['employeeTableError'])) {
                ?>
                    <div class="alert alert-danger mb-0 rounded-bottom-0" role="alert">
                        <div>
                            <i class="bi bi-exclamation-triangle-fill me-1"></i><?php echo $_SESSION['employeeTableError']; ?>
                        </div>
                    </div>
                <?php
                    unset($_SESSION['employeeTableError']);
                }
                ?>

                <h2 class="mb-3 mt-2">Artist Table</h2>
                <!-- search bar -->
                <div class="col">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Search" name="search"/>
                            <button class="btn btn-outline-color-secondary" type="submit" name="submit">
                                Search
                            </button>
                        </div>
                    </form>
                </div>

                <!-- items per page dropdown & add new artist button -->
                <div class="col text-end">
                    <a href="newArtist.php" class="btn btn-outline-color  mb-1 mb-lg-0">Add new artist<i class="bi bi-plus-circle ms-2"></i></a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-color-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Items per Page
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?limit=10">10</a></li>
                            <li><a class="dropdown-item" href="?limit=30">30</a></li>
                            <li><a class="dropdown-item" href="?limit=50">50</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="row mx-2 px-3 table-responsive">
                <table class="table table-hover" id="example">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Event date</th>
                            <th scope="col">ِArt pieces</th>
                            <th scope="col">Art Style</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php
                        foreach ($artists as $artist) {
                        ?>
                            <tr>
                                <td><?= $artist['id'] ?></td>
                                <td><?= $artist['firstName'] ?></td>
                                <td><?= $artist['lastName'] ?></td>
                                <td><?= $artist['email'] ?></td>
                                <td><?= $artist['dateOfShow'] ?></td>
                                <td><?= $artist['artPieces'] ?></td>
                                <td><?= $artist['allArtStyles'] ?></td>
                                <td>
                                    <div class="btn-group gap-1">
                                        <a href="readArtist.php?id=<?= $artist['id'] ?>" class="btn btn-outline-secondary"><i class="bi bi-binoculars"></i></a>
                                        <a href="updateArtist.php?id=<?= $artist['id'] ?>" class="btn btn-outline-success"><i class="bi bi-pencil-square"></i></a>
                                        <a class="btn btn-outline-danger deletebtn"><i class="bi bi-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                        mysqli_close($link);
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="row mx-2 pt-3 rounded-bottom-2">
                <!-- Pagination -->
                <nav class="d-flex justify-content-center">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="artistTable.php?page=<?= $previous ?>"><i class="bi bi-chevron-double-left"></i></a>
                        </li>
                        <?php for ($i = 1; $i <= $pages; $i++) : ?>
                            <li class="page-item"><a class="page-link" href="artistTable.php?page=<?= $i ?>"><?= $i ?></a></li>
                        <?php endfor; ?>
                        <li class="page-item">
                            <a class="page-link" href="artistTable.php?page=<?= $next ?>"><i class="bi bi-chevron-double-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </main>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Delete Artist Data </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="deleteArtist.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="delete_id">
                        <h6>Do you want to delete this artist?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-color-secondary" data-bs-dismiss="modal"> NO </button>
                        <button type="submit" name="deletedata" class="btn btn-color"> Yes </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('.deletebtn').on('click', function() {
                $('#deleteModal').modal('show');

                $tr = $(this).closest('tr');
                var data = $tr.children("td").map(function() {
                    return $(this).text();
                }).get();

                console.log(data);
                $('#delete_id').val(data[0]);
            });
        });
    </script>
</body>

</html>