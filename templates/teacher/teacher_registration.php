<?php
session_start();
require_once(__DIR__ . '/../../src/common/db.php');
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header('Location: /Web-Based%20Health-Integrated%20Student%20Information%20System/templates/register/login.php');
    exit();
}
$activePage = '';
$school_options = [
    'Tahusan Elementary School',
    'Biasong Elementary School',
    'Otama Elementary School',
    'Hinunangan West Central School'
];
$grade_levels = [
    'Kinder',
    'Grade 1',
    'Grade 2',
    'Grade 3',
    'Grade 4',
    'Grade 5',
    'Grade 6'
];
// Build a PHP array of assigned grades for each school
$assigned_grades_by_school = [];
foreach ($school_options as $school) {
    $stmt = $conn->prepare('SELECT grade_level FROM teachers WHERE elementary_school = ? AND status = "approved"');
    $stmt->bind_param('s', $school);
    $stmt->execute();
    $result = $stmt->get_result();
    $assigned_grades_by_school[$school] = [];
    while ($row = $result->fetch_assoc()) {
        $assigned_grades_by_school[$school][] = $row['grade_level'];
    }
    $stmt->close();
}
$full_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Complete Your Profile (Teacher)</h2>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-info" role="alert">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            <form action="../../src/register/register_teacher.php" method="POST" id="teacherRegForm">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" readonly required>
                </div>
                <div class="mb-3">
                    <label for="elementary_school" class="form-label">Elementary School</label>
                    <select class="form-select" id="elementary_school" name="elementary_school" required>
                        <option value="">Select School</option>
                        <?php foreach ($school_options as $school): ?>
                            <option value="<?php echo htmlspecialchars($school); ?>"><?php echo htmlspecialchars($school); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="grade_level" class="form-label">Grade Level</label>
                    <select class="form-select" id="grade_level" name="grade_level" required disabled>
                        <option value="">Select Grade Level</option>
                        <?php foreach ($grade_levels as $grade): ?>
                            <option value="<?php echo htmlspecialchars($grade); ?>"><?php echo htmlspecialchars($grade); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="mb-3">
                    <label for="contact_info" class="form-label">Contact Info</label>
                    <input type="text" class="form-control" id="contact_info" name="contact_info" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Profile</button>
            </form>
        </div>
    </div>
</div>
<script>
// Assigned grades by school from PHP
const assignedGrades = <?php echo json_encode($assigned_grades_by_school); ?>;
const gradeLevels = <?php echo json_encode($grade_levels); ?>;
const schoolSelect = document.getElementById('elementary_school');
const gradeSelect = document.getElementById('grade_level');

schoolSelect.addEventListener('change', function() {
    const selectedSchool = this.value;
    // Reset grade level dropdown
    gradeSelect.innerHTML = '<option value="">Select Grade Level</option>';
    if (!selectedSchool) {
        gradeSelect.disabled = true;
        return;
    }
    // Enable grade level dropdown
    gradeSelect.disabled = false;
    // Get assigned grades for this school
    const assigned = assignedGrades[selectedSchool] || [];
    // Add only unassigned grade levels
    gradeLevels.forEach(function(grade) {
        if (!assigned.includes(grade)) {
            const opt = document.createElement('option');
            opt.value = grade;
            opt.textContent = grade;
            gradeSelect.appendChild(opt);
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 