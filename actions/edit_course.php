<?php
require '../config/db.php';

// 1. Safety Check
if (!isset($_GET['id'])) {
    header('Location: ../pages/courses.php');
    exit;
}

$id = $_GET['id'];

// 2. Fetch Course
$stmt = $pdo->prepare("SELECT * FROM course WHERE courseID = ?");
$stmt->execute([$id]);
$course = $stmt->fetch();

if (!$course) die("Course not found.");

// 3. Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Note: We don't update courseID (the key) to avoid breaking links
    $stmt = $pdo->prepare("UPDATE course SET course_code=?, description=? WHERE courseID=?");
    $stmt->execute([
        $_POST['course_code'], 
        $_POST['description'], 
        $id
    ]);

    header('Location: ../pages/courses.php?updated=true');
    exit;
}

include '../includes/sidebar.php'; 
?>

<div class="container-fluid main-content">
    <div class="card p-4 mx-auto mt-4" style="max-width: 600px;">
        <h3 class="mb-4 fw-bold text-primary">Edit Course</h3>
        
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Course ID</label>
                <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($course['courseID']); ?>" readonly>
                <div class="form-text text-muted">You cannot change the unique ID.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Course Code</label>
                <input type="text" name="course_code" class="form-control" value="<?php echo htmlspecialchars($course['course_code']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($course['description']); ?></textarea>
            </div>
            
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                <a href="../pages/courses.php" class="btn btn-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</body>
</html>