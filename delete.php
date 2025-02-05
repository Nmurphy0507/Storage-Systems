<?php
    include "db_conn.php";
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "DELETE FROM `checkin-out` WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        if(mysqli_stmt_affected_rows($stmt) > 0) {
            header("Location: homepage.php?msg=Record deleted successfully");
            exit();
        } else {
            echo "Failed to delete record.";
        }

        // Prevention of MYSQL injection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo "Invalid ID parameter.";
    }
?>