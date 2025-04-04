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

$search = "";
$filter = isset($_GET['filter']) ? $_GET['filter'] : "";
$building = isset($_GET['building']) ? $_GET['building'] : "";
$group = isset($_GET['group']) ? $_GET['group'] : "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT co.id as resident_id, co.first_name, co.last_name, co.building, co.room, co.`group`, l.first_name, l.last_name, l.building, l.room, l.`group`, l.linen_type, l.linen_quantity, l.linen_cost, l.linen_date_rented, l.linen_date_returned
            FROM `linens` l
            LEFT JOIN `checkin-out` co ON co.id = l.resident_id
            WHERE (co.first_name LIKE '%$search%' OR
                   co.last_name LIKE '%$search%' OR
                   co.building LIKE '%$search%' OR
                   co.room LIKE '%$search%' OR
                   co.`group` LIKE '%$search%' OR
                   l.first_name LIKE '%$search%' OR
                   l.last_name LIKE '%$search%' OR
                   l.building LIKE '%$search%' OR
                   l.room LIKE '%$search%' OR
                   l.`group` LIKE '%$search%' OR
                   l.linen_type LIKE '%$search%' OR
                   l.linen_quantity LIKE '%$search%' OR
                   l.linen_cost LIKE '%$search%' OR
                   l.linen_date_rented LIKE '%$search%' OR
                   l.linen_date_returned LIKE '%$search%')";
} else {
    $sql = "SELECT co.id as resident_id, co.first_name, co.last_name, co.building, co.room, co.`group`, l.first_name, l.last_name, l.building, l.room, l.`group`, l.linen_type, l.linen_quantity, l.linen_cost, l.linen_date_rented, l.linen_date_returned
            FROM `linens` l
            LEFT JOIN `checkin-out` co ON co.id = l.resident_id
            WHERE l.linen_date_rented IS NOT NULL";
}
$result = mysqli_query($conn, $sql);

function highlight_search_result($text, $search) {
    if (!empty($search)) {
        $text = preg_replace("/($search)/i", "<strong>$1</strong>", $text);
    }
    return $text;
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
        <li><a href="homepage.php" class="dropbtn btn btn-dark mx-2 mt-2 mb-2">Residence</a></li>
        <li><a href="linen.php" class="dropbtn btn btn-dark mx-2 mt-2 mb-2">Linen Rentals</a></li>
        <li><a href="fan.php" class="btn btn-dark mx-2 mt-2 mb-2">Fan Rentals</a></li>
        <li><a href="Archives.php" class="btn btn-dark mx-2 mt-2 mb-2">Archives</a></li>

        <div class="right_buttons">
            <li><a href="add_new_linen.php" class="btn btn-secondary my-2"> Add New </a></li>
        </div>

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

    <div class="column middle mt-4">
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

        <h4>Linen Rentals</h4>
            <thead class="table-dark">
                <tr>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Group</th>
                    <th scope="col">Building</th>
                    <th scope="col">Room / Bed</th>
                    <th scope="col">Linen Type</th>
                    <th scope="col">Linen Quantity</th>
                    <th scope="col">Linen Cost</th>
                    <th scope="col">Date Rented</th>
                    <th scope="col">Date Returned</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody style="background-color:#ffffff">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo highlight_search_result($row['first_name'], $search); ?></td>
                    <td><?php echo highlight_search_result($row['last_name'], $search); ?></td>
                    <td><?php echo highlight_search_result($row['group'], $search); ?></td>
                    <td><?php echo highlight_search_result($row['building'], $search); ?></td>
                    <td><?php echo highlight_search_result($row['room'], $search); ?></td>
                    <td><?php echo highlight_search_result($row['linen_type'], $search); ?></td>
                    <td><?php echo highlight_search_result($row['linen_quantity'], $search); ?></td>
                    <td><?php echo highlight_search_result($row['linen_cost'], $search); ?></td>
                    <td><?php echo highlight_search_result($row['linen_date_rented'], $search); ?></td>
                    <td><?php echo highlight_search_result($row['linen_date_returned'], $search); ?></td>
                    <td>
                        <a href="edit_linen.php?id=<?php echo $row['resident_id']; ?>"><i class="fa-solid fa-pen-to-square fs-5 me-1 link-dark"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php if (empty($filter) && empty($building) && empty($group)): ?>
        <form method="post" action="excel_linen.php">
            <input type="submit" name="export_excel" style="float:right" class="btn btn-success " value="Export to Excel">
        </form>
        <?php endif ?>

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