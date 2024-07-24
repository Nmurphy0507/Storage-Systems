<?php

    session_start();

    if(isset($_SESSION["user_id"])){
        $mysqli = require __DIR__ . "/database.php";

        $sql = "SELECT * FROM user
                WHERE id = {$_SESSION["user_id"]}";

        $result = $mysqli -> query($sql);

        $user = $result -> fetch_assoc();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>PHP Keysorting App</title>
</head>
<body>

    <nav class="navbar  navbar-light fs-3" style="background-color: #8F150C">
       <?php if (isset($user)): ?>
        <p class="fs-3 mb-0">&nbsp Welcome, <?= htmlspecialchars($user["name"]) ?></p>
        <p class="fs-3 mb-0"><a href="logout.php">Log out</a>&nbsp</p>
        <?php endif; ?>
    </nav>

    <nav class="navbar navbar-light justify-content-center fs-3 mb-3" style="background-color: #8F150C">
        <h1>Residence Hall Keys</h1>
    </nav>

    <div class="container">
        <?php 
            if(isset($_GET['msg'])){
                $msg = $_GET['msg'];
                echo 
                '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    '.$msg.'
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }  
        ?>

        <a href="add_new.php" class="btn btn-dark mb-3"> Add New </a>

        <form class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search..." id="searchInput">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i></button>
            </div>
        </form>

    <table class="table table-hover text-center">
    <thead class = "table-dark">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">First Name</th>
      <th scope="col">Last Name</th>
      <th scope="col">Buidling</th>
      <th scope="col">Room / Bed</th>
      <th scope="col">Key</th>
      <th scope="col">Floor</th>
      <th scope="col">Barcode</th>
      <th scope="col">Signature</th>
      <th scope="col">Checked In or Out</th>
      <th scope="col">Date</th>
      <th scope="col">Action</th>
    </tr>
    </thead>

    <?php
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
            barcode LIKE '%$search%' OR
            checkin_signature LIKE '%$search%' OR
            Checked_in_out LIKE '%$search%' OR
            Date LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM `checkin-out`";
}

$result = mysqli_query($conn, $sql);
?>

<tbody>
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?php echo highlight_search_result($row['id'], $search); ?></td>
            <td><?php echo highlight_search_result($row['first_name'], $search); ?></td>
            <td><?php echo highlight_search_result($row['last_name'], $search); ?></td>
            <td><?php echo highlight_search_result($row['building'], $search); ?></td>
            <td><?php echo highlight_search_result($row['room'], $search); ?></td>
            <td><?php echo highlight_search_result($row['key_number'], $search); ?></td>
            <td><?php echo highlight_search_result($row['floor'], $search); ?></td>
            <td><?php echo highlight_search_result($row['barcode'], $search); ?></td>
            <td><?php echo highlight_search_result($row['checkin_signature'], $search); ?></td>
            <td><?php echo highlight_search_result($row['Checked_in_out'], $search); ?></td>
            <td><?php echo highlight_search_result($row['Date'], $search); ?></td>

            
            <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                <a href="delete.php?id=<?php echo $row['id']; ?>" class="link-dark"><i class="fa-solid fa-trash fs-5"></i></a>
            </td>
        </tr>
        <?php 
    }
    ?>
</tbody>

<?php
function highlight_search_result($text, $search) {
    if (!empty($search)) {
        $text = preg_replace("/($search)/i", "<span class='highlight'>$1</span>", $text);
    }
    return $text;
}
?>
    </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        
        searchInput.addEventListener('input', function() {
            const searchText = searchInput.value.trim().toLowerCase();
            const cells = document.querySelectorAll('tbody td');
            let found = false;

            cells.forEach(cell => {
                const cellText = cell.textContent.trim().toLowerCase();
                const index = cellText.indexOf(searchText);
                
                if (index !== -1) {
                    found = true;
                    const highlightedText = `<span class="highlight">${cellText.substr(index, searchText.length)}</span>`;
                    const newText = cellText.replace(searchText, highlightedText);
                    cell.innerHTML = newText;

                    // Scroll to the parent row of the cell
                    cell.closest('tr').scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    cell.innerHTML = cellText; // Reset original content
                }
            });

            // If not found, reset all cells
            if (!found) {
                cells.forEach(cell => {
                    cell.innerHTML = cell.textContent.trim();
                });
            }
        });
    });
    </script>

    <style>
        .highlight {
            background-color: yellow;
            font-weight: bold;
        }
    </style>
        
</body>
</html>
