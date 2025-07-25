<?php
session_start();
require_once(__DIR__ . '/../common/db.php');
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'principal') {
    header('Location: /Web-Based%20Health-Integrated%20Student%20Information%20System/templates/register/login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacher_id'], $_POST['action'])) {
    $teacher_id = intval($_POST['teacher_id']);
    $action = $_POST['action'];
    if ($action === 'approve') {
        $stmt = $conn->prepare('UPDATE teachers SET status = "approved" WHERE id = ?');
        $stmt->bind_param('i', $teacher_id);
        $stmt->execute();
    } elseif ($action === 'reject') {
        $stmt = $conn->prepare('UPDATE teachers SET status = "rejected" WHERE id = ?');
        $stmt->bind_param('i', $teacher_id);
        $stmt->execute();
    } elseif ($action === 'remove') {
        $stmt = $conn->prepare('DELETE FROM teachers WHERE id = ?');
        $stmt->bind_param('i', $teacher_id);
        $stmt->execute();
    }
    header('Location: /Web-Based%20Health-Integrated%20Student%20Information%20System/templates/principal/teachers.php');
    exit();
} else {
    header('Location: /Web-Based%20Health-Integrated%20Student%20Information%20System/templates/principal/teachers.php');
    exit();
} 