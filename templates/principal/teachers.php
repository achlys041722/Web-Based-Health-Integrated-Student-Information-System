<?php
session_start();
require_once(__DIR__ . '/../../src/common/db.php');
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'principal') {
    header('Location: /Web-Based%20Health-Integrated%20Student%20Information%20System/templates/register/login.php');
    exit();
}
$activePage = 'teachers';
// Fetch principal's email
$stmt = $conn->prepare('SELECT email FROM principals WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($principal_email);
$stmt->fetch();
$stmt->close();
// Fetch only approved teachers for this principal
$teachers_stmt = $conn->prepare('SELECT id, full_name, email, grade_level FROM teachers WHERE principal_email = ? AND status = "approved"');
$teachers_stmt->bind_param('s', $principal_email);
$teachers_stmt->execute();
$teachers = $teachers_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teachers Management - Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/sidebar.php'; ?>
        <main class="col-lg-10 ms-sm-auto col-md-9 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Teachers Management</h2>
            </div>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Grade Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($teachers && $teachers->num_rows > 0): ?>
                    <?php while ($row = $teachers->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['grade_level']); ?></td>
                            <td>
                                <form action="../../src/principal/manage_teacher.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to permanently remove this teacher? This action cannot be undone.');">
                                    <input type="hidden" name="teacher_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">No approved teachers found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            <a href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/principal/principal_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 