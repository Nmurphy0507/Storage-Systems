<?php
include "db_conn.php";
error_reporting(E_ALL); ini_set('display_errors', 1);

if(isset($_POST['submit'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $building = mysqli_real_escape_string($conn, $_POST['building']);
    $room = mysqli_real_escape_string($conn, $_POST['room']);

    $key_number = isset($_POST['key_number']) ? mysqli_real_escape_string($conn, $_POST['key_number']) : null;
    $loaner_key = isset($_POST['loaner_key']) ? mysqli_real_escape_string($conn, $_POST['loaner_key']) : null;
    $group = isset($_POST['group']) ? mysqli_real_escape_string($conn, $_POST['group']) : null;
    $mealcard = isset($_POST['mealcard']) ? mysqli_real_escape_string($conn, $_POST['mealcard']) : null;
    $checkin_signature = isset($_POST['checkin_signature']) ? mysqli_real_escape_string($conn, $_POST['checkin_signature']) : null;
    $key_check = isset($_POST['key_check']) ? mysqli_real_escape_string($conn, $_POST['key_check']) : null;
    $key_returned = isset($_POST['key_returned']) ? mysqli_real_escape_string($conn, $_POST['key_returned']) : null;
    $mealcard_returned = isset($_POST['mealcard_returned']) ? mysqli_real_escape_string($conn, $_POST['mealcard_returned']) : null;
    $Date = isset($_POST['Date']) ? mysqli_real_escape_string($conn, $_POST['Date']) : null;
    $notes = isset($_POST['notes']) ? mysqli_real_escape_string($conn, $_POST['notes']) : null;

    $Checked_in_out = $key_check; // Use the already escaped value

    if(empty($first_name) || empty($last_name) || empty($building) || empty($room)) {
        echo "<script>alert('Please fill in Firstname, Lastname, Building, Room # before submitting')</script>";
    }

    // Insert query with optional fields
    $sql = "INSERT INTO `checkin-out` (first_name, last_name, building, room, `group`, mealcard, key_number, loaner_key, checkin_signature, Checked_in_out, key_returned, mealcard_returned, Date, notes)
            VALUES ('$first_name', '$last_name', '$building', '$room', 
                    ". ($group ? "'$group'" : "NULL") .", 
                    ". ($mealcard ? "'$mealcard'" : "NULL") .", 
                    ". ($key_number ? "'$key_number'" : "NULL") .", 
                    ". ($loaner_key ? "'$loaner_key'" : "NULL") .", 
                    ". ($checkin_signature ? "'$checkin_signature'" : "NULL") .",
                    '$Checked_in_out',
                    ". ($key_returned ? "'$key_returned'" : "NULL") .",
                    ". ($mealcard_returned ? "'$mealcard_returned'" : "NULL") .",
                    ". ($Date ? "'$Date'" : "NULL") .",
                    ". ($notes ? "'$notes'" : "NULL") .")";

    $result = mysqli_query($conn, $sql);

    if($result) {
        header("Location: homepage.php?msg=New record created successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./src/edit.css">
    <title>PHP Keysorting App</title>

    <!-- Include Signature Pad Library -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="text-center mt-5 mb-4">
            <h3>Add New Resident</h3>
            <p class="text-muted">Complete the form below to add a new resident</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:50vw; min-width:300px" onsubmit="return validateForm()">
                <div class="row mb-3">
                    <div class="col">
                        <label for="first_name" class="form-label">First Name:</label>
                        <input type="text" id="first_name" class="form-control" name="first_name" placeholder="First Name">
                    </div>
                    
                    <div class="col">
                        <label for="last_name" class="form-label">Last Name:</label>
                        <input type="text" id="last_name" class="form-control" name="last_name" placeholder="Last Name">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="group" class="form-label">Group:</label>
                    <select id="group" class="form-control" name="group">
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

                <div class="mb-3">
                    <label for="building" class="form-label">Building Name:</label>
                    <select id="building" class="form-control" name="building">
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

                <div class="mb-3">
                    <label for="room" class="form-label">Room / Bed:</label>
                    <input type="text" id="room" class="form-control" name="room" placeholder="Room / Bed #">
                </div>
                   
                <div class="row mb-3">
                    <div class="col">
                        <label for="key_number" class="form-label">Primary Key:</label>
                        <input type="text" id="key_number" class="form-control" name="key_number" placeholder="Key #">
                    </div>
                    
                    <div class="col">
                        <label for="loaner_key" class="form-label">Loaner Key:</label>
                        <input type="text" id="loaner_key" class="form-control" name="loaner_key" placeholder="Loaner Key # (if applicable)">
                    </div>

                    <div class="col">
                        <label for="mealcard" class="form-label">Mealcard:</label>
                        <input type="text" id="mealcard" class="form-control" name="mealcard" placeholder="Mealcard #">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="checkin_signature" class="form-label">Signature:</label>
                    <canvas id="signature-pad" class="signature-pad" style="border: 1px solid #000; width: 100%; height: 150px;"></canvas>
                    <input type="hidden" id="checkin_signature" name="checkin_signature">
                    <button type="button" id="clear-signature" class="btn btn-secondary mt-2">Clear Signature</button>
                </div>

                <div class="row mb-3" style="text-align: center;">
                    <div class="col">
                        <label class="form-label">Resident Checked In or Out:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="key_check" id="checked_in" value="Checked In">
                            <label class="form-check-label" for="checked_in">Checked In</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="key_check" id="checked_out" value="Checked Out">
                            <label class="form-check-label" for="checked_out">Checked Out</label>
                        </div>
                    </div>

                    <div class="col">
                        <label class="form-label">Key Returned:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="key_returned" id="key_returned_yes" value="Yes">
                            <label class="form-check-label" for="key_returned_yes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="key_returned" id="key_returned_no" value="No">
                            <label class="form-check-label" for="key_returned_no">No</label>
                        </div>
                    </div>

                    <div class="col">
                        <label class="form-label">Mealcard Returned:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="mealcard_returned" id="mealcard_returned_yes" value="Yes">
                            <label class="form-check-label" for="mealcard_returned_yes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="mealcard_returned" id="mealcard_returned_no" value="No">
                            <label class="form-check-label" for="mealcard_returned_no">No</label>
                        </div>
                    </div>
                </div>

                <script>
                    const lastSelected = {};

                    document.querySelectorAll('input[type="radio"]').forEach((radio) => {
                        radio.addEventListener('click', function() {
                            const name = this.name;
                            if (lastSelected[name] === this) {
                                this.checked = false;
                                lastSelected[name] = null;
                            } else {
                                lastSelected[name] = this;
                            }
                        });
                    });
                </script>


                <div class="form-container mb-3">
                    <label for="Date" class="form-label">Date:</label>
                    <input type="date" class="form-control" id="Date" name="Date">
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes:</label>
                    <input type="text" class="form-control" name="notes" placeholder="Enter any information about the resident. Don't forget to sign entries with your NAME and DATE the note was added.">
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success mb-5" name="submit">Save</button>
                    <a href="homepage.php" class="btn btn-secondary mb-5">Cancel</a>
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

        // Clear the canvas and set the background color to white
        function clearCanvas() {
            var ctx = canvas.getContext('2d');
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            signaturePad.clear(); // Clear the signature pad
        }

        // Clear the canvas on page load
        clearCanvas();

        document.getElementById('clear-signature').addEventListener('click', function () {
            clearCanvas();
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