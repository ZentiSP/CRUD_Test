<?php

session_start();

require_once "config/db.php";

$search = '';

$stmt = $conn->query("SELECT * FROM user");
$stmt->execute();
$users = $stmt->fetchAll();

if (isset($_GET['search']) == !'') {
    echo 'Search : ' . $_GET['search'] . '';
    $search = $_GET['search'];
    $searchStmt = $conn->query("SELECT * FROM user WHERE id like '%$search%' or firstname like '%$search%' or lastname like '%$search%'");
    $searchStmt->execute();
    $users = $searchStmt->fetchAll();
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $deletestmt = $conn->query("DELETE FROM user WHERE id = $delete_id");
    $deletestmt->execute();

    if ($deletestmt) {
        echo "<script>alert('Data has been deleted successfully');</script>";
        $_SESSION['success'] = "Data has been deleted succesfully";
        header("refresh:1; url=index.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code test Website</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
        .container {
            max-width: 950px;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <!-- Modal add user -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="insert.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3 row g-2">
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="firstname" placeholder="" required>
                                    <label for="firstname" class="col-form-label">First Name</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="lastname" placeholder="" required>
                                    <label for="lastname" class="col-form-label">Last Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 form-floating">
                            <select class="form-select" id="floatingSelect" aria-label="Floating label select example"
                                name="classyear" required>
                                <option selected>Select your class year</option>
                                <option value="1">Year 1</option>
                                <option value="2">Year 2</option>
                                <option value="3">Year 3</option>
                                <option value="4">Year 4</option>
                            </select>
                            <label for="classyear" class="col-form-label">Year</label>
                        </div>
                        <div class="mb-3">
                            <label for="birthday" class="col-form-label">Brithday "mm/dd/yyyy"</label>
                            <input type="date" class="form-control" name="birthday" required>
                        </div>
                        <div class="mb-3">
                            <label for="img" class="col-form-label">Picture</label>
                            <input type="file" class="form-control" id="imginput" name="img" required>
                        </div>
                        <img width=85% id="previewimg" alt="" class="rounded mx-auto d-block mb-3">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <!-- Website header text -->
                <a href="index.php">
                    <h1>User data</h1>
                </a>
            </div>
        </div>
        <!-- Search & add user tab bar -->
        <div class="container text-center">
            <div class="row">
                <!-- Search tab -->
                <div class="col-md-6">
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" name="search" placeholder="Search"
                            value="<?php echo $search ?>" aria-label="Search">
                        <button class="btn btn-outline-primary" type="submit">Search</button>
                    </form>
                </div>
                <!-- Add user button -->
                <div class="col-md-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal"
                        data-bs-whatever="@mdo" style=" --bs-btn-padding-x: .5rem;">Add
                        User
                    </button>
                </div>
            </div>
        </div>

        <hr>
        <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </div>
        <?php } ?>
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php } ?>

        <table class="table table-bordered">
            <thead class="table-primary">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Classyear</th>
                    <th scope="col">Birthday</th>
                    <th scope="col">Image</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody class="table-light">
                <?php
                if (!$users) {
                    echo "<p><td colspan='6' class='text-center'>No data available</td></p>";
                } else {
                    foreach ($users as $user) {
                        ?>
                        <tr>
                            <th scope="row">
                                <?= $user['id']; ?>
                            </th>
                            <td>
                                <?= $user['firstname']; ?>
                            </td>
                            <td>
                                <?= $user['lastname']; ?>
                            </td>
                            <td>
                                <?= $user['classyear']; ?>
                            </td>
                            <td>
                                <?= $user['birthday']; ?>
                            </td>
                            <td width="150px"> <img width="100%" src="uploads/<?= $user['img']; ?>" </td>
                            <td>
                                <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-warning">Edit</a>
                                <a onclick="return confirm('Are you sure you want to delete?');"
                                    href="?delete=<?php echo $user['id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    </div>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script>
        let imgInput = document.getElementById('imginput');
        let previewImg = document.getElementById('previewimg');

        imgInput.onchange = evt => {
            const [file] = imgInput.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file)
            }
        }

    </script>
</body>

</html>