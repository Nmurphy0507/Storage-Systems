<?php
include "db_conn.php";

if (isset($_POST['submit'])) {
    $id = $_GET['id'];
    $first_name = !empty($_POST['first_name']) ? "'" . mysqli_real_escape_string($conn, $_POST['first_name']) . "'" : "NULL";
    $last_name = !empty($_POST['last_name']) ? "'" . mysqli_real_escape_string($conn, $_POST['last_name']) . "'" : "NULL";
    $building = !empty($_POST['building']) ? "'" . mysqli_real_escape_string($conn, $_POST['building']) . "'" : "NULL";
    $room = !empty($_POST['room']) ? "'" . mysqli_real_escape_string($conn, $_POST['room']) . "'" : "NULL";
    $group = !empty($_POST['group']) ? "'" . mysqli_real_escape_string($conn, $_POST['group']) . "'" : "NULL";
    $fan_quantity = !empty($_POST['fan_quantity']) ? "'" . mysqli_real_escape_string($conn, $_POST['fan_quantity']) . "'" : "NULL";
    $fan_cost = !empty($_POST['fan_cost']) ? "'" . mysqli_real_escape_string($conn, $_POST['fan_cost']) . "'" : "NULL";
    $fan_date_rented = !empty($_POST['fan_date_rented']) ? "'" . mysqli_real_escape_string($conn, $_POST['fan_date_rented']) . "'" : "NULL";
    $fan_date_returned = !empty($_POST['fan_date_returned']) ? "'" . mysqli_real_escape_string($conn, $_POST['fan_date_returned']) . "'" : "NULL";

    $sql = "SELECT * FROM `fan` WHERE `resident_id` = $id OR `id` = $id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $updatefanFields = [];
        if (!is_null($first_name)) $updatefanFields[] = "`first_name` = $first_name";
        if (!is_null($last_name)) $updatefanFields[] = "`last_name` = $last_name";
        if (!is_null($building)) $updatefanFields[] = "`building` = $building";
        if (!is_null($room)) $updatefanFields[] = "`room` = $room";
        if (!is_null(value: $group)) $updatefanFields[] = "`group` = $group";
        if (!is_null($fan_quantity)) $updatefanFields[] = "`fan_quantity` = $fan_quantity";
        if (!is_null($fan_cost)) $updatefanFields[] = "`fan_cost` = $fan_cost";
        if (!is_null($fan_date_rented)) $updatefanFields[] = "`fan_date_rented` = $fan_date_rented";
        if (!is_null($fan_date_returned)) $updatefanFields[] = "`fan_date_returned` = $fan_date_returned";

        if (count($updatefanFields) > 0) {
            $sql = "UPDATE `fan` SET " . implode(", ", $updatefanFields) . " WHERE `resident_id` = $id OR `id` = $id";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                header("Location: fan.php?msg=Record updated successfully");
                exit();
            } else {
                echo "Error updating fan table: " . mysqli_error($conn);
            }
        }
    } else {
        $sql = "INSERT INTO `fan` (`resident_id`, `first_name`, `last_name`, `building`, `room`, `group`, `fan_quantity`, `fan_cost`, `fan_date_rented`, `fan_date_returned`)
                VALUES ($id, $first_name, $last_name, $building, $room, $group, $fan_quantity, $fan_cost, $fan_date_rented, $fan_date_returned)";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            header("Location: fan.php?msg=Record added successfully");
            exit();
        } else {
            echo "Error inserting into fan table: " . mysqli_error($conn);
        }
    }
}

$id = $_GET['id'];
$sql = "SELECT co.first_name, co.last_name, co.building, co.room, co.`group`, f.fan_quantity, f.fan_cost, f.fan_date_rented, f.fan_date_returned
        FROM `checkin-out` co
        LEFT JOIN `fan` f ON co.id = f.resident_id OR f.id = '$id'
        WHERE co.id = '$id' LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
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
            <h3>Edit Fan Information</h3>
            <p class="text-muted">Click update after changing any information</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:50vw; min-width:300px" onsubmit="return validateForm()">
                <div class="row mb-3">
                    <div class="col">
                        <label for="first_name" class="form-label">First Name:</label>
                        <input type="text" id="first_name" class="form-control" name="first_name" value="<?php echo htmlspecialchars($row['first_name']); ?>">
                    </div>
                    
                    <div class="col">
                        <label for="last_name" class="form-label">Last Name:</label>
                        <input type="text" id="last_name" class="form-control" name="last_name" value="<?php echo htmlspecialchars($row['last_name']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="group" class="form-label">Group:</label>
                        <select id="group" class="form-control" name="group">
                        <option select hidden><?php echo htmlspecialchars($row['group']); ?></option>
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
                        <option select hidden><?php echo htmlspecialchars($row['building']); ?></option>
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
                        <input type="text" id="room" class="form-control" name="room" value="<?php echo htmlspecialchars($row['room']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="fan_quantity" class="form-label">Fan Quantity:</label>
                        <input type="text" class="form-control" id="fan_quantity" name="fan_quantity" value="<?php echo htmlspecialchars($row['fan_quantity'] ?? ''); ?>">
                    </div>

                    <div class="col">
                        <label for="fan_cost" class="form-label">Fan Cost:</label>
                        <input type="text" class="form-control" id="fan_cost" name="fan_cost" value="<?php echo htmlspecialchars($row['fan_cost'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-container mb-3">
                    <label for="fan_date_rented" class="form-label">Date Rented:</label>
                    <input type="date" class="form-control" id="fan_date_rented" name="fan_date_rented" value="<?php echo htmlspecialchars($row['fan_date_rented'] ?? ''); ?>">
                </div>

                <div class="form-container mb-3">
                    <label for="fan_date_returned" class="form-label">Date Returned:</label>
                    <input type="date" class="form-control" id="fan_date_returned" name="fan_date_returned" value="<?php echo htmlspecialchars($row['fan_date_returned'] ?? ''); ?>">
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success" name="submit">Update</button>
                    <a href="fan.php" class="btn btn-secondary">Cancel</a>
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