<?php
require '../config/db.php';
$pageTitle = 'Courses';

// --- HANDLE ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        try {
            // Auto-generate next courseID (e.g., '0006', '0007', etc.)
            $maxID = $pdo->query("SELECT COALESCE(MAX(CAST(courseID AS UNSIGNED)), 0) + 1 FROM course")->fetchColumn();
            $newCourseID = str_pad($maxID, 4, '0', STR_PAD_LEFT);
            $stmt = $pdo->prepare("INSERT INTO course (courseID, course_code, description) VALUES (?, ?, ?)");
            $stmt->execute([$newCourseID, strtoupper($_POST['course_code']), $_POST['description']]);
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

$uniqueCodes = $pdo->query("SELECT DISTINCT course_code FROM course ORDER BY course_code ASC")->fetchAll(PDO::FETCH_COLUMN);
?>

<?php include '../includes/sidebar.php'; ?>

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Course Management</h1>
            <p class="page-subtitle">Manage academic programs and courses</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
            <i class="fas fa-plus"></i>
            <span>Add Course</span>
        </button>
    </div>

    <?php if (isset($successMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire({title: 'Success', text: '<?php echo $successMsg; ?>', icon: 'success', confirmButtonColor: '#5a6578'}));</script><?php endif; ?>
    <?php if (isset($errorMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire({title: 'Error', text: '<?php echo $errorMsg; ?>', icon: 'error', confirmButtonColor: '#5a6578'}));</script><?php endif; ?>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" class="d-flex gap-3 align-items-end">
            <div style="flex: 1; max-width: 260px;">
                <label class="form-label">Filter by Course</label>
                <select name="code" class="form-select">
                    <option value="">All Courses</option>
                    <?php foreach($uniqueCodes as $code): ?>
                        <option value="<?php echo htmlspecialchars($code); ?>" <?php echo ($selectedCode === $code) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($code); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
        </form>
    </div>

    <!-- Data Table Card (Flex Fill) -->
    <div class="card flex-fill">
        <div class="table-wrapper">
            <table id="coursesTable" class="table table-hover">
                <thead>
                    <tr>
                        <th style="padding-left: 20px; width: 100px;">ID</th>
                        <th style="width: 140px;">Course</th>
                        <th>Description</th>
                        <th class="text-center" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($courses as $c): ?>
                <tr>
                    <td style="padding-left: 20px;">
                        <span class="badge-id"><?php echo str_pad($c['courseID'], 4, '0', STR_PAD_LEFT); ?></span>
                    </td>
                    <td><span class="badge-course"><?php echo htmlspecialchars($c['course_code']); ?></span></td>
                    <td><?php echo htmlspecialchars($c['description']); ?></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-outline-primary btn-sm edit-btn" 
                                data-id="<?php echo $c['courseID']; ?>"
                                data-code="<?php echo htmlspecialchars($c['course_code']); ?>"
                                data-desc="<?php echo htmlspecialchars($c['description']); ?>"
                                data-bs-toggle="modal" data-bs-target="#editCourseModal"
                                title="Edit">
                            <i class="fas fa-pen"></i>
                        </button>
                        <form method="POST" class="d-inline delete-form">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $c['courseID']; ?>">
                            <button type="button" class="btn btn-outline-danger btn-sm delete-btn" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<!-- ADD MODAL (Enlarged) -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Course</h5>
                    <small class="text-muted">Create a new academic program</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-4">
                        <label class="form-label">Course Name <span class="text-danger">*</span></label>
                        <input type="text" name="course_code" class="form-control" required maxlength="20" placeholder="e.g. BSIT, BSCS, BSHM">
                        <p class="form-text">Enter the course abbreviation or acronym</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <input type="text" name="description" class="form-control" required placeholder="e.g. Bachelor of Science in Information Technology">
                        <p class="form-text">Enter the full course title</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Course</h5>
                    <small class="text-muted">Update course information</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="courseID" id="edit_courseID">
                    <div class="mb-4">
                        <label class="form-label">Course Name <span class="text-danger">*</span></label>
                        <input type="text" name="course_code" id="edit_course_code" class="form-control" required>
                        <p class="form-text">Course abbreviation or acronym</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <input type="text" name="description" id="edit_description" class="form-control" required>
                        <p class="form-text">Full course title</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Update Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#coursesTable').DataTable({ 
            "pageLength": 10, 
            "lengthMenu": [5, 10, 25],
            "language": {
                "search": "",
                "searchPlaceholder": "Search courses...",
                "lengthMenu": "Show _MENU_"
            }
        });

        // Reset Add modal form when opened
        $('#addCourseModal').on('show.bs.modal', function() {
            $(this).find('form')[0].reset();
        });

        // Edit button
        $(document).on('click', '.edit-btn', function() {
            $('#edit_courseID').val($(this).data('id'));
            $('#edit_course_code').val($(this).data('code'));
            $('#edit_description').val($(this).data('desc'));
        });

        // Delete confirmation
        $(document).on('click', '.delete-btn', function() {
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Delete this course?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c47474',
                cancelButtonColor: '#5a6578',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel'
            }).then((result) => { if (result.isConfirmed) form.submit(); })
        });
    });
</script>
</body>
</html>
