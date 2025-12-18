<?php
require '../config/db.php';
$pageTitle = 'Courses';

// --- HANDLE ADD COURSE ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    try {
        $stmt = $pdo->prepare("INSERT INTO course (courseID, course_code, description) VALUES (?, ?, ?)");
        $stmt->execute([
            strtoupper($_POST['courseID']), 
            strtoupper($_POST['course_code']), 
            $_POST['description']
        ]);
        $successMsg = "Course added successfully!";
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            $errorMsg = "Error: Course ID already exists.";
        } else {
            $errorMsg = "Error: " . $e->getMessage();
        }
    }
}

$courses = $pdo->query("SELECT * FROM course ORDER BY courseID ASC")->fetchAll();
?>

<?php include '../includes/sidebar.php'; ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-secondary">Course Management</h2>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addCourseModal">
            <i class="fas fa-plus me-2"></i>Add Course
        </button>
    </div>

    <?php if (isset($successMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire('Success', '<?php echo $successMsg; ?>', 'success'));</script><?php endif; ?>
    <?php if (isset($errorMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire('Error', '<?php echo $errorMsg; ?>', 'error'));</script><?php endif; ?>

    <div class="card border-0 shadow-sm p-4">
        <table id="coursesTable" class="table table-hover align-middle">
            <thead class="bg-light text-secondary">
                <tr>
                    <th>course_id</th>
                    <th>Course</th>
                    <th>Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $c): ?>
                <tr>
                    <td class="fw-bold font-monospace text-secondary"><?php echo htmlspecialchars($c['courseID']); ?></td>
                    <td><span class="badge bg-primary"><?php echo htmlspecialchars($c['course_code']); ?></span></td>
                    <td><?php echo htmlspecialchars($c['description']); ?></td>
                    <td class="text-center">
                        <a href="../actions/edit_course.php?id=<?php echo $c['courseID']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                        <form method="POST" action="../actions/delete_course.php" class="d-inline delete-form">
                            <input type="hidden" name="id" value="<?php echo $c['courseID']; ?>">
                            <button type="button" class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add New Course</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4 bg-light">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="fw-bold small">Course ID (e.g., IT, HM)</label>
                        <input type="text" name="courseID" class="form-control" required maxlength="10" placeholder="e.g. IT">
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small">Course Code (e.g., BSIT, BSHM)</label>
                        <input type="text" name="course_code" class="form-control" required maxlength="20" placeholder="e.g. BSIT">
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small">Description</label>
                        <input type="text" name="description" class="form-control" required placeholder="Bachelor of Science in...">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4">Save Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#coursesTable').DataTable();
        $(document).on('click', '.delete-btn', function() {
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Delete Course?',
                text: "Warning: This might delete linked subjects!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete'
            }).then((result) => { if (result.isConfirmed) form.submit(); })
        });
    });
</script>
</body>
</html>