<?php
session_start();
require_once(__DIR__ . '/../../src/common/db.php');
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header('Location: /Web-Based%20Health-Integrated%20Student%20Information%20System/templates/register/login.php');
    exit();
}
$activePage = 'my_students';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Students - Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            margin-bottom: 1.5rem;
        }
        @media (min-width: 992px) {
            .form-section {
                display: flex;
                gap: 2rem;
            }
            .form-section > .card {
                flex: 1 1 0;
            }
        }
        .form-label, .form-check-label, .card-header {
            font-size: 0.95rem;
        }
        .form-control, .form-select {
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/sidebar.php'; ?>
        <main class="col-lg-10 ms-sm-auto col-md-9 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2 class="mb-0">My Students</h2>
                <button type="button" class="btn btn-success" id="showStudentFormBtn">Add Student</button>
            </div>
            <form method="POST" action="#" id="studentForm" style="display:none;">
                <div class="form-section">
                    <!-- Personal Info Card -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">Personal Information</div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" name="middle_name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">LRN</label>
                                    <input type="text" class="form-control" name="lrn" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Birthdate</label>
                                    <input type="date" class="form-control" name="birthdate" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sex</label>
                                    <select class="form-select" name="sex" required>
                                        <option value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Height (cm)</label>
                                    <input type="number" step="0.01" class="form-control" name="height">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Weight (kg)</label>
                                    <input type="number" step="0.01" class="form-control" name="weight">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name of Parent</label>
                                <input type="text" class="form-control" name="parent_name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Grade Level</label>
                                <select class="form-select" name="grade_level" required>
                                    <option value="">Select</option>
                                    <option value="Kinder">Kinder</option>
                                    <option value="Grade 1">Grade 1</option>
                                    <option value="Grade 2">Grade 2</option>
                                    <option value="Grade 3">Grade 3</option>
                                    <option value="Grade 4">Grade 4</option>
                                    <option value="Grade 5">Grade 5</option>
                                    <option value="Grade 6">Grade 6</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Health & Nutritional Assessment Card -->
                    <div class="card">
                        <div class="card-header bg-success text-white">Health & Nutritional Assessment</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Nutritional Status</label>
                                <input type="text" class="form-control" name="nutritional_status">
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">BMI</label>
                                    <input type="number" step="0.01" class="form-control" name="bmi">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date of Examination</label>
                                    <input type="date" class="form-control" name="date_of_exam">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Height-for-age</label>
                                    <select class="form-select" name="height_for_age">
                                        <option value="">Select</option>
                                        <option value="Severely Stunted">Severely Stunted</option>
                                        <option value="Stunted">Stunted</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Tall">Tall</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Weight-for-age</label>
                                    <select class="form-select" name="weight_for_age">
                                        <option value="">Select</option>
                                        <option value="Severely Stunted">Severely Stunted</option>
                                        <option value="Stunted">Stunted</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Overweight">Overweight</option>
                                        <option value="Obese">Obese</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">4Ps Beneficiary</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="four_ps_beneficiary" value="1">
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="four_ps_beneficiary" value="0">
                                        <label class="form-check-label">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Immunization</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>MR</label>
                                        <select class="form-select" name="immunization_mr">
                                            <option value="None">None</option>
                                            <option value="1st dose">1st dose</option>
                                            <option value="2nd dose">2nd dose</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>TD</label>
                                        <select class="form-select" name="immunization_td">
                                            <option value="None">None</option>
                                            <option value="1st dose">1st dose</option>
                                            <option value="2nd dose">2nd dose</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>HPV (Grade 4 Females Only)</label>
                                        <select class="form-select" name="immunization_hpv">
                                            <option value="None">None</option>
                                            <option value="1st dose">1st dose</option>
                                            <option value="2nd dose">2nd dose</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deworming</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="deworming_1st" value="1" id="deworming1">
                                    <label class="form-check-label" for="deworming1">1st Dose</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="deworming_2nd" value="1" id="deworming2">
                                    <label class="form-check-label" for="deworming2">2nd Dose</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ailments</label>
                                <input type="text" class="form-control" name="ailments">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Intervention</label>
                                <select class="form-select" name="intervention">
                                    <option value="">Select</option>
                                    <option value="Treatment">Treatment</option>
                                    <option value="Referral">Referral</option>
                                    <option value="None">None</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Allergies</label>
                                <input type="text" class="form-control" name="allergies">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <input type="text" class="form-control" name="status">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" name="remarks" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
            <a href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/teacher/teacher_dashboard.php" class="btn btn-outline-secondary mt-3">Back to Dashboard</a>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('showStudentFormBtn').addEventListener('click', function() {
        var form = document.getElementById('studentForm');
        if (form.style.display === 'none') {
            form.style.display = 'block';
            this.textContent = 'Hide Form';
            this.classList.remove('btn-success');
            this.classList.add('btn-secondary');
        } else {
            form.style.display = 'none';
            this.textContent = 'Add Student';
            this.classList.remove('btn-secondary');
            this.classList.add('btn-success');
        }
    });
</script>
</body>
</html> 