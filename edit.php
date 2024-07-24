<?php
    include "db_conn.php";

    if(isset($_POST['submit'])) {
        $id = $_GET['id'];
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $building = mysqli_real_escape_string($conn, $_POST['building']);
        $room = mysqli_real_escape_string($conn, $_POST['room']);
        $key_number = mysqli_real_escape_string($conn, $_POST['key_number']);
        $floor = mysqli_real_escape_string($conn, $_POST['floor']);
        $barcode = mysqli_real_escape_string($conn, $_POST['barcode']);
        $checkin_signature = mysqli_real_escape_string($conn, $_POST['checkin_signature']);
        $key_check = mysqli_real_escape_string($conn, $_POST['key_check']);
        $Date = mysqli_real_escape_string($conn, $_POST['Date']);

        if(empty($first_name) || empty($last_name) || empty($building) || empty($room) || empty($key_number) || empty($floor) || empty($barcode) || empty($checkin_signature) || empty($key_check) || empty($Date)) {
            echo "<script>alert('Please fill in all fields before updating.')</script>";
        } else {
            $Checked_in_out = ($key_check == 'Checked In') ? 'Checked In' : 'Checked Out';

            $sql = "UPDATE `checkin-out` SET 
                    `first_name` = '$first_name', 
                    `last_name` = '$last_name', 
                    `building` = '$building', 
                    `room` = '$room', 
                    `floor` = '$floor', 
                    `barcode` = '$barcode', 
                    `key_number` = '$key_number', 
                    `checkin_signature` = '$checkin_signature', 
                    `Checked_in_out` = '$Checked_in_out',
                    `Date` = '$Date'
                    WHERE `id` = $id";

            $result = mysqli_query($conn, $sql);

            if($result) {
                header("Location: index.php?msg=Record updated successfully");
                exit();
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        }
    }

    $id = $_GET['id'];
    $sql = "SELECT * FROM `checkin-out` WHERE id = $id LIMIT 1";
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
</head>
<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #ff0000">
        Residence Hall Keys
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3>Edit User Information</h3>
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

                <div class="mb-3">
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
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="room" class="form-label">Room / Bed:</label>
                        <input type="text" id="room" class="form-control" name="room" value="<?php echo htmlspecialchars($row['room']); ?>">
                    </div>
                    
                    <div class="col">
                        <label for="key_number" class="form-label">Key:</label>
                        <input type="text" id="key_number" class="form-control" name="key_number" value="<?php echo htmlspecialchars($row['key_number']); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="floor" class="form-label">Floor:</label>
                    <input type="text" id="floor" class="form-control" name="floor" value="<?php echo htmlspecialchars($row['floor']); ?>">
                </div>

                <div class="mb-3">
                    <label for="barcode" class="form-label">Barcode:</label>
                    <input type="text" id="barcode" class="form-control" name="barcode" value="<?php echo htmlspecialchars($row['barcode']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Signature:</label>
                    <input type="text" class="form-control" name="checkin_signature" value="<?php echo htmlspecialchars($row['checkin_signature']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Key Checked In or Out:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="key_check" id="checked_in" value="Checked In" <?php echo ($row['Checked_in_out'] == 'Checked In') ? "checked" : ""; ?>>
                        <label class="form-check-label" for="checked_in">Checked In</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="key_check" id="checked_out" value="Checked Out" <?php echo ($row['Checked_in_out'] == 'Checked Out') ? "checked" : ""; ?>>
                        <label class="form-check-label" for="checked_out">Checked Out</label>
                    </div>
                </div>

                <div class="form-container mb-3">
                    <label for="Date" class="form-label">Date:</label>
                    <input type="date" class="form-control" id="Date" name="Date" value="<?php echo htmlspecialchars($row['Date']); ?>">
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success" name="submit">Update</button>
                    <a href="index.php" class="btn btn-danger">Cancel</a>
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
            var keyNumber = document.getElementById("key_number").value.trim();
            var floor = document.getElementById("floor").value.trim();
            var barcode = document.getElementById("barcode").value.trim();
            var signature = document.querySelector("input[name=checkin_signature]").value.trim();
            var keyChecked = document.querySelector("input[name=key_check]:checked");
            var Date = document.getElementById("Date").value.trim();

            if(firstName === "" || lastName === "" || building === "" || room === "" || keyNumber === "" || floor === "" || barcode === "" || signature === "" || keyChecked === null || Date === "") {
                alert("Please fill in all fields before updating.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>