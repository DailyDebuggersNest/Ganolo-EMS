<?php
require '../config/db.php';
$pageTitle = 'Edit Subject';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM curriculum WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $subject = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE curriculum SET subject_code=?, description=?, year_level=?, semester=?, units=? WHERE id=?");
    $stmt->execute([
        $_POST['subject_code'], 
        $_POST['description'], 
        $_POST['year_level'], 
        $_POST['semester'], 
        $_POST['units'], 
        $_POST['id']
    ]);
    header('Location: ../pages/curriculum.php');
    exit;
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="container-fluid main-content">
    <div class="card p-4 shadow-sm" style="max-width: 600px; margin: 0 auto;">
        <h3 class="mb-4">Edit Curriculum Subject</h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $subject['id']; ?>">
            <div class="mb-3">
                <label>Subject Code</label>
                <input type="text" name="subject_code" class="form-control" value="<?php echo htmlspecialchars($subject['subject_code']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" required><?php echo htmlspecialchars($subject['description']); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Year Level</label>
                    <input type="number" name="year_level" class="form-control" value="<?php echo $subject['year_level']; ?>" min="1" max="4">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Semester</label>
                    <select name="semester" class="form-select">
                        <option value="1st" <?php if($subject['semester']=='1st') echo 'selected'; ?>>1st</option>
                        <option value="2nd" <?php if($subject['semester']=='2nd') echo 'selected'; ?>>2nd</option>
                        <option value="Summer" <?php if($subject['semester']=='Summer') echo 'selected'; ?>>Summer</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Units</label>
                    <input type="number" name="units" class="form-control" value="<?php echo $subject['units']; ?>">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update Subject</button>
                <a href="../pages/curriculum.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>