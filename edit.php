<?php
    include "db_conn.php";

    if (isset($_POST['submit'])) {
        $id = $_GET['id'];
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $building = mysqli_real_escape_string($conn, $_POST['building']);
        $room = mysqli_real_escape_string($conn, $_POST['room']);
        $key_number = mysqli_real_escape_string($conn, $_POST['key_number']);
        $group = !empty($_POST['group']) ? mysqli_real_escape_string($conn, $_POST['group']) : null;
        $mealcard = !empty($_POST['mealcard']) ? mysqli_real_escape_string($conn, $_POST['mealcard']) : null;
        $checkin_signature = !empty($_POST['checkin_signature']) ? mysqli_real_escape_string($conn, $_POST['checkin_signature']) : null;
        $key_check = mysqli_real_escape_string($conn, $_POST['key_check']);
        $key_returned = mysqli_real_escape_string($conn, $_POST['key_returned']);
        $mealcard_returned = mysqli_real_escape_string($conn, $_POST['mealcard_returned']);
        $Date = mysqli_real_escape_string($conn, $_POST['Date']);
        $notes = !empty($_POST['notes']) ? mysqli_real_escape_string($conn, $_POST['notes']) : null;

        $updateFields = [];

        if (!empty($first_name)) $updateFields[] = "`first_name` = '$first_name'";
        if (!empty($last_name)) $updateFields[] = "`last_name` = '$last_name'";
        if (!empty($building)) $updateFields[] = "`building` = '$building'";
        if (!empty($room)) $updateFields[] = "`room` = '$room'";
        if (!empty($key_number)) $updateFields[] = "`key_number` = '$key_number'";
        if (!empty($group)) $updateFields[] = "`group` = '$group'";
        if (!empty($mealcard)) $updateFields[] = "`mealcard` = '$mealcard'";
        if (!empty($checkin_signature)) $updateFields[] = "`checkin_signature` = '$checkin_signature'";
        if (!empty($key_check)) $updateFields[] = "`Checked_in_out` = '$key_check'";
        if (!empty($key_returned)) $updateFields[] = "`key_returned` = '$key_returned'";
        if (!empty($mealcard_returned)) $updateFields[] = "`mealcard_returned` = '$mealcard_returned'";
        if (!empty($Date)) $updateFields[] = "`Date` = '$Date'";
        if (!empty($notes)) $updateFields[] = "`notes` = '$notes'";


        if (count($updateFields) > 0) {
            $sql = "UPDATE `checkin-out` SET " . implode(", ", $updateFields) . " WHERE `id` = $id";
            $result = mysqli_query($conn, $sql);

            if($result) {
                header("Location: homepage.php?msg=Record updated successfully");
                exit();
            } else {
                echo "Error: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="./src/edit.css">
    <!-- Include Signature Pad Library -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="text-center mt-5 mb-4">
            <h3>Edit Resident Information</h3>
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

                <div class="mb-3">
                    <label for="room" class="form-label">Room / Bed:</label>
                    <input type="text" id="room" class="form-control" name="room" value="<?php echo htmlspecialchars($row['room']); ?>">
                </div>
                    
                <div class="row mb-3">
                    <div class="col">
                        <label for="key_number" class="form-label">Key:</label>
                        <input type="text" id="key_number" class="form-control" name="key_number" value="<?php echo htmlspecialchars($row['key_number']); ?>">
                    </div>

                    <div class="col">
                        <label for="mealcard" class="form-label">Mealcard:</label>
                        <input type="text" id="mealcard" class="form-control" name="mealcard" value="<?php echo htmlspecialchars($row['mealcard'] ?? ''); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="checkin_signature" class="form-label">Signature:</label>
                    <canvas id="signature-pad" class="signature-pad" style="border: 1px solid #000; width: 100%; height: 150px;"></canvas>
                    <input type="hidden" id="checkin_signature" name="checkin_signature" value="<?php echo htmlspecialchars($row['checkin_signature']); ?>">
                    <button type="button" id="clear-signature" class="btn btn-secondary mt-2">Clear Signature</button>
                </div>

                <div class="row mb-3">
                    <div class="col">
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
                    
                    <div class="col">
                        <label class="form-label">Key Returned:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="key_returned" id="key_returned" value="Yes" <?php echo ($row['key_returned'] == 'Yes') ? "checked" : ""; ?>>
                            <label class="form-check-label" for="key_returned">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="key_returned" id="key_returned" value="No" <?php echo ($row['key_returned'] == 'No') ? "checked" : ""; ?>>
                            <label class="form-check-label" for="key_returned">No</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="key_returned" id="key_returned" value="Not Issued" <?php echo ($row['key_returned'] == 'Not Issued') ? "checked" : ""; ?>>
                            <label class="form-check-label" for="key_returned">Not Issued</label>
                        </div>
                    </div>

                    <div class="col">
                        <label class="form-label">Mealcard Returned:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="mealcard_returned" id="mealcard_returned" value="Yes" <?php echo ($row['mealcard_returned'] == 'Yes') ? "checked" : ""; ?>>
                            <label class="form-check-label" for="mealcard_returned">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="mealcard_returned" id="mealcard_returned" value="No" <?php echo ($row['key_returned'] == 'No') ? "checked" : ""; ?>>
                            <label class="form-check-label" for="mealcard_returned">No</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="mealcard_returned" id="mealcard_returned" value="Not Issued" <?php echo ($row['key_returned'] == 'Not Issued') ? "checked" : ""; ?>>
                            <label class="form-check-label" for="mealcard_returned">Not Issued</label>
                        </div>
                    </div>
                </div>

                <div class="form-container mb-3">
                    <label for="Date" class="form-label">Date:</label>
                    <input type="date" class="form-control" id="Date" name="Date" value="<?php echo htmlspecialchars($row['Date'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes:</label>
                    <input type="text" class="form-control" id="notes" name="notes" value="<?php echo htmlspecialchars($row['notes'] ?? ''); ?>" placeholder="Enter any information about the resident. Dont forget to sign entries with your NAME and DATE the note was added.">
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success" name="submit">Update</button>
                    <a href="homepage.php" class="btn btn-secondary">Cancel</a>
                </div>

                <div class="delete-button-container">
                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirmDeletion();">Delete Resident</a>
                </div>
                
                </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    
    <script>
    function confirmDeletion() {
        return confirm("Are you sure you want to delete this record?");
    }

    function validateForm() {
        var firstName = document.getElementById("first_name").value.trim();
        var lastName = document.getElementById("last_name").value.trim();
        var building = document.getElementById("building").value.trim();
        var room = document.getElementById("room").value.trim();

        if (firstName === "" || lastName === "" || building === "" || room === "") {
            alert("Please fill in Firstname, Lastname, Building, Room # before submitting");
            return false;
        }
        return true;
    }

    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)' // Set background color to white
    });

    // Set canvas dimensions explicitly
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;

    var existingSignature = document.getElementById('checkin_signature').value;
    if (existingSignature) {
        var img = new Image();
        img.src = existingSignature;
        img.onload = function () {
            // Clear the canvas before redrawing
            signaturePad.clear();
            
            // Draw the existing signature with the correct dimensions
            var ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        };
    }

    document.getElementById('clear-signature').addEventListener('click', function () {
        signaturePad.clear();
        document.getElementById('checkin_signature').value = '';
    });

    document.querySelector('form').addEventListener('submit', function (event) {
        if (!signaturePad.isEmpty()) {
            var signatureData = signaturePad.toDataURL();
            document.getElementById('checkin_signature').value = signatureData;
        }
    });
</script>
    
</body>
</html>