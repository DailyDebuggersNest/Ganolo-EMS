<?php
require '../config/db.php';
$pageTitle = 'Edit Course';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$id]);
$course = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $credits = $_POST['credits'];

    if (!empty($title)) {
        $stmt = $pdo->prepare("UPDATE courses SET title = ?, description = ?, credits = ? WHERE id = ?");
        $stmt->execute([$title, $description, $credits, $id]);
        header('Location: ../pages/courses.php');
        exit;
    } else {
        $error = "Title is required.";
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="container">
    <h1>Edit Course</h1>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($course['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"><?php echo htmlspecialchars($course['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label>Credits</label>
            <input type="number" name="credits" class="form-control" value="<?php echo $course['credits']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="../pages/courses.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>