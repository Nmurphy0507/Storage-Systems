<?php
session_start();
require_once __DIR__ . "/database.php";

// Check if admin user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $mysqli->prepare("SELECT user_type FROM user WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$currentUser = $result->fetch_assoc();

if (!$currentUser || $currentUser['user_type'] !== 'admin') {
    // Not an admin, deny access
    header("Location: admin_features.php?deleted=0");
    exit;
}

// Validate input
if (!isset($_GET['id'])) {
    header("Location: admin_features.php?deleted=0");
    exit;
}

$id = intval($_GET['id']);

// Optional: Prevent admin from deleting themselves
if ($id === $_SESSION['user_id']) {
    header("Location: admin_features.php?deleted=0");
    exit;
}

// Delete user
$deleteStmt = $mysqli->prepare("DELETE FROM user WHERE id = ?");
$deleteStmt->bind_param("i", $id);

if ($deleteStmt->execute()) {
    header("Location: admin_features.php?deleted=1");
    exit;
} else {
    header("Location: admin_features.php?deleted=0");
    exit;
}
