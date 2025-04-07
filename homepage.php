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
    $sql = "SELECT co.*, l.linen_date_rented FROM `checkin-out` co
            LEFT JOIN `linens` l ON co.id = l.resident_id
            WHERE (co.first_name LIKE '%$search%' OR
                   co.last_name LIKE '%$search%' OR
                   co.building LIKE '%$search%' OR
                   co.room LIKE '%$search%' OR
                   co.key_number LIKE '%$search%' OR
                   co.`group` LIKE '%$search%' OR
                   co.mealcard LIKE '%$search%' OR
                   co.checkin_signature LIKE '%$search%' OR
                   co.Checked_in_out LIKE '%$search%' OR
                   co.Date LIKE '%$search%')";
    
    if ($filter === 'checkedin') {
        $sql .= " AND co.Checked_in_out = 'Checked In'";
    } elseif ($filter === 'checkedout') {
        $sql .= " AND co.Checked_in_out = 'Checked Out'";
    }

    if (!empty($building)) {
        $sql .= " AND co.building = '$building'";
    }

    if (!empty($group)) {
        $sql .= " AND co.`group` = '$group'";
    }

} else {
    $sql = "SELECT co.*, l.linen_date_rented, f.fan_date_rented FROM `checkin-out` co
            LEFT JOIN `linens` l ON co.id = l.resident_id
            LEFT JOIN `fan` f ON co.id = f.resident_id
            WHERE 1=1";

    if ($filter === 'checkedin') {
        $sql .= " AND co.Checked_in_out = 'Checked In'";
    } elseif ($filter === 'checkedout') {
        $sql .= " AND co.Checked_in_out = 'Checked Out'";
    }

    if (!empty($building)) {
        $sql .= " AND co.building = '$building'";
    }

    if (!empty($group)) {
        $sql .= " AND co.`group` = '$group'";
    }
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
        <h2 class="mt-2 mx-2" style="float:right;"> Welcome, <?= htmlspecialchars($user["name"])?></h2>
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
        <li class="dropdown mb-2">
            <a href="javascript:void(0)" class="dropbtn btn btn-dark mx-2 mt-2 mb-2 dropdown-toggle">Residence</a>
            <div class="dropdown-content">
                <a href="homepage.php">All Residence</a>
                <a href="homepage.php?filter=checkedin">Checked In</a>
                <a href="homepage.php?filter=checkedout">Checked Out</a>
            </div>
        </li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn btn btn-dark mx-2 mt-2 mb-2 dropdown-toggle">Buildings</a>
            <div class="dropdown-content">
                <a href="homepage.php?building=Allen">Allen</a>
                <a href="homepage.php?building=Annapolis">Annapolis</a>
                <a href="homepage.php?building=Cumberland">Cumberland</a>
                <a href="homepage.php?building=Diehl">Diehl</a>
                <a href="homepage.php?building=Frederick">Frederick</a>
                <a href="homepage.php?building=Frost">Frost</a>
                <a href="homepage.php?building=Gray">Gray</a>
                <a href="homepage.php?building=Simpson">Simpson</a>
                <a href="homepage.php?building=Sowers">Sowers</a>
                <a href="homepage.php?building=Westminster">Westminster</a>
            </div>
        </li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn btn btn-dark mx-2 mt-2 mb-2 dropdown-toggle">Groups</a>
            <div class="dropdown-content">
                <div class="dropdown-submenu">
                    <a href="javascript:void(0)" class="dropdown-toggle">Wooten</a>
                    <div class="submenu-content">
                        <a href="homepage.php?group=Wooten Session 1">Wooten Session 1</a>
                        <a href="homepage.php?group=Wooten Session 2">Wooten Session 2</a>
                        <a href="homepage.php?group=Wooten Session 3">Wooten Session 3</a>
                        <a href="homepage.php?group=Wooten Session 4">Wooten Session 4</a>
                        <a href="homepage.php?group=Wooten Session 5">Wooten Session 5</a>
                    </div>
                </div>
                <div class="dropdown-submenu">
                    <a href="javascript:void(0)" class="dropdown-toggle">FSY</a>
                    <div class="submenu-content">
                        <a href="homepage.php?group=FSY Week 1">FSY Week 1</a>
                        <a href="homepage.php?group=FSY Week 2">FSY Week 2</a>
                        <a href="homepage.php?group=FSY Week 3">FSY Week 3</a>
                    </div>
                </div>
                <a href="homepage.php?group=MD All-State">MD All-State</a>
                <a href="homepage.php?group=Arlington Soccer">Arlington Soccer</a>
                <a href="homepage.php?group=Brit-AM">Brit-AM</a>
                <a href="homepage.php?group=Camp Hope">Camp Hope</a>
                <a href="homepage.php?group=LUC Staff">LUC Staff</a>
                <a href="homepage.php?group=Res Life Staff">Res Life Staff</a>
                <a href="homepage.php?group=Other">Other</a>
            </div>
        </li>
        <li><a href="linen.php" class="dropbtn btn btn-dark mx-2 mt-2 mb-2">Linen Rentals</a></li>
        <li><a href="fan.php" class="btn btn-dark mx-2 mt-2 mb-2">Fan Rentals</a></li>
        <li><a href="Archives.php" class="btn btn-dark mx-2 mt-2 mb-2">Archives</a></li>
        
        <div class="right_buttons">
            <li><a href="add_new.php" class="btn btn-secondary my-2 mx-2"> Add New </a></li>
            <li><a href="import.php" class="btn btn-secondary my-2"> Insert Dataset </a></li>
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

        <h4>
            <?php
            if ($filter === 'checkedin') {
                echo "Checked In Residence";
            } elseif ($filter === 'checkedout') {
                echo "Checked Out Residence";
            } elseif (!empty($building)) {
                echo "{$building} Hall";
            } elseif (!empty($group)) {
                echo "{$group} Group";
            } else {
                echo "All Residence";
            }
            ?>
        </h4>
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
                    <th scope="col">Notes</th>
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
                    <td><?php echo highlight_search_result($row['key_number'], $search); ?></td>
                    <td><?php echo highlight_search_result($row['mealcard'], $search); ?></td>
                    <td>
                        <?php if (!empty($row['checkin_signature'])): ?>
                            <img src="<?php echo htmlspecialchars($row['checkin_signature']); ?>" alt="Signature" class="signature-image">
                        <?php else: ?>
                            No Signature
                        <?php endif; ?>
                    </td>
                    <td><?php echo highlight_search_result($row['Checked_in_out'], $search); ?></td>
                    <td><?php echo highlight_search_result($row['Date'], $search); ?></td>
                    <td>
                        <div class="truncate-text" title="<?= htmlspecialchars($row['notes']) ?>">
                        </div>
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
                    <td>
                        <a href="edit_linen.php?id=<?php echo $row['id']; ?>"><i class="fa-solid fa-bed <?php echo !empty($row['linen_date_rented']) ? 'text-success' : 'link-dark';?>"></i></a>
                        <a href="edit_fan.php?id=<?php echo $row['id']; ?>"><i class="fa-solid fa-fan <?php echo !empty($row['fan_date_rented']) ? 'text-success' : 'link-dark';?>"></i></a>
                        <a href="edit.php?id=<?php echo $row['id']; ?>"><i class="fa-solid fa-pen-to-square link-dark mx-1"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <?php if (empty($filter) && empty($building) && empty($group)): ?>
        <form method="post" action="excelactive.php">
            <input type="submit" name="export_excel" style="float:right" class="btn btn-success " value="Export to Excel">
        </form>
        <?php endif ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybU5p2Uj1d1iz1pSA9Wdnlrp9bJhFsD7/TfZp7xYfuN0KSf4I" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src=""></script>

    <div class="footer">
        <p>Website Created by - Nathan Murphy <i class="fa fa-phone" style="font-size:12px"> 240-457-3326 </i> <i class="fa fa-envelope" style="font-size:12px"></i> Nathanmurphy0507@gmail.com </p>
    </div>
    
</body>
</html>