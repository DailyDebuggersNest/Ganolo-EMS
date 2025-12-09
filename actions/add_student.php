<?php
require '../config/db.php';
$pageTitle = 'Add Student';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date_of_birth = $_POST['date_of_birth'];

    if (!empty($firstname) && !empty($lastname) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("INSERT INTO students (firstname, lastname, email, phone, date_of_birth) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$firstname, $lastname, $email, $phone, $date_of_birth]);
        header('Location: ../pages/students.php');
        exit;
    } else {
        $error = "Invalid input.";
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="container">
    <h1>Add Student</h1>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="firstname" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="lastname" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="mb-3">
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
        <a href="../pages/students.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>