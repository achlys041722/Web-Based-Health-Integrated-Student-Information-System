<?php
session_start();
require_once(__DIR__ . '/../common/db.php');
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header('Location: /Web-Based%20Health-Integrated%20Student%20Information%20System/templates/register/login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    if (!isset($_POST['elementary_school']) || !isset($_POST['grade_level'])) {
        header('Location: /Web-Based%20Health-Integrated%20Student%20Information%20System/templates/teacher/teacher_registration.php?error=Please+select+an+elementary+school+and+grade+level.');
        exit();
    }
    $elementary_school = trim($_POST['elementary_school']);
    $grade_level = trim($_POST['grade_level']);
    $address = trim($_POST['address']);
    $contact_info = trim($_POST['contact_info']);
    // Find the principal for the selected school
    $stmt = $conn->prepare('SELECT email FROM principals WHERE elementary_school = ?');
    $stmt->bind_param('s', $elementary_school);
    $stmt->execute();
    $stmt->bind_result($principal_email);
    $stmt->fetch();
    $stmt->close();
    if (!$principal_email) {
        header('Location: /Web-Based%20Health-Integrated%20Student%20Information%20System/templates/teacher/teacher_registration.php?error=No+principal+found+for+the+selected+school.');
        exit();
    }
    $stmt = $conn->prepare('UPDATE teachers SET full_name = ?, elementary_school = ?, grade_level = ?, address = ?, contact_info = ?, principal_email = ?, status = "pending" WHERE id = ?');
    $stmt->bind_param('ssssssi', $full_name, $elementary_school, $grade_level, $address, $contact_info, $principal_email, $_SESSION['user_id']);
    $stmt->execute();
    $_SESSION['full_name'] = $full_name;
    header('Location: /Web-Based%20Health-Integrated%20Student%20Information%20System/templates/teacher/teacher_registration.php?error=Your+account+is+pending+approval+by+the+principal.');
    exit();
} else {
    header('Location: /Web-Based%20Health-Integrated%20Student%20Information%20System/templates/teacher/teacher_registration.php');
    exit();
} 