<?php
session_start();
require __DIR__ . "/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: home.php?msg=" . urlencode("You must be logged in."));
    exit;
}

$user_id = $_SESSION["user_id"];
$username = trim($_POST["username"] ?? "");
$email = trim($_POST["email"] ?? "");
$currentPassword = $_POST["currentPassword"] ?? "";
$newPassword = $_POST["newPassword"] ?? "";
$confirmPassword = $_POST["confirmPassword"] ?? "";

// Validation for username and email
if (empty($username)) {
    header("Location: home.php?msg=" . urlencode("Username is required."));
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || substr($email, -14) !== "@frostburg.edu") {
    header("Location: home.php?msg=" . urlencode("Invalid @frostburg.edu email."));
    exit;
}

// Get current user's password hash from DB
$sql = "SELECT password_hash FROM user WHERE id = ?";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    header("Location: home.php?msg=" . urlencode("Database error."));
    exit;
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($password_hash_db);
$stmt->fetch();
$stmt->close();

// Password change requested?
if (!empty($newPassword) || !empty($confirmPassword)) {
    // Must provide current password
    if (empty($currentPassword)) {
        header("Location: home.php?msg=" . urlencode("Current password is required to change password."));
        exit;
    }

    // Verify current password matches DB
    if (!password_verify($currentPassword, $password_hash_db)) {
        header("Location: home.php?msg=" . urlencode("Current password is incorrect."));
        exit;
    }

    if (strlen($newPassword) < 8) {
        header("Location: home.php?msg=" . urlencode("Password must be at least 8 characters."));
        exit;
    }
    if (!preg_match("/[a-z]/i", $newPassword)) {
        header("Location: home.php?msg=" . urlencode("Password must contain at least one letter."));
        exit;
    }
    if (!preg_match("/[0-9]/", $newPassword)) {
        header("Location: home.php?msg=" . urlencode("Password must contain at least one number."));
        exit;
    }
    if ($newPassword !== $confirmPassword) {
        header("Location: home.php?msg=" . urlencode("Passwords do not match."));
        exit;
    }
}

$sql = "UPDATE user SET name = ?, email = ? WHERE id = ?";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    header("Location: home.php?msg=" . urlencode("Database error."));
    exit;
}
$stmt->bind_param("ssi", $username, $email, $user_id);
if (!$stmt->execute()) {
    header("Location: home.php?msg=" . urlencode("Failed to update account info."));
    exit;
}

if (!empty($newPassword)) {
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE user SET password_hash = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        header("Location: home.php?msg=" . urlencode("Database error on password update."));
        exit;
    }
    $stmt->bind_param("si", $newPasswordHash, $user_id);
    if (!$stmt->execute()) {
        header("Location: home.php?msg=" . urlencode("Failed to update password."));
        exit;
    }
}

header("Location: home.php?msg=" . urlencode("Account updated successfully!"));
exit;
