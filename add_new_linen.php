<?php
include "db_conn.php";
error_reporting(E_ALL); 
ini_set('display_errors', 1);

if (isset($_POST['submit'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $building = mysqli_real_escape_string($conn, $_POST['building']);
    $room = mysqli_real_escape_string($conn, $_POST['room']);
    $group = !empty($_POST['group']) ? mysqli_real_escape_string($conn, $_POST['group']) : null;
    $linen_type = !empty($_POST['linen_type']) ? mysqli_real_escape_string($conn, $_POST['linen_type']) : null;
    $linen_quantity = !empty($_POST['linen_quantity']) ? mysqli_real_escape_string($conn, $_POST['linen_quantity']) : null;
    $linen_cost = !empty($_POST['linen_cost']) ? mysqli_real_escape_string($conn, $_POST['linen_cost']) : null;
    $linen_date_rented = !empty($_POST['linen_date_rented']) ? mysqli_real_escape_string($conn, $_POST['linen_date_rented']) : null;
    $linen_date_returned = !empty($_POST['linen_date_returned']) ? mysqli_real_escape_string($conn, $_POST['linen_date_returned']) : null;

    // Step 1: Check if the resident already exists in the `checkin-out` table
    $sql_check_resident = "SELECT id FROM `checkin-out` 
                           WHERE first_name = '$first_name' 
                           AND last_name = '$last_name' 
                           AND building = '$building' 
                           AND room = '$room'";
    $result_check_resident = mysqli_query($conn, $sql_check_resident);

    if (mysqli_num_rows($result_check_resident) > 0) {
        // Resident exists, use their ID as the resident_id
        $resident_row = mysqli_fetch_assoc($result_check_resident);
        $resident_id = $resident_row['id'];
    } else {
        // Step 2: Resident does not exist, create a new resident record
        $sql_insert_resident = "INSERT INTO `checkin-out` (`first_name`, `last_name`, `building`, `room`, `group`)
                               VALUES ('$first_name', '$last_name', '$building', '$room', " . ($group ? "'$group'" : "NULL") . ")";
        mysqli_query($conn, $sql_insert_resident);
        $resident_id = mysqli_insert_id($conn); // Get the newly created resident ID
    }

    // Step 3: Insert the new linen record with the resident_id
    $sql_insert_linen = "INSERT INTO `linens` (`resident_id`, `first_name`, `last_name`, `building`, `room`, `group`, 
                                                `linen_type`, `linen_quantity`, `linen_cost`, `linen_date_rented`, `linen_date_returned`)
                         VALUES ($resident_id, '$first_name', '$last_name', '$building', '$room', 
                                 " . ($group ? "'$group'" : "NULL") . ", 
                                 " . ($linen_type ? "'$linen_type'" : "NULL") . ", 
                                 " . ($linen_quantity ? "'$linen_quantity'" : "NULL") . ", 
                                 " . ($linen_cost ? "'$linen_cost'" : "NULL") . ", 
                                 " . ($linen_date_rented ? "'$linen_date_rented'" : "NULL") . ", 
                                 " . ($linen_date_returned ? "'$linen_date_returned'" : "NULL") . ")";
    $result_insert_linen = mysqli_query($conn, $sql_insert_linen);

    if ($result_insert_linen) {
        header("Location: linen.php?msg=Record added successfully");
        exit();
    } else {
        echo "Error inserting into linens table: " . mysqli_error($conn);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./src/edit.css">
</head>

<body>
    <div class="container">
        <div class="text-center mt-5 mb-4">
            <h3>Add New Linen Information</h3>
            <p class="text-muted">Click update after changing any information</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:50vw; min-width:300px" onsubmit="return validateForm()">
                <div class="row mb-3">
                    <div class="col">
                        <label for="first_name" class="form-label">First Name:</label>
                        <input type="text" id="first_name" class="form-control" name="first_name">
                    </div>
                    
                    <div class="col">
                        <label for="last_name" class="form-label">Last Name:</label>
                        <input type="text" id="last_name" class="form-control" name="last_name">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="group" class="form-label">Group:</label>
                        <select id="group" class="form-control" name="group">
                            <option selected hidden>Select a Group</option>
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

                    <div class="col">
                        <label for="building" class="form-label">Building Name:</label>
                        <select id="building" class="form-control" name="building">
                            <option selected hidden>Select a Hall</option>
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
                            <option>Edgewood</option>
                        </select>
                    </div>

                    <div class="col">
                        <label for="room" class="form-label">Room / Bed:</label>
                        <input type="text" id="room" class="form-control" name="room">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="linen_type" class="form-label">Linen Type:</label>
                        <input type="text" class="form-control" id="linen_type" name="linen_type">
                    </div>

                    <div class="col">
                        <label for="linen_quantity" class="form-label">Linen Quantity:</label>
                        <input type="text" class="form-control" id="linen_quantity" name="linen_quantity">
                    </div>

                    <div class="col">
                        <label for="linen_cost" class="form-label">Linen Cost:</label>
                        <input type="text" class="form-control" id="linen_cost" name="linen_cost">
                    </div>
                </div>

                <div class="form-container mb-3">
                    <label for="linen_date_rented" class="form-label">Date Rented:</label>
                    <input type="date" class="form-control" id="linen_date_rented" name="linen_date_rented">
                </div>

                <div class="form-container mb-3">
                    <label for="linen_date_returned" class="form-label">Date Returned:</label>
                    <input type="date" class="form-control" id="linen_date_returned" name="linen_date_returned">
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success" name="submit">Add New</button>
                    <a href="linen.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    
    <script>
    function validateForm() {
        var firstName = document.getElementById("first_name").value.trim();
        var lastName = document.getElementById("last_name").value.trim();
        var building = document.getElementById("building").value.trim();
        var room = document.getElementById("room").value.trim();

        if(firstName === "" || lastName === "" || building === "" || room === "") {
            alert("Please fill in Firstname, Lastname, Building, Room # before submitting");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>