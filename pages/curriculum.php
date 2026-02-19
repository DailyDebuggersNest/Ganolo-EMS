<?php
require '../config/db.php';
$pageTitle = 'Curriculum';

// --- HANDLE ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO curriculum (courseID, subject_code, description, year_level, semester, units) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['courseID'], $_POST['subject_code'], $_POST['description'], $_POST['year_level'], $_POST['semester'], $_POST['units']]);
            $successMsg = "Subject added successfully!";
        } catch (PDOException $e) { $errorMsg = "Error: " . $e->getMessage(); }
    }
    elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        try {
            $stmt = $pdo->prepare("UPDATE curriculum SET courseID=?, subject_code=?, description=?, year_level=?, semester=?, units=? WHERE CurriculumID=?");
            $stmt->execute([$_POST['courseID'], $_POST['subject_code'], $_POST['description'], $_POST['year_level'], $_POST['semester'], $_POST['units'], $_POST['id']]);
            $successMsg = "Subject updated successfully!";
        } catch (PDOException $e) { $errorMsg = "Error: " . $e->getMessage(); }
    }
    elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM curriculum WHERE CurriculumID = ?");
            $stmt->execute([$_POST['id']]);
            $successMsg = "Subject deleted!";
        } catch (PDOException $e) { $errorMsg = "Error: " . $e->getMessage(); }
    }
}

// --- FETCH COURSES FOR DROPDOWN ---
$courses = $pdo->query("SELECT * FROM course ORDER BY courseID ASC")->fetchAll();

// --- FILTER LOGIC ---
$selectedCourse = $_GET['courseID'] ?? '';
$selectedYear = $_GET['year_level'] ?? '';
$selectedSem = $_GET['semester'] ?? '';

$sql = "SELECT cur.*, c.course_code FROM curriculum cur LEFT JOIN course c ON cur.courseID = c.courseID WHERE 1=1";
$params = [];

if (!empty($selectedCourse)) {
    $sql .= " AND cur.courseID = ?";
    $params[] = $selectedCourse;
}
if (!empty($selectedYear)) {
    $sql .= " AND cur.year_level = ?";
    $params[] = $selectedYear;
}
if (!empty($selectedSem)) {
    $sql .= " AND cur.semester = ?";
    $params[] = $selectedSem;
}

$sql .= " ORDER BY cur.year_level, cur.semester, cur.subject_code ASC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $subjects = $stmt->fetchAll();
} catch (PDOException $e) {
    $errorMsg = "Error fetching data: " . $e->getMessage();
}
?>

