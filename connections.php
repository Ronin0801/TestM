<?php
// connections.php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "test_db";

// Create connection
$connections = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($connections->connect_error) {
    die("Connection failed: " . $connections->connect_error);
}
?>
