<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Health-Integrated Student Information System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    <!-- Registration Section -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header text-center bg-success text-white">
                                <h4>Create Account</h4>
                            </div>
                            <div class="card-body">
                                <?php if (isset($_GET['reg_error'])): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo htmlspecialchars($_GET['reg_error']); ?>
                                    </div>
                                <?php elseif (isset($_GET['reg_success'])): ?>
                                    <div class="alert alert-success" role="alert">
                                        <?php echo htmlspecialchars($_GET['reg_success']); ?>
                                    </div>
                                <?php endif; ?>
                                <form action="../../src/register/register_account.php" method="POST">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="form-select" id="role" name="role" required>
                                            <option value="">Select Role</option>
                                            <option value="principal">Principal</option>
                                            <option value="teacher">Teacher</option>
                                            <option value="nurse">Nurse</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="admin_password" class="form-label">Admin Password</label>
                                        <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">Create Account</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Login Section -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header text-center">
                                <h4>Login</h4>
                            </div>
                            <div class="card-body">
                                <?php if (isset($_GET['error'])): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo htmlspecialchars($_GET['error']); ?>
                                    </div>
                                <?php endif; ?>
                                <form action="../../src/auth/login.php" method="POST">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required autofocus>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Login</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 