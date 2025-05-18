<?php
header('Content-Type: application/json');

$errors = [];
$data = [];

if (empty($_POST["Username"])) {
    $errors['username'] = 'Username is required';
}

if (!filter_var($_POST["Email"], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Valid email address required';
} elseif (substr($_POST["Email"], -14) !== "@frostburg.edu") {
    $errors['email'] = 'Email must be from @frostburg.edu';
}

if (strlen($_POST["Password"]) < 8) {
    $errors['password'] = 'Password must be at least 8 characters';
} elseif (!preg_match("/[a-z]/i", $_POST["Password"])) {
    $errors['password'] = 'Password must contain at least one letter';
} elseif (!preg_match("/[0-9]/i", $_POST["Password"])) {
    $errors['password'] = 'Password must contain at least one number';
}

if ($_POST["Password"] !== $_POST["ConfirmPassword"]) {
    $errors['confirmPassword'] = 'Passwords must match';
}

if (!empty($errors)) {
    $data['success'] = false;
    $data['errors'] = $errors;
} else {
    $password_hash = password_hash($_POST["Password"], PASSWORD_DEFAULT);

    $mysqli = require __DIR__ . "/database.php";

    $sql = "INSERT INTO user (name, Email, password_hash, user_type)
            VALUES (?,?,?,?)";

    $stmt = $mysqli->stmt_init();

    if (!$stmt->prepare($sql)) {
        die("SQL error: " . $mysqli->error);
    }

    // Set user_type to 'user' by default
    $user_type = 'user';

    $stmt->bind_param("ssss",
                      $_POST["username"],
                      $_POST["Email"],
                      $password_hash,
                      $user_type);

    if ($stmt->execute()) {
        $data['success'] = true;
    } else {
        $data['success'] = false;
        $data['errors']['database'] = 'Database error: ' . $stmt->error;
    }
}

echo json_encode($data);
?>