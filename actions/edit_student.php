<?php
require '../config/db.php';
$pageTitle = 'Edit Student';

// 1. Fetch Student Data to Display
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $student = $stmt->fetch();
    if (!$student) die("Student not found.");
}

// 2. Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $lastname = $_POST['lastname'];
    // Ensure Lastname ends in 23
    if (substr($lastname, -2) !== '23') { $lastname .= '23'; }

    $stmt = $pdo->prepare("UPDATE students SET lastname=?, firstname=?, middlename=?, age=?, email=?, phone=? WHERE id=?");
    $stmt->execute([$lastname, $_POST['firstname'], $_POST['middlename'], $_POST['age'], $_POST['email'], $_POST['phone'], $id]);
    
    header('Location: ../pages/students.php');
    exit;
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="container-fluid main-content">
    <div class="card p-4 shadow-sm" style="max-width: 800px; margin: 0 auto;">
        <h3 class="mb-4">Edit Student</h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="lastname" class="form-control" value="<?php echo htmlspecialchars($student['lastname']); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">First Name</label>
                    <input type="text" name="firstname" class="form-control" value="<?php echo htmlspecialchars($student['firstname']); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="middlename" class="form-control" value="<?php echo htmlspecialchars($student['middlename']); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" value="<?php echo $student['age']; ?>" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($student['phone']); ?>">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Student</button>
                <a href="../pages/students.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>