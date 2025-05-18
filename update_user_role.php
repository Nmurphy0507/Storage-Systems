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
    header("Location: admin_features.php?updated=0");
    exit;
}

// Validate input
if (!isset($_POST['id'], $_POST['user_type'])) {
    header("Location: admin_features.php?updated=0");
    exit;
}

$id = intval($_POST['id']);
$user_type = $_POST['user_type'];

if ($user_type !== 'user' && $user_type !== 'admin') {
    header("Location: admin_features.php?updated=0");
    exit;
}

// Update user role
$updateStmt = $mysqli->prepare("UPDATE user SET user_type = ? WHERE id = ?");
$updateStmt->bind_param("si", $user_type, $id);

if ($updateStmt->execute()) {
    header("Location: admin_features.php?updated=1");
    exit;
} else {
    header("Location: admin_features.php?updated=0");
    exit;
}
