<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "lift_a_boat";

// create connection --> variables from config.php
$conn = new mysqli($servername, $username, $password, $database);

// error on not establishing connection
if ($conn->connect_error) {
    //error logging
    $error_message = "Connection failed: " . mysqli_connect_error();
    $log_file = "../../logs/error_log.log";
    error_log(date("Y-m-d H:i:s") . " " . $error_message . "\n", 3, $log_file);
    die("Connection failed: " . $conn->connect_error . "<br>");
}

// create database if it doesn't exist --> database variable from config.php
$sql = "CREATE DATABASE IF NOT EXISTS $database";

// error checking on database creation
if (!mysqli_query($conn, $sql)) {
    //error logging
    $error_message = "Error creating database: " . mysqli_error($conn);
    $log_file = "../../logs/error_log.log";
    error_log(date("Y-m-d H:i:s") . " " . $error_message . "\n", 3, $log_file);
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}