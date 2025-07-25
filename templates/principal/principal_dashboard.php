<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'principal') {
    header('Location: login.php?error=Access+denied');
    exit();
}
require_once(__DIR__ . '/../../src/common/db.php');
$principal_id = $_SESSION['user_id'];
$school_check = $conn->prepare('SELECT id FROM schools WHERE principal_id = ?');
$school_check->bind_param('i', $principal_id);
$school_check->execute();
$school_check->store_result();
if ($school_check->num_rows === 0) {
    header('Location: school_registration.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Principal Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?> (Principal)</h2>
        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
        <!-- Principal dashboard content goes here -->
        <?php
        // Fetch the principal's school
        $school_stmt = $conn->prepare('SELECT id, name, lrn FROM schools WHERE principal_id = ?');
        $school_stmt->bind_param('i', $principal_id);
        $school_stmt->execute();
        $school_result = $school_stmt->get_result();
        $school = $school_result->fetch_assoc();
        $school_id = $school['id'];

        // Fetch nurse requests for this school
        $requests_stmt = $conn->prepare('SELECT id, nurse_email, status FROM nurse_requests WHERE school_id = ?');
        $requests_stmt->bind_param('i', $school_id);
        $requests_stmt->execute();
        $requests_result = $requests_stmt->get_result();

        // Fetch principal's email
        $stmt = $conn->prepare('SELECT email FROM principals WHERE id = ?');
        $stmt->bind_param('i', $principal_id);
        $stmt->execute();
        $stmt->bind_result($principal_email);
        $stmt->fetch();
        $stmt->close();
        // Fetch pending teachers for this principal
        $pending_stmt = $conn->prepare('SELECT id, full_name, email, grade_level, address, contact_info FROM teachers WHERE principal_email = ? AND status = "pending"');
        $pending_stmt->bind_param('s', $principal_email);
        $pending_stmt->execute();
        $pending_teachers = $pending_stmt->get_result();
        ?>
        <div class="mt-4">
            <h4>Invite School Nurse</h4>
            <div class="alert alert-warning">
                Warning: All student and school data will be shared with the invited nurse upon acceptance.
            </div>
            <form action="../../src/principal/invite_nurse.php" method="POST" class="mb-3">
                <input type="hidden" name="school_id" value="<?php echo $school_id; ?>">
                <div class="mb-3">
                    <label for="nurse_email" class="form-label">Nurse Email</label>
                    <input type="email" class="form-control" id="nurse_email" name="nurse_email" required>
                </div>
                <button type="submit" class="btn btn-primary">Send Invite</button>
            </form>
            <h5>Current Nurse Requests</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $requests_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nurse_email']); ?></td>
                            <td><?php echo ucfirst($row['status']); ?></td>
                            <td>
                                <?php if ($row['status'] === 'pending'): ?>
                                    <form action="../../src/principal/invite_nurse.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="action" value="resend">
                                        <button type="submit" class="btn btn-sm btn-secondary">Resend</button>
                                    </form>
                                    <form action="../../src/principal/invite_nurse.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="action" value="cancel">
                                        <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <h4>Pending Teacher Requests</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Grade Level</th>
                        <th>Address</th>
                        <th>Contact Info</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $pending_teachers->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['grade_level']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact_info']); ?></td>
                            <td>
                                <form action="../../src/principal/teacher_approval.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="teacher_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <form action="../../src/principal/teacher_approval.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="teacher_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html> 