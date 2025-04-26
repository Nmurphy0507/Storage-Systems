<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";

    $stmt = $mysqli->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

include "db_conn.php";

// Get the current date
$currentDate = date('Y-m-d');

// Calculate the date 2 days ago
$twoDaysAgo = date('Y-m-d', strtotime('-2 days'));

// Select records where key is checked out for more than 2 days
$sql = "SELECT * FROM `checkin-out` WHERE `Checked_in_out` = 'Checked Out' AND `Date` <= '$twoDaysAgo'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $first_name = mysqli_real_escape_string($conn, $row['first_name']);
        $last_name = mysqli_real_escape_string($conn, $row['last_name']);
        $building = mysqli_real_escape_string($conn, $row['building']);
        $room = mysqli_real_escape_string($conn, $row['room']);
        $key_number = mysqli_real_escape_string($conn, $row['key_number']);
        $loaner_key = mysqli_real_escape_string($conn, $row['loaner_key']);
        $group = mysqli_real_escape_string($conn, $row['group']);
        $mealcard = mysqli_real_escape_string($conn, $row['mealcard']);
        $checkin_signature = mysqli_real_escape_string($conn, $row['checkin_signature']);
        $checked_in_out = mysqli_real_escape_string($conn, string: $row['Checked_in_out']);
        $key_returned = mysqli_real_escape_string($conn, $row['key_returned']);
        $mealcard_returned = mysqli_real_escape_string($conn, $row['mealcard_returned']);
        $date = mysqli_real_escape_string($conn, $row['Date']);
        $notes = mysqli_real_escape_string($conn, $row['notes']);
        
        // Insert into ArchivedKeys table
        $archiveSql = "INSERT INTO ArchivedKeys (`first_name`, `last_name`, `building`, `room`, `key_number`, `loaner_key`, `group`, `mealcard`, `checkin_signature`, `Checked_in_out`, `key_returned`, `mealcard_returned`, `Date`, `notes`)
                        VALUES ('$first_name', '$last_name', '$building', '$room', '$key_number', '$loaner_key', '$group', '$mealcard', '$checkin_signature', '$checked_in_out', '$key_returned', '$mealcard_returned', '$date', '$notes')";
        
        
        // Run the insert query
        if (mysqli_query($conn, $archiveSql)) {
            // After inserting, delete the record from the main table
            $deleteSql = "DELETE FROM `checkin-out` WHERE `id` = {$row['id']}";
            mysqli_query($conn, $deleteSql);
        }
    }
}

$search = '';

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM `ArchivedKeys` WHERE
            first_name LIKE '%$search%' OR
            last_name LIKE '%$search%' OR
            building LIKE '%$search%' OR
            room LIKE '%$search%' OR
            key_number LIKE '%$search%' OR
            'group' LIKE '%$search%' OR
            mealcard LIKE '%$search%' OR
            checkin_signature LIKE '%$search%' OR
            Checked_in_out LIKE '%$search%' OR
            Date LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM `ArchivedKeys`";
}

function highlight_search_result($text, $search) {
    if (!empty($search)) {
        $text = preg_replace("/($search)/i", "<strong>$1</strong>", $text);
    }
    return $text;
}

$archiveQuery = "SELECT * FROM `ArchivedKeys`";
$ArchivedResult = mysqli_query($conn, $archiveQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>PHP Keysorting App</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="homepage.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    

    <!-- Font Awesome CSS -->
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

<div class="topnavbar">
    <ul>
        <li><a href="home.php" class="btn btn-dark mx-2 mt-2 mb-2">Home</a></li>
        <li><a href="homepage.php" class="dropbtn btn btn-dark mx-2 mt-2 mb-2">Residence</a></li>
        <li><a href="linen.php" class="dropbtn btn btn-dark mx-2 mt-2 mb-2">Linen Rentals</a></li>
        <li><a href="fan.php" class="btn btn-dark mx-2 mt-2 mb-2">Fan Rentals</a></li>
        <li><a href="Archives.php" class="btn btn-dark mx-2 mt-2 mb-2">Archives</a></li>

        <form method="get">
            <div class="input-group">
                <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                <input type="hidden" name="building" value="<?= htmlspecialchars($building) ?>">
                <input type="hidden" name="group" value="<?= htmlspecialchars($group) ?>">
                <input type="text" class="form-control" placeholder="Search..." id="searchInput" name="search" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search)): ?>
                    <a href="<?= $_SERVER['PHP_SELF'] . '?filter=' . urlencode($filter) . '&building=' . urlencode($building) . '&group=' . urlencode($group) ?>" class="btn btn-danger ms-2">Clear Search</a>
                    <?php endif; ?>
            </div>
        </form>
    </ul>
