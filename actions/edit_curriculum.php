<?php
require '../config/db.php';

// 1. GET ID & FETCH DATA (This check belongs HERE, not in the main page)
if (!isset($_GET['id'])) {
    header('Location: ../pages/curriculum.php');
    exit;
}

$id = $_GET['id'];

// Fetch the subject
$stmt = $pdo->prepare("SELECT * FROM curriculum WHERE CurriculumID = ?");
$stmt->execute([$id]);
$subject = $stmt->fetch();

if (!$subject) {
    die("Error: Subject with ID $id not found.");
}

// Fetch courses for dropdown
$courses = $pdo->query("SELECT * FROM course")->fetchAll();

// 2. HANDLE UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $pdo->prepare("UPDATE curriculum SET 
                  courseID = ?, 
                  subject_code = ?, 
                  description = ?, 
                  year_level = ?, 
                  semester = ?, 
                  units = ? 
                  WHERE CurriculumID = ?");
        
        $result = $stmt->execute([
            $_POST['courseID'],
            $_POST['subject_code'],
            $_POST['description'],
            $_POST['year_level'],
            $_POST['semester'],
            $_POST['units'],
            $_POST['id']
        ]);

        if ($result) {
            header('Location: ../pages/curriculum.php?status=updated');
            exit;
        } else {
            $errorInfo = $stmt->errorInfo();
            die("Database Error: " . $errorInfo[2]);
        }

    } catch (PDOException $e) {
        die("System Error: " . $e->getMessage());
    }
}

include '../includes/sidebar.php'; 
?>

<div class="container-fluid main-content">
    <div class="card p-4 mx-auto mt-4" style="max-width: 700px;">
        <h3 class="mb-4 fw-bold text-primary">Edit Subject</h3>
        
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $subject['CurriculumID']; ?>">
            
            <div class="mb-3">
                <label class="form-label fw-bold">Course / Program</label>
                <select name="courseID" class="form-select" required>
                    <option value="">Select Course...</option>
                    <?php foreach($courses as $c): ?>
                        <option value="<?php echo $c['courseID']; ?>" 
                            <?php if($subject['courseID'] == $c['courseID']) echo 'selected'; ?>>
                            <?php echo $c['course_code']; ?> - <?php echo $c['description']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Subject Code</label>
                <input type="text" name="subject_code" class="form-control" value="<?php echo htmlspecialchars($subject['subject_code']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="2" required><?php echo htmlspecialchars($subject['description']); ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Year Level</label>
                    <input type="number" name="year_level" class="form-control" value="<?php echo $subject['year_level']; ?>" min="1" max="4">
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Semester</label>
                    <select name="semester" class="form-select">
                        <option value="1st" <?php if($subject['semester']=='1st') echo 'selected'; ?>>1st</option>
                        <option value="2nd" <?php if($subject['semester']=='2nd') echo 'selected'; ?>>2nd</option>
                        <option value="Summer" <?php if($subject['semester']=='Summer') echo 'selected'; ?>>Summer</option>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Units</label>
                    <input type="number" name="units" class="form-control" value="<?php echo $subject['units']; ?>">
                </div>
            </div>
            
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                <a href="../pages/curriculum.php" class="btn btn-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div> 
</body>
</html>