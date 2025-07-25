<?php
// config/db.php
// Database connection script for the Health-Integrated Student Information System

$host = 'localhost';
$db   = 'health_student_info_system';
$user = 'root'; // Default XAMPP user
$pass = '';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Use $conn in your scripts to interact with the database 