</div>

<!-- Archived Keys Section -->
<div class="column middle mt-4">
    <h4>Archived Residence</h4>
    <table class="table table-hover text-center">

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

        <thead class="table-dark">
            <tr>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Group</th>
                <th scope="col">Building</th>
                <th scope="col">Room / Bed</th>
                <th scope="col">Key</th>
                <th scope="col">Mealcard</th>
                <th scope="col">Signature</th>
                <th scope="col">Checked In or Out</th>
                <th scope="col">Date</th>
                <th scope="col">Archived Date</th>
                <th scope="col">Notes</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody style="background-color:#ffffff">
            <?php while ($row = mysqli_fetch_assoc($ArchivedResult)): ?>
                <?php if ($ArchivedResult && mysqli_num_rows($ArchivedResult) > 0): ?>
                <tr>
                <td><?php echo highlight_search_result($row['first_name'], $search); ?></td>
                <td><?php echo highlight_search_result($row['last_name'], $search); ?></td>
                <td><?php echo highlight_search_result($row['group'], $search); ?></td>
                <td><?php echo highlight_search_result($row['building'], $search); ?></td>
                <td><?php echo highlight_search_result($row['room'], $search); ?></td>
                <td><?php echo highlight_search_result($row['key_number'], $search); ?></td>
                <td><?php echo highlight_search_result($row['mealcard'], $search); ?></td>
                <td><?php echo '<img src="'.$row['checkin_signature'].'" alt="Signature" style="max-width: 100px; max-height: 30px;" />'; ?></td>
                <td><?php echo highlight_search_result($row['Checked_in_out'], $search); ?></td>
                <td><?php echo highlight_search_result($row['Date'], $search); ?></td>
                <td><?php echo highlight_search_result($row['ArchivedDate'], $search); ?></td>
                <td>
                    <div class="truncate-text" title="<?= htmlspecialchars($row['notes']) ?>"> </div>
                        <?php if (strlen($row['notes']) > 1):?>
                            <a data-bs-toggle="modal" data-bs-target="#noteModal<?= $row['id'] ?>">Read Note</a>
                        <?php endif; ?>
                        <div class="modal fade" id="noteModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="noteModalLabel<?= $row['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="noteModalLabel<?= $row['id'] ?>">Full Note</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?= nl2br(htmlspecialchars($row['notes'])) ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><a href="restore.php?id=<?php echo $row['id']; ?>" class="link-dark"><i class="fa-solid fa-undo fs-5 me-3"></i></a></td>
                </tr>
                <?php endif; ?>
            <?php endwhile; ?>
        </tbody>
    </table>
    <form method="post" action="excel.php">
            <input type="submit" name="export_excel" style="float:right" class="btn btn-success " value="Export to Excel">
    </form>
</div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybU5p2Uj1d1iz1pSA9Wdnlrp9bJhFsD7/TfZp7xYfuN0KSf4I" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <!-- Custom JS -->
    <script src=""></script>

    <div class="footer">
        <p>Website Created by - Nathan Murphy <i class="fa fa-phone" style="font-size:12px"> 240-457-3326 </i> <i class="fa fa-envelope" style="font-size:12px"></i> Nathanmurphy0507@gmail.com </p>
    </div>
    
</body>
</html>