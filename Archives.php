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

// Query to count the total number of checked-in keys across all buildings
$query_total = "SELECT COUNT(*) as total_checked_in
                FROM `Checkin-out`
                WHERE Checked_in_out = 'Checked in'";
$result_total = mysqli_query($conn, $query_total);

// Fetch the total count
$row_total = mysqli_fetch_assoc($result_total);
$total_checked_in = $row_total['total_checked_in'];

// Query to count keys checked out per building
$query = "SELECT building, COUNT(*) as total_checked_out
          FROM `Checkin-out`
          WHERE Checked_in_out = 'Checked in'
          GROUP BY building";
$result = mysqli_query($conn, $query);

// Initialize an array to store the counts
$building_counts = array();

while ($row = mysqli_fetch_assoc($result)) {
    $building_counts[$row['building']] = $row['total_checked_out'];
}

// Query to count keys per group, excluding NULL and "Other"
$query_groups = "SELECT `group`, COUNT(*) as total_per_group
                 FROM `Checkin-out`
                 WHERE `group` IS NOT NULL AND `group` != 'Other'
                 GROUP BY `group`";
$result_groups = mysqli_query($conn, $query_groups);

// Initialize an array to store the group counts
$group_counts = array();

while ($row_groups = mysqli_fetch_assoc($result_groups)) {
    $group_counts[$row_groups['group']] = $row_groups['total_per_group'];
}
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

<style>
/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 33.33%;
  padding: 15px;
}

/* Clear floats after the columns */
.row::after {
  content: "";
  display: table;
  clear: both;
}

/* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
@media screen and (max-width:600px) {
  .column {
    width: 100%;
  }
}

h2{
    text-align: center;
}

.archivebutton{
    text-align: center;
}

</style>
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
        <a href="logout.php" class="dropbtn btn btn-danger"> Logout </a>
    </div>
</div>

<div class="topnavbar">
    <ul>
        <li><a href="home.php" class="btn btn-dark mx-2 mt-2 mb-2">Home</a></li>
        <li class="dropdown mb-2"><a href="homepage.php" class="dropbtn btn btn-dark mx-2 mt-2 mb-2">Residence</a></li>
        <li><a href="linen.php" class="dropbtn btn btn-dark mx-2 mt-2 mb-2">Linen Rentals</a></li>
        <li><a href="fan.php" class="btn btn-dark mx-2 mt-2 mb-2">Fan Rentals</a></li>
        <li><a href="Archives.php" class="btn btn-dark mx-2 mt-2 mb-2">Archives</a></li>
    </ul>
    
    <form class="" method="get">
      <div class="input-group"></div>
    </form>
</div>

<div class="row" style="overflow-x:auto; margin-top: 25px;">
    <div class="column" style="overflow-x:auto;">
        <h2> Residence Key Archives </h2>
        <div class="archivebutton"><a href="ArchivedKeys.php" class="dropbtn btn btn-danger mx-2 mt-2 mb-2">Residence Archives</a></div>
    </div>
  
    <div class="column" style="overflow-x:auto;">
        <h2> Linen Archives </h2>
        <div class="archivebutton"><a href="ArchivedLinens.php" class="dropbtn btn btn-danger mx-2 mt-2 mb-2">Linen Rental Archives</a></div>
    </div>

    
    <div class="column" style="overflow-x:auto;">
        <h2> Fan Archives </h2>
        <div class="archivebutton"><a href="ArchivedFans.php" class="dropbtn btn btn-danger mx-2 mt-2 mb-2">Fan Rental Archives</a></div>
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