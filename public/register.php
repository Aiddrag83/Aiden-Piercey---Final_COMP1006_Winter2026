<?php
session_start();
require_once __DIR__ . '/../config.php'; // Adjust path if needed

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- reCAPTCHA verification ---
    $recaptcha_secret = "6LdfQa8sAAAAAA7B6RzKvDJF_Th-qVgQqF4B5Mrv";
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    if (!$recaptcha_response) {
        $error = "Please complete the CAPTCHA.";
    } else {
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
        $captcha_success = json_decode($verify);
        if (!$captcha_success->success) {
            $error = "CAPTCHA verification failed. Please try again.";
        }
    }

    // --- Proceed with registration if CAPTCHA passed ---
    if (empty($error)) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        if ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Check if username already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Username already taken.";
            } else {
                // Optional file upload
                $upload_file = null;
                if (isset($_FILES['profile_file']) && $_FILES['profile_file']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = __DIR__ . '/../uploads/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

                    $file_name = basename($_FILES['profile_file']['name']);
                    $target_file = $upload_dir . $file_name;

                    if (move_uploaded_file($_FILES['profile_file']['tmp_name'], $target_file)) {
                        $upload_file = $file_name;
                    } else {
                        $error = "Failed to upload file.";
                    }
                }

                if (empty($error)) {
                    // Insert user into database
                    $stmt_insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                    $stmt_insert->bind_param("ss", $username, $hashed_password);
                    $stmt_insert->execute();
                    $stmt_insert->close();

                    $success = "Registration successful! You can now <a href='login.php'>login</a>.";
                }
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h3>Register</h3>
                </div>
                <div class="card-body">
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <?php if($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                        </div>

                        <!-- Optional file upload -->
                        <div class="mb-3">
                            <label for="profile_file" class="form-label">Profile File (optional)</label>
                            <input type="file" name="profile_file" id="profile_file" class="form-control">
                        </div>

                        <!-- reCAPTCHA -->
                        <div class="mb-3">
                            <div class="g-recaptcha" data-sitekey="6LdfQa8sAAAAAIN6uxna13sADuZG0ZaFN1rNWL3W"></div>
                        </div>
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Register</button>
                        </div>
                    </form>
                    <p class="mt-3 text-center">
                        Already have an account? <a href="login.php">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>