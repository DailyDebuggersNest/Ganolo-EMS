<?php
require '../config/db.php';
$pageTitle = 'Edit Enrollment';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM enrollments WHERE id = ?");
$stmt->execute([$id]);
$enrollment = $stmt->fetch();

// Fetch students and curriculum for dropdowns
$students = $pdo->query("SELECT id, firstname, lastname FROM students")->fetchAll();
$curriculum = $pdo->query("SELECT id, subject_code FROM curriculum")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];

    if (!empty($student_id) && !empty($course_id)) {
        $stmt = $pdo->prepare("UPDATE enrollments SET student_id = ?, course_id = ? WHERE id = ?");
        $stmt->execute([$student_id, $course_id, $id]);
        header('Location: ../pages/enrollments.php');
        exit;
    } else {
        $error = "Both student and course are required.";
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="container">
    <h1>Edit Enrollment</h1>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Student</label>
            <select name="student_id" class="form-control" required>
                <option value="">Select Student</option>
                <?php foreach ($students as $student): ?>
                    <option value="<?php echo $student['id']; ?>" <?php if ($student['id'] == $enrollment['student_id']) echo 'selected'; ?>><?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Course</label>
            <select name="course_id" class="form-control" required>
                <option value="">Select Course</option>
                <?php foreach ($curriculum as $curriculum): ?>
                    <option value="<?php echo $curriculum['id']; ?>" <?php if ($curriculum['id'] == $enrollment['course_id']) echo 'selected'; ?>><?php echo htmlspecialchars($curriculum['subject_code']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="../pages/enrollments.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>