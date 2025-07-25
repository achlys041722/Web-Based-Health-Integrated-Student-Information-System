<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'nurse') {
    header('Location: ../templates/login.php?error=Access+denied');
    exit();
}
require_once(__DIR__ . '/../common/db.php');

$nurse_id = $_SESSION['user_id'];
$email = trim($_POST['email']);
$full_name = trim($_POST['full_name']);
$birthdate = $_POST['birthdate'];
$address = trim($_POST['address']);
$contact_info = trim($_POST['contact_info']);

// Check that the email matches the nurse's account in the nurses table
$stmt = $conn->prepare('SELECT email FROM nurses WHERE id = ?');
$stmt->bind_param('i', $nurse_id);
$stmt->execute();
$stmt->bind_result($account_email);
$stmt->fetch();
$stmt->close();
if ($email !== $account_email) {
    header('Location: ../templates/nurse_registration.php?error=Email+does+not+match+your+account');
    exit();
}

if (!$full_name || !$birthdate || !$address || !$contact_info) {
    header('Location: ../templates/nurse_registration.php?error=All+fields+are+required');
    exit();
}

$stmt = $conn->prepare('UPDATE nurses SET full_name = ?, birthdate = ?, address = ?, contact_info = ? WHERE id = ?');
$stmt->bind_param('ssssi', $full_name, $birthdate, $address, $contact_info, $nurse_id);
if ($stmt->execute()) {
    header('Location: ../templates/nurse_dashboard.php');
    exit();
} else {
    header('Location: ../templates/nurse_registration.php?error=Failed+to+save+profile');
    exit();
} 