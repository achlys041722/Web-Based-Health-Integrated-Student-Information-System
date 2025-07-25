<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header('Location: login.php?error=Access+denied');
    exit();
}
require_once(__DIR__ . '/../../src/common/db.php');
$teacher_id = $_SESSION['user_id'];
// Fetch teacher info
$stmt = $conn->prepare('SELECT full_name, email, grade_level, address, contact_info, principal_email, status FROM teachers WHERE id = ?');
$stmt->bind_param('i', $teacher_id);
$stmt->execute();
$stmt->bind_result($full_name, $email, $grade_level, $address, $contact_info, $principal_email, $status);
$stmt->fetch();
$stmt->close();
if ($full_name && $email && $grade_level && $address && $contact_info && $principal_email && $status !== 'pending') {
    header('Location: teacher_dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Teacher Profile</title>
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
                        <form action="../../src/register/register_teacher.php" method="POST">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" readonly required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly required>
                            </div>
                            <div class="mb-3">
                                <label for="grade_level" class="form-label">Grade Level</label>
                                <select class="form-select" id="grade_level" name="grade_level" required>
                                    <option value="">Select Grade Level</option>
                                    <option value="Kinder" <?php if($grade_level==="Kinder") echo "selected"; ?>>Kinder</option>
                                    <option value="Grade 1" <?php if($grade_level==="Grade 1") echo "selected"; ?>>Grade 1</option>
                                    <option value="Grade 2" <?php if($grade_level==="Grade 2") echo "selected"; ?>>Grade 2</option>
                                    <option value="Grade 3" <?php if($grade_level==="Grade 3") echo "selected"; ?>>Grade 3</option>
                                    <option value="Grade 4" <?php if($grade_level==="Grade 4") echo "selected"; ?>>Grade 4</option>
                                    <option value="Grade 5" <?php if($grade_level==="Grade 5") echo "selected"; ?>>Grade 5</option>
                                    <option value="Grade 6" <?php if($grade_level==="Grade 6") echo "selected"; ?>>Grade 6</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Personal Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_info" class="form-label">Contact Info</label>
                                <input type="text" class="form-control" id="contact_info" name="contact_info" value="<?php echo htmlspecialchars($contact_info); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="principal_email" class="form-label">Principal's Email</label>
                                <input type="email" class="form-control" id="principal_email" name="principal_email" value="<?php echo htmlspecialchars($principal_email); ?>" required>
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