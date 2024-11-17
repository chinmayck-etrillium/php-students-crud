<?php
$host = 'localhost';
$username = 'root'; 
$password = '1234'; 
$dbname = 'school_db';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
