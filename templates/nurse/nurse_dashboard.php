<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'nurse') {
    header('Location: login.php?error=Access+denied');
    exit();
}
require_once(__DIR__ . '/../../src/common/db.php');
$nurse_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT full_name, birthdate, address, contact_info FROM nurses WHERE id = ?');
$stmt->bind_param('i', $nurse_id);
$stmt->execute();
$stmt->bind_result($full_name, $birthdate, $address, $contact_info);
$stmt->fetch();
$stmt->close();
if (!$full_name || !$birthdate || !$address || !$contact_info) {
    header('Location: nurse_registration.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nurse Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?> (School Nurse)</h2>
        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
        <!-- Nurse dashboard content goes here -->
        <?php
        // Fetch nurse's email
        $nurse_email = strtolower(trim($_SESSION['email']));
        // Fetch pending principal requests (join nurse_requests -> schools -> principals)
        $pending_stmt = $conn->prepare('
            SELECT nr.id, p.full_name AS principal_name, p.email AS principal_email, s.name AS school_name
            FROM nurse_requests nr
            JOIN schools s ON nr.school_id = s.id
            JOIN principals p ON s.principal_id = p.id
            WHERE nr.nurse_email = ? AND nr.status = "pending"'
        );
        $pending_stmt->bind_param('s', $nurse_email);
        $pending_stmt->execute();
        $pending_requests = $pending_stmt->get_result();
        ?>
        <div class="mt-4">
            <h4>Pending Principal Requests</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Principal Name</th>
                        <th>Principal Email</th>
                        <th>School</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $pending_requests->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['principal_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['principal_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['school_name']); ?></td>
                            <td>
                                <form action="../../src/nurse/nurse_request_action.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="action" value="accept">
                                    <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                </form>
                                <form action="../../src/nurse/nurse_request_action.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
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