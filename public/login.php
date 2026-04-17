<?php
session_start();
require_once __DIR__ . '/../config.php'; // Make sure config.php path is correct

$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- reCAPTCHA verification ---
    $recaptcha_secret = "6LdfQa8sAAAAAA7B6RzKvDJF_Th-qVgQqF4B5Mrv"; // Your secret key
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

    // --- Proceed with login if CAPTCHA passed ---
    if (empty($error)) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h3>Login</h3>
                </div>
                <div class="card-body">
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>

                        <!-- reCAPTCHA -->
                        <div class="mb-3">
                            <div class="g-recaptcha" data-sitekey="6LdfQa8sAAAAAIN6uxna13sADuZG0ZaFN1rNWL3W"></div>
                        </div>
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                    <p class="mt-3 text-center">
                        Don't have an account? <a href="register.php">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>