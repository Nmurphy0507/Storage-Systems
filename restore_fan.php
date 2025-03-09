<?php
include "db_conn.php";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $sql = "SELECT * FROM `fan_archive` WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $insertSql = "INSERT INTO fan (`id`, `resident_id`, `first_name`, `last_name`, `building`, `room`, `group`, `fan_quantity`, `fan_cost`, `fan_date_rented`, `fan_date_returned`)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("iisssssssss", $row['id'], $row['resident_id'], $row['first_name'], $row['last_name'], $row['building'], $row['room'], $row['group'], $row['fan_quantity'], $row['fan_cost'], $row['fan_date_rented'], $row['fan_date_returned']);
        
        if ($insertStmt->execute()) {
            $deleteSql = "DELETE FROM `fan_archive` WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $id);
            $deleteStmt->execute();

            header('Location: fan.php?msg=Record%20restored');
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