<?php
require '../config/db.php';
$pageTitle = 'Courses';

// --- HANDLE ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO course (course_code, description) VALUES (?, ?)");
            $stmt->execute([strtoupper($_POST['course_code']), $_POST['description']]);
            $successMsg = "Course added successfully!";
        } catch (PDOException $e) { $errorMsg = "Error: " . $e->getMessage(); }
    }
    elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        try {
            $stmt = $pdo->prepare("UPDATE course SET course_code=?, description=? WHERE courseID=?");
            $stmt->execute([strtoupper($_POST['course_code']), $_POST['description'], $_POST['courseID']]);
            $successMsg = "Course updated successfully!";
        } catch (PDOException $e) { $errorMsg = "Error: " . $e->getMessage(); }
    }
    elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM course WHERE courseID = ?");
            $stmt->execute([$_POST['id']]);
            $successMsg = "Course deleted successfully!";
        } catch (PDOException $e) { $errorMsg = "Error: Cannot delete this course. It might be used in curriculum."; }
    }
}

// --- FETCH DATA ---
// Always fetch data, filter if param exists
$selectedCode = $_GET['code'] ?? '';
$sql = "SELECT * FROM course WHERE 1=1";
$params = [];

if (!empty($selectedCode)) {
    $sql .= " AND course_code = ?";
    $params[] = $selectedCode;
}

$sql .= " ORDER BY courseID ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$courses = $stmt->fetchAll();

// Fetch Unique Codes for Dropdown
$uniqueCodes = $pdo->query("SELECT DISTINCT course_code FROM course ORDER BY course_code ASC")->fetchAll(PDO::FETCH_COLUMN);
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

    <!-- FILTER DROPDOWN -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light rounded">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">Filter by Course</label>
                    <select name="code" class="form-select">
                        <option value="">All Courses</option>
                        <?php foreach($uniqueCodes as $code): ?>
                            <option value="<?php echo htmlspecialchars($code); ?>" <?php echo ($selectedCode === $code) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($code); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4">
        <table id="coursesTable" class="table table-hover align-middle">
            <thead class="bg-light text-secondary">
                <tr>
                    <th>courID</th>
                    <th>COURSE</th>
                    <th>Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $c): ?>
                <tr>
                    <td class="fw-bold font-monospace text-secondary"><?php echo str_pad($c['courseID'], 4, '0', STR_PAD_LEFT); ?></td>
                    <td><span class="badge bg-primary"><?php echo htmlspecialchars($c['course_code']); ?></span></td>
                    <td><?php echo htmlspecialchars($c['description']); ?></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-warning btn-sm edit-btn" 
                                data-id="<?php echo $c['courseID']; ?>"
                                data-code="<?php echo htmlspecialchars($c['course_code']); ?>"
                                data-desc="<?php echo htmlspecialchars($c['description']); ?>"
                                data-bs-toggle="modal" data-bs-target="#editCourseModal">
                            <i class="fas fa-pen"></i>
                        </button>
                        <form method="POST" class="d-inline delete-form">
                            <input type="hidden" name="action" value="delete">
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

<!-- ADD MODAL -->
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
                        <label class="fw-bold small">Course Code</label>
                        <input type="text" name="course_code" class="form-control" required maxlength="20" placeholder="e.g. BSIT">
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small">Description</label>
                        <input type="text" name="description" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Edit Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4 bg-light">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="courseID" id="edit_courseID">
                    <div class="mb-3">
                        <label class="fw-bold small">Course Code</label>
                        <input type="text" name="course_code" id="edit_course_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small">Description</label>
                        <input type="text" name="description" id="edit_description" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning px-4">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#coursesTable').DataTable({ "pageLength": 5, "lengthMenu": [5, 10, 25, 50, 75, 100] });

        // HANDLE EDIT BUTTON CLICK
        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var code = $(this).data('code');
            var desc = $(this).data('desc');

            $('#edit_courseID').val(id);
            $('#edit_course_code').val(code);
            $('#edit_description').val(desc);
        });

        // HANDLE DELETE CONFIRMATION
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