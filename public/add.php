<?php
include("../config/database.php");
include("../includes/header.php");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize input
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $current_position = trim($_POST['current_position']);
    $skills = trim($_POST['skills']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $bio = trim($_POST['bio']);

    // Server-side validation
    if (empty($first_name)) $errors[] = "First name required.";
    if (empty($last_name)) $errors[] = "Last name required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email required.";
    if (!preg_match("/^[0-9]{10}$/", $phone)) $errors[] = "Phone must be 10 digits.";

    if (empty($errors)) {

        $stmt = $conn->prepare("INSERT INTO photos 
        (first_name, last_name, current_position, skills, email, phone, bio)
        VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssss",
            $first_name,
            $last_name,
            $current_position,
            $skills,
            $email,
            $phone,
            $bio
        );

        $stmt->execute();

        echo "<div class='alert alert-success'>Photo added successfully!</div>";
    }
}
?>

<h2>Add Photos</h2>

<?php
if (!empty($errors)) {
    echo "<div class='alert alert-danger'>";
    foreach ($errors as $error) {
        echo "<p>$error</p>";
    }
    echo "</div>";
}
?>

<form method="POST">

    <div class="mb-3">
        <label>Photo Name</label>
        <input type="text" name="first_name" class="form-control" required>
    </div>

    
    <button type="submit" class="btn btn-primary">Save Photo</button>
</form>

<?php include("../includes/footer.php"); ?>