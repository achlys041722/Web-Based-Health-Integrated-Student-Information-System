<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header('Location: ../templates/login.php?error=Access+denied');
    exit();
}
require_once(__DIR__ . '/../common/db.php');

$teacher_id = $_SESSION['user_id'];
$full_name = trim($_POST['full_name']);
$email = trim($_POST['email']);
$grade_level = $_POST['grade_level'];
$address = trim($_POST['address']);
$contact_info = trim($_POST['contact_info']);
$principal_email = trim($_POST['principal_email']);

if (!$full_name || !$email || !$grade_level || !$address || !$contact_info || !$principal_email) {
    header('Location: ../templates/teacher_registration.php?error=All+fields+are+required');
    exit();
}

$stmt = $conn->prepare('UPDATE teachers SET full_name = ?, email = ?, grade_level = ?, address = ?, contact_info = ?, principal_email = ?, status = "pending" WHERE id = ?');
$stmt->bind_param('ssssssi', $full_name, $email, $grade_level, $address, $contact_info, $principal_email, $teacher_id);
if ($stmt->execute()) {
    header('Location: ../templates/teacher_dashboard.php');
    exit();
} else {
    header('Location: ../templates/teacher_registration.php?error=Failed+to+save+profile');
    exit();
} 