<?php include '../includes/sidebar.php'; ?>

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Curriculum</h1>
            <p class="page-subtitle">Manage subjects and course curriculum</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
            <i class="fas fa-plus"></i>
            <span>Add Subject</span>
        </button>
    </div>

    <?php if (isset($successMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire({title: 'Success', text: '<?php echo $successMsg; ?>', icon: 'success', confirmButtonColor: '#5a6578'}));</script><?php endif; ?>
    <?php if (isset($errorMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire({title: 'Error', text: '<?php echo $errorMsg; ?>', icon: 'error', confirmButtonColor: '#4a5568'}));</script><?php endif; ?>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" class="d-flex gap-3 align-items-end" style="flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label">Course</label>
                <select name="courseID" class="form-select">
                    <option value="">All Courses</option>
                    <?php foreach($courses as $c): ?>
                        <option value="<?php echo $c['courseID']; ?>" <?php echo ($selectedCourse == $c['courseID']) ? 'selected' : ''; ?>>
                            <?php echo $c['course_code']; ?> - <?php echo $c['description']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="min-width: 140px;">
                <label class="form-label">Year Level</label>
                <select name="year_level" class="form-select">
                    <option value="">All Years</option>
                    <option value="1" <?php echo ($selectedYear == '1') ? 'selected' : ''; ?>>1st Year</option>
                    <option value="2" <?php echo ($selectedYear == '2') ? 'selected' : ''; ?>>2nd Year</option>
                    <option value="3" <?php echo ($selectedYear == '3') ? 'selected' : ''; ?>>3rd Year</option>
                    <option value="4" <?php echo ($selectedYear == '4') ? 'selected' : ''; ?>>4th Year</option>
                </select>
            </div>
            <div style="min-width: 140px;">
                <label class="form-label">Semester</label>
                <select name="semester" class="form-select">
                    <option value="">All Semesters</option>
                    <option value="1st" <?php echo ($selectedSem == '1st') ? 'selected' : ''; ?>>1st Sem</option>
                    <option value="2nd" <?php echo ($selectedSem == '2nd') ? 'selected' : ''; ?>>2nd Sem</option>
                    <option value="Summer" <?php echo ($selectedSem == 'Summer') ? 'selected' : ''; ?>>Summer</option>
                </select>
            </div>
            <button type="submit" name="filter" value="1" class="btn btn-secondary">
                <i class="fas fa-filter"></i>
                <span>Apply Filter</span>
            </button>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="card flex-fill">
        <div class="table-wrapper">
            <table id="curriculumTable" class="table table-hover align-middle" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <th style="padding-left: 24px; width: 80px;">ID</th>
                        <th style="width: 100px;">Course</th>
                        <th style="width: 120px;">Subject</th>
                        <th>Description</th>
                        <th style="width: 70px;">Year</th>
                        <th style="width: 80px;">Sem</th>
                        <th style="width: 70px;">Units</th>
                        <th class="text-center" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($subjects) > 0): ?>
                        <?php foreach ($subjects as $s): ?>
                        <tr>
                            <td style="padding-left: 24px;">
                                <span class="font-monospace text-muted" style="font-size: 0.8rem;"><?php echo str_pad($s['CurriculumID'], 4, '0', STR_PAD_LEFT); ?></span>
                            </td>
                            <td><span class="badge badge-course"><?php echo htmlspecialchars($s['course_code'] ?? 'N/A'); ?></span></td>
                            <td style="font-weight: 600; color: var(--text-primary, #1a202c);"><?php echo htmlspecialchars($s['subject_code']); ?></td>
                            <td><?php echo htmlspecialchars($s['description']); ?></td>
                            <td class="text-center"><?php echo $s['year_level']; ?></td>
                            <td><span class="badge badge-sem badge-sem-<?php echo strtolower($s['semester']); ?>"><?php echo $s['semester']; ?></span></td>
                            <td class="text-center"><?php echo $s['units']; ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning btn-icon edit-btn"
                                        data-id="<?php echo $s['CurriculumID']; ?>"
                                        data-courseid="<?php echo $s['courseID']; ?>"
                                        data-code="<?php echo htmlspecialchars($s['subject_code']); ?>"
                                        data-desc="<?php echo htmlspecialchars($s['description']); ?>"
                                        data-year="<?php echo $s['year_level']; ?>"
                                        data-sem="<?php echo $s['semester']; ?>"
                                        data-units="<?php echo $s['units']; ?>"
                                        data-bs-toggle="modal" data-bs-target="#editSubjectModal"
                                        title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <form method="POST" class="d-inline delete-form">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $s['CurriculumID']; ?>">
                                    <button type="button" class="btn btn-danger btn-icon delete-btn" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-book"></i>
                                    <p style="margin: 0; font-size: 0.95rem;">No subjects found</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" style="color: white;">
                    <i class="fas fa-plus-circle me-2"></i>New Curriculum Subject
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body" style="padding: 32px;">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-4">
                        <label class="form-label">Course</label>
                        <select name="courseID" class="form-select" required>
                            <option value="">Select Course...</option>
                            <?php foreach($courses as $c): ?>
                                <option value="<?php echo $c['courseID']; ?>"><?php echo $c['course_code']; ?> - <?php echo $c['description']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Subject Code</label>
                            <input type="text" name="subject_code" class="form-control" placeholder="e.g. IT 101" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control" placeholder="e.g. Introduction to Computing" required>
                        </div>
                    </div>
                    <div class="row g-4 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">Year Level</label>
                            <input type="number" name="year_level" class="form-control" min="1" max="4" placeholder="1-4" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select">
                                <option value="1st">1st Semester</option>
                                <option value="2nd">2nd Semester</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Units</label>
                            <input type="number" name="units" class="form-control" value="3" min="1" max="12" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" style="color: white;">
                    <i class="fas fa-edit me-2"></i>Edit Subject
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body" style="padding: 32px;">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-4">
                        <label class="form-label">Course</label>
                        <select name="courseID" id="edit_courseID" class="form-select" required>
                            <?php foreach($courses as $c): ?>
                                <option value="<?php echo $c['courseID']; ?>"><?php echo $c['course_code']; ?> - <?php echo $c['description']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Subject Code</label>
                            <input type="text" name="subject_code" id="edit_code" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" id="edit_desc" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-4 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">Year Level</label>
                            <input type="number" name="year_level" id="edit_year" class="form-control" min="1" max="4" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Semester</label>
                            <select name="semester" id="edit_sem" class="form-select">
                                <option value="1st">1st Semester</option>
                                <option value="2nd">2nd Semester</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Units</label>
                            <input type="number" name="units" id="edit_units" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning" style="color: white;">
                        <i class="fas fa-check me-2"></i>Update Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#curriculumTable').DataTable({ 
            "pageLength": 10, 
            "lengthMenu": [5, 10, 25, 50],
            "language": {
                "search": "",
                "searchPlaceholder": "Search subjects...",
                "lengthMenu": "Show _MENU_ entries"
            }
        });

        // Reset Add modal form when opened
        $('#addSubjectModal').on('show.bs.modal', function() {
            $(this).find('form')[0].reset();
        });

        $(document).on('click', '.edit-btn', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_courseID').val($(this).data('courseid'));
            $('#edit_code').val($(this).data('code'));
            $('#edit_desc').val($(this).data('desc'));
            $('#edit_year').val($(this).data('year'));
            $('#edit_sem').val($(this).data('sem'));
            $('#edit_units').val($(this).data('units'));
        });

        $(document).on('click', '.delete-btn', function() {
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Delete this subject?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f56565',
                cancelButtonColor: '#4a5568',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel'
            }).then((result) => { if (result.isConfirmed) form.submit(); })
        });
    });
</script>
</body>
</html>