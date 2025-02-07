<?php
include "db_conn.php";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $sql = "SELECT * FROM ArchivedKeys WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $insertSql = "INSERT INTO `checkin-out` (`first_name`, `last_name`, `building`, `room`, `key_number`, `group`, `mealcard`, `checkin_signature`, `Checked_in_out`, `key_returned`, `mealcard_returned`, `Date`, `notes`)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("sssssssssssss", $row['first_name'], $row['last_name'], $row['building'], $row['room'], $row['key_number'], $row['group'], $row['mealcard'], $row['checkin_signature'], $row['Checked_in_out'], $row['key_returned'], $row['mealcard_returned'], $row['Date'], $row['notes']);
        
        if ($insertStmt->execute()) {
            $deleteSql = "DELETE FROM ArchivedKeys WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $id);
            $deleteStmt->execute();

            header('Location: homepage.php?msg=Record%20restored');
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

