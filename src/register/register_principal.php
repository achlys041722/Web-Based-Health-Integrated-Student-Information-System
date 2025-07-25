<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'principal') {
    header('Location: ../templates/login.php?error=Access+denied');
    exit();
}
require_once(__DIR__ . '/../common/db.php');

$principal_id = $_SESSION['user_id'];
$full_name = trim($_POST['full_name']);
$email = trim($_POST['email']);
$elementary_school = trim($_POST['elementary_school']);
$contact_info = trim($_POST['contact_info']);
$nurse_email = trim($_POST['nurse_email']);

if (!$full_name || !$email || !$elementary_school || !$contact_info) {
    header('Location: ../templates/principal_registration.php?error=All+required+fields+must+be+filled');
    exit();
}

$stmt = $conn->prepare('UPDATE principals SET full_name = ?, email = ?, elementary_school = ?, contact_info = ?, nurse_email = ? WHERE id = ?');
$stmt->bind_param('sssssi', $full_name, $email, $elementary_school, $contact_info, $nurse_email, $principal_id);
if ($stmt->execute()) {
    header('Location: ../templates/principal_dashboard.php');
    exit();
} else {
    header('Location: ../templates/principal_registration.php?error=Failed+to+save+profile');
    exit();
} 