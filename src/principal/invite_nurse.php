<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'principal') {
    header('Location: ../templates/login.php?error=Access+denied');
    exit();
}
require_once(__DIR__ . '/../common/db.php');

$principal_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['request_id'])) {
        $request_id = intval($_POST['request_id']);
        $action = $_POST['action'];
        if ($action === 'resend') {
            // Optionally, update timestamp or send email
            header('Location: ../templates/principal_dashboard.php?msg=Request+resent');
            exit();
        } elseif ($action === 'cancel') {
            $del = $conn->prepare('DELETE FROM nurse_requests WHERE id = ?');
            $del->bind_param('i', $request_id);
            $del->execute();
            header('Location: ../templates/principal_dashboard.php?msg=Request+cancelled');
            exit();
        }
    } elseif (isset($_POST['nurse_email'], $_POST['school_id'])) {
        $nurse_email = trim($_POST['nurse_email']);
        $school_id = intval($_POST['school_id']);
        // Prevent duplicate pending requests
        $check = $conn->prepare('SELECT id FROM nurse_requests WHERE school_id = ? AND nurse_email = ? AND status = "pending"');
        $check->bind_param('is', $school_id, $nurse_email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            header('Location: ../templates/principal_dashboard.php?error=Pending+request+already+exists+for+this+nurse');
            exit();
        }
        // Insert new request
        $stmt = $conn->prepare('INSERT INTO nurse_requests (school_id, nurse_email, status) VALUES (?, ?, "pending")');
        $stmt->bind_param('is', $school_id, $nurse_email);
        if ($stmt->execute()) {
            // Optionally, send email here
            header('Location: ../templates/principal_dashboard.php?msg=Invite+sent');
            exit();
        } else {
            header('Location: ../templates/principal_dashboard.php?error=Failed+to+send+invite');
            exit();
        }
    }
}
header('Location: ../templates/principal_dashboard.php');
exit(); 