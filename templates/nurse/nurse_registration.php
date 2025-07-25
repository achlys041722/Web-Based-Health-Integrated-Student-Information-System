<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'nurse') {
    header('Location: login.php?error=Access+denied');
    exit();
}
require_once(__DIR__ . '/../../src/common/db.php');
$nurse_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
// Check if profile is already complete
$stmt = $conn->prepare('SELECT full_name, birthdate, address, contact_info FROM nurses WHERE id = ?');
$stmt->bind_param('i', $nurse_id);
$stmt->execute();
$stmt->bind_result($full_name, $birthdate, $address, $contact_info);
$stmt->fetch();
$stmt->close();
if ($full_name && $birthdate && $address && $contact_info) {
    header('Location: nurse_dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Nurse Profile</title>
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
                        <form action="../../src/register/register_nurse.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly required>
                            </div>
                            <div class="mb-3">
                                <label for="full_name" class="form-label">School Nurse Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="birthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_info" class="form-label">Contact Info</label>
                                <input type="text" class="form-control" id="contact_info" name="contact_info" required>
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