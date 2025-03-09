<?php
include "db_conn.php";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $sql = "SELECT * FROM `linens_archive` WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $insertSql = "INSERT INTO linens (`id`, `resident_id`, `first_name`, `last_name`, `building`, `room`, `group`, `linen_type`, `linen_quantity`, `linen_cost`, `linen_date_rented`, `linen_date_returned`)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("iissssssssss", $row['id'], $row['resident_id'], $row['first_name'], $row['last_name'], $row['building'], $row['room'], $row['group'], $row['linen_type'], $row['linen_quantity'], $row['linen_cost'], $row['linen_date_rented'], $row['linen_date_returned']);
        
        if ($insertStmt->execute()) {
            $deleteSql = "DELETE FROM `linens_archive` WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $id);
            $deleteStmt->execute();

            header('Location: linen.php?msg=Record%20restored');
        } else {
            echo "Error restoring record: " . $conn->error;
        }
    } else {
        echo "Record not found.";
    }
} else {
    echo "Invalid ID.";
}
?>