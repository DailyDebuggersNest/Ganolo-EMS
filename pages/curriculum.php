<?php
require '../config/db.php';
$pageTitle = 'Curriculum';

// --- HANDLE ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. ADD SUBJECT
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO curriculum (courseID, subject_code, description, year_level, semester, units) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['courseID'], $_POST['subject_code'], $_POST['description'], $_POST['year_level'], $_POST['semester'], $_POST['units']]);
            $successMsg = "Subject added successfully!";
        } catch (PDOException $e) { $errorMsg = "Error: " . $e->getMessage(); }
    }

    // 2. EDIT SUBJECT
    elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        try {
            $stmt = $pdo->prepare("UPDATE curriculum SET courseID=?, subject_code=?, description=?, year_level=?, semester=?, units=? WHERE CurriculumID=?");
            $stmt->execute([$_POST['courseID'], $_POST['subject_code'], $_POST['description'], $_POST['year_level'], $_POST['semester'], $_POST['units'], $_POST['id']]);
            $successMsg = "Subject updated successfully!";
        } catch (PDOException $e) { $errorMsg = "Error: " . $e->getMessage(); }
    }

    // 3. DELETE SUBJECT
    elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM curriculum WHERE CurriculumID = ?");
            $stmt->execute([$_POST['id']]);
            $successMsg = "Subject deleted!";
        } catch (PDOException $e) { $errorMsg = "Error: " . $e->getMessage(); }
    }
}

// Fetch Data
$subjects = $pdo->query("SELECT cur.*, c.course_code FROM curriculum cur LEFT JOIN course c ON cur.courseID = c.courseID ORDER BY c.courseID, cur.year_level, cur.semester ASC")->fetchAll();
$courses = $pdo->query("SELECT * FROM course")->fetchAll();
?>

<?php include '../includes/sidebar.php'; ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-secondary">Curriculum Management</h2>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
            <i class="fas fa-plus me-2"></i>Add Subject
        </button>
    </div>

    <?php if (isset($successMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire('Success', '<?php echo $successMsg; ?>', 'success'));</script><?php endif; ?>
    <?php if (isset($errorMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire('Error', '<?php echo $errorMsg; ?>', 'error'));</script><?php endif; ?>

    <div class="card border-0 shadow-sm p-4">
        <table id="curriculumTable" class="table table-hover align-middle">
            <thead class="bg-light text-secondary">
                <tr>
                    <th>Curriculum_ID</th> <th>Course</th> <th>Subject Code</th> <th>Description</th>
                    <th>Year Level</th> <th>Semester</th> <th>Units</th> <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $s): ?>
                <tr>
                    <td class="font-monospace text-muted small"><?php echo str_pad($s['CurriculumID'], 4, '0', STR_PAD_LEFT); ?></td>
                    <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($s['course_code'] ?? 'N/A'); ?></span></td>
                    <td class="fw-bold text-primary"><?php echo htmlspecialchars($s['subject_code']); ?></td>
                    <td><?php echo htmlspecialchars($s['description']); ?></td>
                    <td><?php echo $s['year_level']; ?></td>
                    <td><?php echo $s['semester']; ?></td>
                    <td><?php echo $s['units']; ?></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-warning btn-sm edit-btn"
                                data-id="<?php echo $s['CurriculumID']; ?>"
                                data-courseid="<?php echo $s['courseID']; ?>"
                                data-code="<?php echo htmlspecialchars($s['subject_code']); ?>"
                                data-desc="<?php echo htmlspecialchars($s['description']); ?>"
                                data-year="<?php echo $s['year_level']; ?>"
                                data-sem="<?php echo $s['semester']; ?>"
                                data-units="<?php echo $s['units']; ?>"
                                data-bs-toggle="modal" data-bs-target="#editSubjectModal">
                            <i class="fas fa-pen"></i>
                        </button>
                        <form method="POST" class="d-inline delete-form">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $s['CurriculumID']; ?>">
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
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">New Curriculum Subject</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4 bg-light">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="fw-bold small">Course</label>
                        <select name="courseID" class="form-select" required>
                            <option value="">Select Course...</option>
                            <?php foreach($courses as $c): ?>
                                <option value="<?php echo $c['courseID']; ?>"><?php echo $c['course_code']; ?> - <?php echo $c['description']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label class="fw-bold small">Code</label><input type="text" name="subject_code" class="form-control" required></div>
                    <div class="mb-3"><label class="fw-bold small">Description</label><input type="text" name="description" class="form-control" required></div>
                    <div class="row">
                        <div class="col-4"><label class="fw-bold small">Year</label><input type="number" name="year_level" class="form-control" min="1" max="4" required></div>
                        <div class="col-4"><label class="fw-bold small">Sem</label>
                            <select name="semester" class="form-select">
                                <option value="1st">1st</option><option value="2nd">2nd</option><option value="Summer">Summer</option>
                            </select>
                        </div>
                        <div class="col-4"><label class="fw-bold small">Units</label><input type="number" name="units" class="form-control" value="3" required></div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Edit Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4 bg-light">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="fw-bold small">Course</label>
                        <select name="courseID" id="edit_courseID" class="form-select" required>
                            <?php foreach($courses as $c): ?>
                                <option value="<?php echo $c['courseID']; ?>"><?php echo $c['course_code']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label class="fw-bold small">Code</label><input type="text" name="subject_code" id="edit_code" class="form-control" required></div>
                    <div class="mb-3"><label class="fw-bold small">Description</label><input type="text" name="description" id="edit_desc" class="form-control" required></div>
                    <div class="row">
                        <div class="col-4"><label class="fw-bold small">Year</label><input type="number" name="year_level" id="edit_year" class="form-control" min="1" max="4" required></div>
                        <div class="col-4"><label class="fw-bold small">Sem</label>
                            <select name="semester" id="edit_sem" class="form-select">
                                <option value="1st">1st</option><option value="2nd">2nd</option><option value="Summer">Summer</option>
                            </select>
                        </div>
                        <div class="col-4"><label class="fw-bold small">Units</label><input type="number" name="units" id="edit_units" class="form-control" required></div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#curriculumTable').DataTable({ "pageLength": 5, "lengthMenu": [5, 10, 25, 50, 75, 100] });

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
                title: 'Delete Subject?', text: "Undoing is impossible.", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Yes, delete'
            }).then((result) => { if (result.isConfirmed) form.submit(); })
        });
    });
</script>
</body>
</html>