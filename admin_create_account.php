<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Only allow admins
require_once __DIR__ . "/database.php";
$stmt = $mysqli->prepare("SELECT user_type FROM user WHERE id = ?");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user["user_type"] !== "admin") {
    header("Location: home.php?msg=" . urlencode("Unauthorized."));
    exit;
}

// Validate POST data
$errors = [];

if (empty($_POST["username"])) {
    $errors[] = "Username is required";
}

if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Valid email address required";
} elseif (substr($_POST["email"], -14) !== "@frostburg.edu") {
    $errors[] = "Email must be from @frostburg.edu";
}

if (strlen($_POST["password"]) < 8) {
    $errors[] = "Password must be at least 8 characters";
} elseif (!preg_match("/[a-z]/i", $_POST["password"])) {
    $errors[] = "Password must contain at least one letter";
} elseif (!preg_match("/[0-9]/i", $_POST["password"])) {
    $errors[] = "Password must contain at least one number";
}

if ($_POST["password"] !== $_POST["confirm_password"]) {
    $errors[] = "Passwords must match";
}

if (!in_array($_POST["user_type"], ['user', 'admin'])) {
    $errors[] = "Invalid user type";
}

if (!empty($errors)) {
    header("Location: admin_features.php?msg=" . urlencode(implode(", ", $errors)));
    exit;
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Insert user
$sql = "INSERT INTO user (name, email, password_hash, user_type) VALUES (?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ssss", $_POST["username"], $_POST["email"], $password_hash, $_POST["user_type"]);

if ($stmt->execute()) {
    header("Location: admin_features.php?msg=" . urlencode("Account created successfully."));
    exit;
} else {
    header("Location: admin_features.php?msg=" . urlencode("Error: " . $stmt->error));
    exit;
}
?>
