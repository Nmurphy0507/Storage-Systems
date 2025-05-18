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

// Query to count the total number of fans checked out based on quantity
$query_fans = "SELECT SUM(fan_quantity) AS total_fans_out FROM fan WHERE fan_date_returned IS NULL";
$result_fans = mysqli_query($conn, $query_fans);
$row_fans = mysqli_fetch_assoc($result_fans);
$total_fans_out = $row_fans['total_fans_out'] ?? 0;

// Query to count the total number of linens checked out based on quantity
$query_linens = "SELECT SUM(linen_quantity) AS total_linens_out FROM linens WHERE linen_date_returned IS NULL";
$result_linens = mysqli_query($conn, $query_linens);
$row_linens = mysqli_fetch_assoc($result_linens);
$total_linens_out = $row_linens['total_linens_out'] ?? 0;

$fan_date_rented = isset($total_fans_out) ? $total_fans_out : 0;
$linens_date_returned = isset($total_linens_out) ? $total_linens_out : 0;

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

    <link rel="stylesheet" href="homepage.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
.column {
  float: left;
  width: 33.33%;
  padding: 10px;
  margin-top: 10px;
}

.row::after {
  content: "";
  display: table;
  clear: both;
}

@media screen and (max-width:600px) {
  .column {
    width: 100%;
  }
}

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 95%;
  margin: auto;
}

td, th {
  border: 1px solid rgb(40, 40, 40);
  text-align: center;
  padding: 10px;
}

th {
  background-color: rgb(40, 40, 40);
  color: white;
}

tr {
  background-color:rgb(255, 255, 255);
}

h2 {
  text-align: center;
  color: black;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    width: 50%;
    text-align: left;
    font-size: larger;
  }

.close {
    position: absolute;
    top: 10px;
    right: 15px;
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
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
        <?php else: ?>
        <h2 class="mt-2 mx-2" style="float:right"> Welcome, Guest </h2>
        <?php endif; ?>
    </div>
        
    <div class="logout_button">
      <a href="#" class="dropbtn btn btn-secondary" id="accountButton"><i class="fa-solid fa-user"></i> Account</a>
        <div id="accountModal" class="modal">
            <div class="modal-content">
              <span class="close">&times;</span>
              <h2 style="text-align: left; padding-bottom: 15px;">Account Details</h2>
              <form id="accountUpdateForm" action="update_account.php" method="POST">
                <div style="margin-bottom: 15px;">
                <label for="username" style="font-weight: bold; padding-bottom: 2px;">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user["name"]) ?>" class="form-control">
                </div>
                <div style="margin-bottom: 15px;">
                  <label for="email" style="font-weight: bold; padding-bottom: 2px;">Email</label>
                  <input type="email" id="email" name="email" value="<?= htmlspecialchars($user["email"]) ?>" class="form-control">
                </div>
                <div style="margin-bottom: 15px;">
                  <label for="currentPassword" style="padding-bottom: 2px;"><b>Current Password</b></label>
                  <input type="password" id="currentPassword" name="currentPassword" class="form-control" placeholder="Current password">
                </div>
                <div style="margin-bottom: 15px;">
                  <label for="newPassword" style="padding-bottom: 2px;"><b>New Password</b></label>
                  <input type="password" id="newPassword" name="newPassword" class="form-control" placeholder="New password (Must contain a letter, a number, and be at least 8 characters)">
                </div>
                <div style="margin-bottom: 15px;">
                  <label for="confirmPassword" style="padding-bottom: 2px;"><b>Confirm New Password</b></label>
                  <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Confirm new password">
                </div>
                <p style="text-align: right; padding-top: 15px;">
                  <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                    <a href="admin_features.php" class="btn btn-warning">Admin Page</a>
                  <?php endif; ?>
                  <button type="submit" class="btn btn-success">Update</button>
                  <a href="home.php" class="btn btn-danger">Cancel</a>
                </p>
              </form>
            </div>
        </div>
        <a href="logout.php" class="dropbtn btn btn-danger"> Logout </a>
    </div>
</div>

