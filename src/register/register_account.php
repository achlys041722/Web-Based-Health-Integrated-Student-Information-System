<?php
session_start();
require_once(__DIR__ . '/../common/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $admin_password = $_POST['admin_password'] ?? '';

    // Only allow registration if admin password is correct
    if ($admin_password !== 'sample') {
        header('Location: ../../templates/register/login.php?error=Invalid+admin+password&error_type=register');
        exit();
    }
    if ($password !== $confirm_password) {
        header('Location: ../../templates/register/login.php?error=Passwords+do+not+match&error_type=register');
        exit();
    }
    if (!$role || !$email || !$full_name || !$password) {
        header('Location: ../../templates/register/login.php?error=Please+fill+all+fields&error_type=register');
        exit();
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    if ($role === 'principal') {
        $stmt = $conn->prepare('INSERT INTO principals (email, password, full_name, elementary_school, contact_info) VALUES (?, ?, ?, "", "")');
        $stmt->bind_param('sss', $email, $hashed_password, $full_name);
    } elseif ($role === 'teacher') {
        $stmt = $conn->prepare('INSERT INTO teachers (email, password, full_name, grade_level, address, contact_info, principal_email, status) VALUES (?, ?, ?, "", "", "", "", "pending")');
        $stmt->bind_param('sss', $email, $hashed_password, $full_name);
    } elseif ($role === 'nurse') {
        $stmt = $conn->prepare('INSERT INTO nurses (email, password, full_name, birthdate, address, contact_info) VALUES (?, ?, ?, NULL, NULL, NULL)');
        $stmt->bind_param('sss', $email, $hashed_password, $full_name);
    } else {
        header('Location: ../../templates/register/login.php?error=Invalid+role&error_type=register');
        exit();
    }
    if ($stmt->execute()) {
        header('Location: ../../templates/register/login.php?success=Account+created+successfully');
        exit();
    } else {
        header('Location: ../../templates/register/login.php?error=Account+creation+failed&error_type=register');
        exit();
    }
} else {
    header('Location: ../../templates/register/login.php');
    exit();
} 