<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM user WHERE id = {$_SESSION['user_id']}";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
}

include "db_conn.php";
error_reporting(E_ALL); ini_set('display_errors', 1);

if (isset($_POST['submit'])) {
    if (isset($_POST['first_name']) && is_array($_POST['first_name'])) {
        // Loop through the rows
        foreach ($_POST['first_name'] as $index => $first_name) {
            $last_name = $_POST['last_name'][$index];
            $building = $_POST['building'][$index];
            $room = $_POST['room'][$index];

            // Optional fields
            $key_number = isset($_POST['key_number'][$index]) ? $_POST['key_number'][$index] : null;
            $group = isset($_POST['group'][$index]) ? $_POST['group'][$index] : null;
            $mealcard = isset($_POST['mealcard'][$index]) ? $_POST['mealcard'][$index] : null;
            $checkin_signature = isset($_POST['checkin_signature'][$index]) ? $_POST['checkin_signature'][$index] : null;
            $key_check = isset($_POST['key_check'][$index]) ? $_POST['key_check'][$index] : null;
            $key_returned = isset($_POST['key_returned'][$index]) ? $_POST['key_returned'][$index] : null;
            $mealcard_returned = isset($_POST['mealcard_returned'][$index]) ? $_POST['mealcard_returned'][$index] : null;
            $Date = isset($_POST['Date'][$index]) ? $_POST['Date'][$index] : null;
            $notes = isset($_POST['notes'][$index]) ? $_POST['notes'][$index] : null;

            // Ensure key_check is selected
            $Checked_in_out = isset($_POST['key_check'][$index]) ? $_POST['key_check'][$index] : null;

            // Ensure required fields are not empty
            if (empty($first_name) || empty($last_name) || empty($building) || empty($room)) {
                echo "<script>alert('Please fill in Firstname, Lastname, Building, Room # before submitting')</script>";
                continue;
            }

            // Insert query with optional fields
            $sql = "INSERT INTO `checkin-out` (first_name, last_name, building, room, `group`, mealcard, key_number, checkin_signature, Checked_in_out, key_returned, mealcard_returned, Date, notes)
                    VALUES ('$first_name', '$last_name', '$building', '$room',
                            " . ($group ? "'$group'" : "NULL") . ",
                            " . ($mealcard ? "'$mealcard'" : "NULL") . ",
                            " . ($key_number ? "'$key_number'" : "NULL") . ",
                            " . ($checkin_signature ? "'$checkin_signature'" : "NULL") . ",
                            '$Checked_in_out',
                            " . ($key_returned ? "'$key_returned'" : "NULL") . ",
                            " . ($mealcard_returned ? "'$mealcard_returned'" : "NULL") . ",
                            " . ($Date ? "'$Date'" : "NULL") . ",
                            " . ($notes ? "'$notes'" : "NULL") . ")";

            $result = mysqli_query($conn, $sql);

            if (!$result) {
                echo "Error: " . mysqli_error($conn);
            }
        }
        header("Location: homepage.php?msg=New records created successfully");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>PHP Keysorting App</title>

    <link rel="stylesheet" href="homepage.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

<div class="header">
    <div>
     <a href="https://www.frostburg.edu/">
        <img alt="Frostburg State University" style="transform: translate(20px,20px);" src="src/FSU-logo.png">
        </a>
        <?php if (isset($user)): ?>
        <h2 class="mt-2 mx-2" style="float:right"> Welcome, <?= htmlspecialchars($user["name"])?></h2>
        <?php endif; ?>
    </div>
        
    <div class="logout_button">
        <a class="dropbtn btn btn-secondary"> <i class="fa-solid fa-user"></i> Account </a>
        <a href="logout.php" class="dropbtn btn btn-danger"> Logout </a>
    </div>
</div>

<div class="container">
    <?php
    if (isset($_GET['msg'])) {
        $msg = $_GET['msg'];
        echo
        '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            '.$msg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
    ?>
</div>

<div class="topnavbar">
    <ul>
        <li><a href="home.php" class="btn btn-dark mx-2 mt-2 mb-2">Home</a></li>
        <li><a href="homepage.php" class="dropbtn btn btn-dark mx-2 mt-2 mb-2">Residence</a></li>
        <li><a href="linen.php" class="dropbtn btn btn-dark mx-2 mt-2 mb-2">Linen Rentals</a></li>
        <li><a href="fan.php" class="btn btn-dark mx-2 mt-2 mb-2">Fan Rentals</a></li>
        <li><a href="Archives.php" class="btn btn-dark mx-2 mt-2 mb-2">Archives</a></li>
    </ul>
    
    <form class="" method="get">
      <div class="input-group"></div>
    </form>
</div>

<div class="column middle my-4 mx-5">
<div class="card mt-5">
        <div class="card-header">
            <h4>Fill in the Table and Submit</h4>
        </div>
        <div class="card-body">
            <form id="table-form" method="POST" onsubmit="return validateForm()">
                <table class="table table-bordered table-light text-center">
                    <thead>
                        <tr>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Group</th>
                            <th scope="col">Building</th>
                            <th scope="col">Room</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <tr>
                            <td>
                                <div>
                                    <input type="text" name="first_name[]" class="form-control" placeholder="First Name" required>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="text" name="last_name[]" class="form-control" placeholder="Last Name" required>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <select name="group[]" class="form-control" required>
                                        <option select hidden>Select a Group</option>
                                        <option>MD All-State</option>
                                        <option>Arlington Soccer</option>
                                        <option>Brit-AM</option>
                                        <option>Camp Hope</option>
                                        <option>LUC Staff</option>
                                        <option>Res Life Staff</option>
                                        <option>FSY Week 1</option>
                                        <option>FSY Week 2</option>
                                        <option>FSY Week 3</option>
                                        <option>Wooten Session 1</option>
                                        <option>Wooten Session 2</option>
                                        <option>Wooten Session 3</option>
                                        <option>Wooten Session 4</option>
                                        <option>Wooten Session 5</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <select name="building[]" class="form-control" required>
                                        <option select hidden>Select a Hall</option>
                                        <option>Allen</option>
                                        <option>Annapolis</option>
                                        <option>Cumberland</option>
                                        <option>Diehl</option>
                                        <option>Frederick</option>
                                        <option>Frost</option>
                                        <option>Gray</option>
                                        <option>Simpson</option>
                                        <option>Sowers</option>
                                        <option>Westminster</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" name="room[]" class="form-control" placeholder="Room / Bed #" required>
                                </div>
                            </td>
                            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                        </tr>
                    </tbody>
                </table>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="button" id="add-row" class="btn btn-success mx-3">Add Row</button>
                </div>
                <div style="justify: text-center;">
                    <button type="submit" class="btn btn-primary" name="submit">Save All To Database</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-2">
        <div class="card-header"><h4> Import Keys into Database <h4></div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="file" name="import_file" class="form-control" accept=".xlsx">
                <button class="btn btn-primary mt-3"> Import xlsx file</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybU5p2Uj1d1iz1pSA9Wdnlrp9bJhFsD7/TfZp7xYfuN0KSf4I" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<script>
    document.getElementById('add-row').addEventListener('click', function() {
        const tableBody = document.getElementById('table-body');
        const newRow = `
            <tr>
                <td>
                    <div>
                        <input type="text" name="first_name[]" class="form-control" placeholder="First Name" required>
                    </div>
                </td>
                <td>
                    <div>
                        <input type="text" name="last_name[]" class="form-control" placeholder="Last Name" required>
                    </div>
                </td>
                <td>
                    <div class="mb-1">
                        <select name="group[]" class="form-control" required>
                            <option select hidden>Select a Group</option>
                            <option>MD All-State</option>
                            <option>Arlington Soccer</option>
                            <option>Brit-AM</option>
                            <option>Camp Hope</option>
                            <option>LUC Staff</option>
                            <option>Res Life Staff</option>
                            <option>FSY Week 1</option>
                            <option>FSY Week 2</option>
                            <option>FSY Week 3</option>
                            <option>Wooten Session 1</option>
                            <option>Wooten Session 2</option>
                            <option>Wooten Session 3</option>
                            <option>Wooten Session 4</option>
                            <option>Wooten Session 5</option>
                            <option>Other</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="mb-1">
                        <select name="building[]" class="form-control" required>
                            <option select hidden>Select a Hall</option>
                            <option>Allen</option>
                            <option>Annapolis</option>
                            <option>Cumberland</option>
                            <option>Diehl</option>
                            <option>Frederick</option>
                            <option>Frost</option>
                            <option>Gray</option>
                            <option>Simpson</option>
                            <option>Sowers</option>
                            <option>Westminster</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="mb-1">
                        <input type="text" name="room[]" class="form-control" placeholder="Room / Bed #" required>
                    </div>
                </td>
                <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', newRow);
    });

    document.getElementById('table-body').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    function validateForm() {
        var firstName = document.getElementsByName("first_name[]");
        var lastName = document.getElementsByName("last_name[]");
        var group = document.getElementsByName("group[]");
        var building = document.getElementsByName("building[]");
        var room = document.getElementsByName("room[]");

        for (var i = 0; i < firstName.length; i++) {
            if (firstName[i].value.trim() === "" || lastName[i].value.trim() === "" || group[i].value.trim() === "" || building[i].value.trim() === "" || room[i].value.trim() === "") {
                alert("Please fill in Firstname, Lastname, Group, Building, Room # before submitting");
                return false;
            }
        }
        return true;
    }
</script>

<div class="footer">
    <p>Website Created by - Nathan Murphy <i class="fa fa-phone" style="font-size:12px"> 240-457-3326 </i> <i class="fa fa-envelope" style="font-size:12px"></i> Nathanmurphy0507@gmail.com </p>
</div>

</body>
</html>