<div class="topnavbar">
    <ul>
        <li><a href="home.php" class="btn btn-dark mx-2 mt-2 mb-2">Home</a></li>
        <li><a href="homepage.php" class="dropbtn btn btn-dark mx-2 mt-2 mb-2">Residence</a></li>
        <li><a href="linen.php" class="dropbtn btn btn-dark mx-2 mt-2 mb-2">Linen Rentals</a></li>
        <li><a href="fan.php" class="btn btn-dark mx-2 mt-2 mb-2">Fan Rentals</a></li>
        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
        <li><a href="Archives.php" class="btn btn-dark mx-2 mt-2 mb-2">Archives</a></li>
        <?php endif; ?>
    </ul>
    
    <form class="" method="get">
      <div class="input-group"></div>
    </form>
</div>

<div class="container mt-3" style="
    position: fixed;
    top: 15px;
    left: 50%;
    transform: translateX(-50%);
    width: auto;
    max-width: 90%;
    z-index: 1050; /* higher than most elements */
">
  <?php
  if (isset($_GET['msg'])) {
      $msg = htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8');
      echo '
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          ' . $msg . '
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
  }
  ?>
</div>


<div class="row">
    <div class="column" style="overflow-x:auto;">

    <table class = "buildings-table" style="margin-bottom: 35px; padding-left: 15px;">
        <tbody>
            <tr>
              <th colspan="2">Items Out</th>
            <tr>
              <td>Fans Out</td>
              <td><?= isset($total_fans_out) ? $total_fans_out : 0; ?></td>
            </tr>
            <tr>
              <td>Linens Out</td>
              <td><?= isset($total_linens_out) ? $total_linens_out : 0; ?></td>
            </tr>
        </tbody>
    </table>

    <div class="quick-add" style="text-align: center; margin-bottom: 20px; margin: 15px; background-color: white; padding: 15px;">
      <h2> Quick Add </h2>
      <button class="btn btn-danger" onclick="window.location.href='add_new.php'">Add Residence</button>
      <button class="btn btn-danger" onclick="window.location.href='add_new_linen.php'">Add Linen</button>
      <button class="btn btn-danger" onclick="window.location.href='add_new_fan.php'">Add Fan</button>
      <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
      <button class="btn btn-danger" onclick="window.location.href='import.php'">Add Dataset</button>
      <?php endif; ?>
    </div>
  </div>

    <div class="column" style="overflow-x:auto;">
      <div class="buildings-table">
        <table>
            <tr>
                <th>Building</th>
                <th>Checked in Residence</th>
            </tr>
            <tr>
                <td>Allen</td>
                <td><?= isset($building_counts['Allen']) ? $building_counts['Allen'] : 0; ?></td>
            </tr>
            <tr>
                <td>Annapolis</td>
                <td><?= isset($building_counts['Annapolis']) ? $building_counts['Annapolis'] : 0; ?></td>
            </tr>
            <tr>
                <td>Cumberland</td>
                <td><?= isset($building_counts['Cumberland']) ? $building_counts['Cumberland'] : 0; ?></td>
            </tr>
            <tr>
              <td>Diehl</td>
              <td><?= isset($building_counts['Diehl']) ? $building_counts['Diehl'] : 0; ?></td>
            </tr>
            <tr>
              <td>Frederick</td>
              <td><?= isset($building_counts['Frederick']) ? $building_counts['Frederick'] : 0; ?></td>
            </tr>
            <tr>
              <td>Frost</td>
              <td><?= isset($building_counts['Frost']) ? $building_counts['Frost'] : 0; ?></td>
            </tr>
            <tr>
              <td>Gray</td>
              <td><?= isset($building_counts['Gray']) ? $building_counts['Gray'] : 0; ?></td>
            </tr>
            <tr>
              <td>Simpson</td>
              <td><?= isset($building_counts['Simpson']) ? $building_counts['Simpson'] : 0; ?></td>
            </tr>
            <tr>
              <td>Sowers</td>
              <td><?= isset($building_counts['Sowers']) ? $building_counts['Sowers'] : 0; ?></td>
            </tr>
            <tr>
              <td>Westminster</td>
              <td><?= isset($building_counts['Westminster']) ? $building_counts['Westminster'] : 0; ?></td>
            </tr>
            <tr>
              <td>Total </td>
              <td><?= isset($total_checked_in) ? $total_checked_in : 0; ?></td>
            </tr>
        </table>
      </div>
    </div>

  <div class="column" style="overflow-x:auto;">
    <div class="groups-table">
        <table>
          <tr>
            <th>Groups</th>
            <th>Residence Per Group</th>
          </tr>
          <tr>
            <td>MD All-State</td>
            <td><?= isset($group_counts['MD All-State']) ? $group_counts['MD All-State'] : 0; ?></td>
          </tr>
          <tr>
            <td>Arlington Soccer</td>
            <td><?= isset($group_counts['Arlington Soccer']) ? $group_counts['Arlington Soccer'] : 0; ?></td>
          </tr>
          <tr>
            <td>Brit-AM</td>
            <td><?= isset($group_counts['Brit-AM']) ? $group_counts['Brit-AM'] : 0; ?></td>
          </tr>
          <tr>
            <td>Camp Hope</td>
            <td><?= isset($group_counts['Camp Hope']) ? $group_counts['Camp Hope'] : 0; ?></td>
          </tr>
          <tr>
            <td>LUC Staff</td>
            <td><?= isset($group_counts['LUC Staff']) ? $group_counts['LUC Staff'] : 0; ?></td>
          </tr>
          <tr>
            <td>Res Life Staff</td>
            <td><?= isset($group_counts['Res Life Staff']) ? $group_counts['Res Life Staff'] : 0; ?></td>
          </tr>
          <tr>
            <td>FSY Week 1</td>
            <td><?= isset($group_counts['FSY Week 1']) ? $group_counts['FSY Week 1'] : 0; ?></td>
          </tr>
          <tr>
            <td>FSY Week 2</td>
            <td><?= isset($group_counts['FSY Week 2']) ? $group_counts['FSY Week 2'] : 0; ?></td>
          </tr>
          <tr>
            <td>FSY Week 3</td>
            <td><?= isset($group_counts['FSY Week 3']) ? $group_counts['FSY Week 3'] : 0; ?></td>
          </tr>
          <tr>
            <td>Wooten Session 1</td>
            <td><?= isset($group_counts['Wooten Session 1']) ? $group_counts['Wooten Session 1'] : 0; ?></td>
          </tr>
          <tr>
            <td>Wooten Session 2</td>
            <td><?= isset($group_counts['Wooten Session 2']) ? $group_counts['Wooten Session 2'] : 0; ?></td>
          </tr>
          <tr>
            <td>Wooten Session 3</td>
            <td><?= isset($group_counts['Wooten Session 3']) ? $group_counts['Wooten Session 3'] : 0; ?></td>
          </tr>
          <tr>
            <td>Wooten Session 4</td>
            <td><?= isset($group_counts['Wooten Session 4']) ? $group_counts['Wooten Session 4'] : 0; ?></td>
          </tr>
          <tr>
            <td>Wooten Session 5</td>
            <td><?= isset($group_counts['Wooten Session 5']) ? $group_counts['Wooten Session 5'] : 0; ?></td>
          </tr>
          <tr>
            <td>Other</td>
            <td><?= isset($group_counts['Other']) ? $group_counts['Other'] : 0; ?></td>
          </tr>
        </table>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybU5p2Uj1d1iz1pSA9Wdnlrp9bJhFsD7/TfZp7xYfuN0KSf4I" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
      document.getElementById('accountButton').addEventListener('click', function (e) {
          e.preventDefault();
          document.getElementById('accountModal').style.display = 'block';
      });

      document.querySelector('.close').addEventListener('click', function () {
          document.getElementById('accountModal').style.display = 'none';
      });

      window.addEventListener('click', function (event) {
          const modal = document.getElementById('accountModal');
          if (event.target === modal) {
              modal.style.display = 'none';
          }
      });
    </script>

    <div class="footer">
        <p>Website Created by - Nathan Murphy <i class="fa fa-phone" style="font-size:12px"> 240-457-3326 </i> <i class="fa fa-envelope" style="font-size:12px"></i> Nathanmurphy0507@gmail.com </p>
    </div>
    
</body>
</html>