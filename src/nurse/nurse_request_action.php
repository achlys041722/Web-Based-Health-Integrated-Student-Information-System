<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'nurse') {
    header('Location: ../templates/login.php?error=Access+denied');
    exit();
}
require_once(__DIR__ . '/../common/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $request_id = intval($_POST['request_id']);
    $action = $_POST['action'];
    // Get the nurse_request info
    $stmt = $conn->prepare('SELECT school_id, nurse_email FROM nurse_requests WHERE id = ?');
    $stmt->bind_param('i', $request_id);
    $stmt->execute();
    $stmt->bind_result($school_id, $nurse_email);
    $stmt->fetch();
    $stmt->close();
    if ($action === 'accept') {
        // Set request to accepted
        $update = $conn->prepare('UPDATE nurse_requests SET status = "accepted" WHERE id = ?');
        $update->bind_param('i', $request_id);
        $update->execute();
        // Link nurse to school (insert into nurse_schools)
        $nurse_id = $_SESSION['user_id'];
        $check = $conn->prepare('SELECT id FROM nurse_schools WHERE nurse_id = ? AND school_id = ?');
        $check->bind_param('ii', $nurse_id, $school_id);
        $check->execute();
        $check->store_result();
        if ($check->num_rows === 0) {
            $insert = $conn->prepare('INSERT INTO nurse_schools (nurse_id, school_id) VALUES (?, ?)');
            $insert->bind_param('ii', $nurse_id, $school_id);
            $insert->execute();
        }
        header('Location: ../templates/nurse_dashboard.php?msg=Request+accepted');
        exit();
    } elseif ($action === 'reject') {
        $update = $conn->prepare('UPDATE nurse_requests SET status = "rejected" WHERE id = ?');
        $update->bind_param('i', $request_id);
        $update->execute();
        header('Location: ../templates/nurse_dashboard.php?msg=Request+rejected');
        exit();
    } else {
        header('Location: ../templates/nurse_dashboard.php?error=Invalid+action');
        exit();
    }
} else {
    header('Location: ../templates/nurse_dashboard.php?error=Invalid+request');
    exit();
} 