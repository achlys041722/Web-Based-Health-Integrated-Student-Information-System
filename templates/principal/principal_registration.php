<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'principal') {
    header('Location: login.php?error=Access+denied');
    exit();
}
require_once(__DIR__ . '/../../src/common/db.php');
$principal_id = $_SESSION['user_id'];
// Fetch principal info
$stmt = $conn->prepare('SELECT full_name, email, elementary_school, contact_info, nurse_email FROM principals WHERE id = ?');
$stmt->bind_param('i', $principal_id);
$stmt->execute();
$stmt->bind_result($full_name, $email, $elementary_school, $contact_info, $nurse_email);
$stmt->fetch();
$stmt->close();
if ($full_name && $email && $elementary_school && $contact_info) {
    header('Location: principal_dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Principal Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Complete Your Profile</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($_GET['error']); ?>
                            </div>
                        <?php endif; ?>
                        <form action="../../src/register/register_principal.php" method="POST">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" readonly required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly required>
                            </div>
                            <div class="mb-3">
                                <label for="elementary_school" class="form-label">Elementary School Assigned</label>
                                <input type="text" class="form-control" id="elementary_school" name="elementary_school" value="<?php echo htmlspecialchars($elementary_school); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_info" class="form-label">Contact Info</label>
                                <input type="text" class="form-control" id="contact_info" name="contact_info" value="<?php echo htmlspecialchars($contact_info); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="nurse_email" class="form-label">School Nurse's Email (optional)</label>
                                <input type="email" class="form-control" id="nurse_email" name="nurse_email" value="<?php echo htmlspecialchars($nurse_email); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Save Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 