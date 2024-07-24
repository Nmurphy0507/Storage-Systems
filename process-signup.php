<?php
     if (empty($_POST["Username"])){
        die("Username is required");
     }  
     
     if (! filter_var($_POST["Email"], FILTER_VALIDATE_EMAIL)){
        die("Valid email address required");
     } 

     if (strlen($_POST["Password"]) < 8){
        die("Password must be at least 8 character");
     }
     if (! preg_match("/[a-z]/i", $_POST["Password"])) {
        die("Password must contain at least one letter");
     }
     if (! preg_match("/[0-9]/i", $_POST["Password"])) {
        die("Password must contain at least one number");
     }
     if ($_POST["Password"] !== $_POST["ConfirmPassword"]){
         die("Passwords must match");
     }

     $password_hash = password_hash($_POST["Password"], PASSWORD_DEFAULT);

     $mysqli = require __DIR__ . "/database.php";

     $sql = "INSERT INTO user (name, Email, password_hash)
             VALUES (?,?,?)";

     $stmt = $mysqli->stmt_init();

     if (! $stmt->prepare($sql)){
         die("SQL error: " . $mysqli->error);
     }

     $stmt->bind_param("sss",
                       $_POST["Username"],
                       $_POST["Email"],
                       $password_hash);

     if($stmt->execute()){
      header("Location: signup-successful-button.html");
      exit;

       } else {
        echo "SQL error: " . $stmt->error;
    }
?>   