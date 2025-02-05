<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM user WHERE id = {$_SESSION["user_id"]}";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
}

include "db_conn.php";

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM `checkin-out` WHERE 
            first_name LIKE '%$search%' OR
            last_name LIKE '%$search%' OR
            building LIKE '%$search%' OR
            room LIKE '%$search%' OR
            key_number LIKE '%$search%' OR
            floor LIKE '%$search%' OR
            mealcard LIKE '%$search%' OR
            checkin_signature LIKE '%$search%' OR
            Checked_in_out LIKE '%$search%' OR
            Date LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM `checkin-out`";
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
        <img alt="Frostburg State University" style="transform: translate(20px,23px);" src="src/FSU-logo.png">
        <?php if (isset($user)): ?>
        <h2 class="p-3 mt-3" style="float:right"> Welcome, <?= htmlspecialchars($user["name"])?> <a href="logout.php" class="dropbtn btn btn-danger"> Logout </a> </h2>
        <?php endif; ?>
</div>

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

<div class="topnavbar">
    <ul>
    <li><a href="home.php" class="btn btn-dark mx-1 mt-1">Home</a></li>
        <li class="dropdown">
        <a href="javascript:void(0)" class="dropbtn btn btn-dark mb-1 mx-0 mt-1">Checked In/Out</a>
            <div class="dropdown-content">
            <a href="homepage.php">All Keys</a>
            <a href="Checkedin.php">Checked In</a>
            <a href="Checkedout.php">Checked Out</a>
            <a href="">Archived Keys</a>
        </div>
        </li>
        <li class="dropdown">
        <a href="javascript:void(0)" class="dropbtn btn btn-dark mb-1 mx-1 mt-1">Building</a>
        <div class="dropdown-content">
            <a href="">Allen</a>
            <a href="">Annapolis</a>
            <a href="">Cumberland</a>
            <a href="">Diehl</a>
            <a href="">Frederick</a>
            <a href="">Frost</a>
            <a href="">Gray</a>
            <a href="">Simpson</a>
            <a href="">Sowers</a>
            <a href="">Westminster</a>
        </div>
        </li>
        <li><a href="homepage.php" class="btn btn-dark mt-1">Status</a></li>
        <li><a href="homepage.php" class="btn btn-dark mx-1 mt-1">Upcoming</a></li>
        <li style="float:right"><a href="add_new.php" class="btn btn-dark mb-2 mx-1 mt-1"> Add New </a></li>
        <li style="float:right"><a href="" class="btn btn-dark mb-1 mt-1"> Insert Dataset </a></li>
        
        <form class="p-4" method="get">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search..." id="searchInput" name="search" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search)): ?>
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-danger ms-2"> Clear Search </a>
                <?php endif; ?>
            </div>
        </form>
    </ul>
</div>

    <div class="column middle">
        <table class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Building</th>
                    <th scope="col">Room / Bed</th>
                    <th scope="col">Key</th>
                    <th scope="col">Floor</th>
                    <th scope="col">Mealcard</th>
                    <th scope="col">Signature</th>
                    <th scope="col">Checked In or Out</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody style="background-color:#ffffff">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php if ($row['Checked_in_out'] === 'Checked In'): ?>
                        <tr>
                            <td><?php echo highlight_search_result($row['first_name'], $search); ?></td>
                            <td><?php echo highlight_search_result($row['last_name'], $search); ?></td>
                            <td><?php echo highlight_search_result($row['building'], $search); ?></td>
                            <td><?php echo highlight_search_result($row['room'], $search); ?></td>
                            <td><?php echo highlight_search_result($row['key_number'], $search); ?></td>
                            <td><?php echo highlight_search_result($row['floor'], $search); ?></td>
                            <td><?php echo highlight_search_result($row['mealcard'], $search); ?></td>
                            <td><?php echo highlight_search_result($row['checkin_signature'], $search); ?></td>
                            <td><?php echo highlight_search_result($row['Checked_in_out'], $search); ?></td>
                            <td><?php echo highlight_search_result($row['Date'], $search); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="link-dark"><i class="fa-solid fa-trash fs-5"></i></a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybU5p2Uj1d1iz1pSA9Wdnlrp9bJhFsD7/TfZp7xYfuN0KSf4I" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0sG1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <!-- Custom JS -->
    <script src=""></script>

    <div class="footer">
        <p>Website Created by - Nathan Murphy <i class="fa fa-phone" style="font-size:12px"> 240-457-3326 </i> <i class="fa fa-envelope" style="font-size:12px"></i> Nathanmurphy0507@gmail.com </p>
    </div>
    
</body>
</html>
