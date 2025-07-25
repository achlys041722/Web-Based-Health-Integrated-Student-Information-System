<?php
// src/register_account.php
session_start();

// Admin password (change this in production!)
$ADMIN_PASSWORD = 'sample';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../templates/login.php?reg_error=Invalid+request');
    exit();
}

require_once(__DIR__ . '/../common/db.php');

$role = $_POST['role'] ?? '';
$email = trim($_POST['email'] ?? '');
$full_name = trim($_POST['full_name'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$admin_password = $_POST['admin_password'] ?? '';

if ($admin_password !== $ADMIN_PASSWORD) {
    header('Location: ../templates/login.php?reg_error=Invalid+admin+password');
    exit();
}
if (!$role || !$email || !$full_name || !$password || !$confirm_password) {
    header('Location: ../templates/login.php?reg_error=All+fields+are+required');
    exit();
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../templates/login.php?reg_error=Invalid+email+format');
    exit();
}
if ($password !== $confirm_password) {
    header('Location: ../templates/login.php?reg_error=Passwords+do+not+match');
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert into the correct table
if ($role === 'nurse') {
    $table = 'nurses';
} elseif ($role === 'principal') {
    $table = 'principals';
} elseif ($role === 'teacher') {
    $table = 'teachers';
} else {
    header('Location: ../templates/login.php?reg_error=Invalid+role');
    exit();
}

// Check for duplicate email
$check = $conn->prepare("SELECT id FROM $table WHERE email = ?");
$check->bind_param('s', $email);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    header('Location: ../templates/login.php?reg_error=Email+already+exists');
    exit();
}

// Insert basic info (profile completion will be done after login)
$stmt = $conn->prepare("INSERT INTO $table (email, password, full_name) VALUES (?, ?, ?)");
$stmt->bind_param('sss', $email, $hashed_password, $full_name);
if ($stmt->execute()) {
    // Redirect to the appropriate profile completion form
    if ($role === 'nurse') {
        header('Location: ../templates/login.php?reg_success=Account+created+successfully.+Please+log+in+to+complete+your+profile.');
    } elseif ($role === 'principal') {
        header('Location: ../templates/login.php?reg_success=Account+created+successfully.+Please+log+in+to+complete+your+profile.');
    } elseif ($role === 'teacher') {
        header('Location: ../templates/login.php?reg_success=Account+created+successfully.+Please+log+in+to+complete+your+profile.');
    }
    exit();
} else {
    header('Location: ../templates/login.php?reg_error=Failed+to+create+account');
    exit();
} 