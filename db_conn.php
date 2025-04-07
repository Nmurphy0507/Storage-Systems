<?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Dormroom_Keys";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if(!$conn){
        die ("Connection failed " . mysqli_connect_error());
    }

    // echo("Connect Successfully!");
?>