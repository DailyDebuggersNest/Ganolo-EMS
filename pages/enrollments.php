<?php
require '../config/db.php';
$pageTitle = 'Enrollments';

// --- HANDLE ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. ADD ENROLLMENT
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, subject_id) VALUES (?, ?)");
            $stmt->execute([$_POST['student_id'], $_POST['subject_id']]);
            $successMsg = "Student enrolled successfully!";
        } catch (PDOException $e) { $errorMsg = ($e->errorInfo[1] == 1062) ? "Already enrolled in this subject." : "Error: " . $e->getMessage(); }
    }

    // 2. EDIT ENROLLMENT
    elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        try {
            $stmt = $pdo->prepare("UPDATE enrollments SET student_id = ?, subject_id = ? WHERE id = ?");
            $stmt->execute([$_POST['student_id'], $_POST['subject_id'], $_POST['id']]);
            $successMsg = "Enrollment updated successfully!";
        } catch (PDOException $e) { $errorMsg = "Error: " . $e->getMessage(); }
    }

    // 3. DELETE ENROLLMENT
    elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM enrollments WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $successMsg = "Enrollment removed.";
    }
}

// Fetch Data
$enrollments = $pdo->query("SELECT e.id, e.student_id, e.subject_id, s.firstname, s.lastname, c.subject_code, c.description, e.enrolled_at 
                            FROM enrollments e 
                            JOIN students s ON e.student_id = s.id 
                            JOIN curriculum c ON e.subject_id = c.CurriculumID 
                            ORDER BY e.enrolled_at DESC")->fetchAll();
$students = $pdo->query("SELECT id, firstname, lastname FROM students ORDER BY lastname ASC")->fetchAll();
$subjects = $pdo->query("SELECT CurriculumID, subject_code, description FROM curriculum ORDER BY year_level, semester ASC")->fetchAll();
?>

<?php include '../includes/sidebar.php'; ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-secondary">Enrollments Management</h2>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-2"></i>New Enrollment
        </button>
    </div>

    <?php if (isset($successMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire('Success', '<?php echo $successMsg; ?>', 'success'));</script><?php endif; ?>
    <?php if (isset($errorMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire('Error', '<?php echo $errorMsg; ?>', 'error'));</script><?php endif; ?>

    <div class="card border-0 shadow-sm p-4">
        <table id="enrollmentTable" class="table table-hover align-middle">
            <thead class="bg-light text-secondary">
                <tr>
                    <th>ID</th> <th>Student Name</th> <th>Subject</th> <th>Description</th> <th>Date Enrolled</th> <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($enrollments as $row): ?>
                    <tr>
                        <td class="text-muted small font-monospace"><?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                        <td class="fw-bold"><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary border border-primary"><?php echo htmlspecialchars($row['subject_code']); ?></span></td>
                        <td class="text-muted small"><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['enrolled_at'])); ?></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-sm edit-btn"
                                    data-id="<?php echo $row['id']; ?>"
                                    data-student="<?php echo $row['student_id']; ?>"
                                    data-subject="<?php echo $row['subject_id']; ?>"
                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form method="POST" class="d-inline delete-form">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
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
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Enroll Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Student</label>
                        <select name="student_id" class="form-select" required>
                            <option value="">Choose Student...</option>
                            <?php foreach ($students as $s): ?>
                                <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['lastname'] . ', ' . $s['firstname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject</label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">Choose Subject...</option>
                            <?php foreach ($subjects as $s): ?>
                                <option value="<?php echo $s['CurriculumID']; ?>"><?php echo htmlspecialchars($s['subject_code'] . ' - ' . $s['description']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Enroll</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Edit Enrollment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Student</label>
                        <select name="student_id" id="edit_student" class="form-select" required>
                            <?php foreach ($students as $s): ?>
                                <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['lastname'] . ', ' . $s['firstname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject</label>
                        <select name="subject_id" id="edit_subject" class="form-select" required>
                            <?php foreach ($subjects as $s): ?>
                                <option value="<?php echo $s['CurriculumID']; ?>"><?php echo htmlspecialchars($s['subject_code'] . ' - ' . $s['description']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#enrollmentTable').DataTable({ "pageLength": 5, "lengthMenu": [5, 10, 25, 50, 75, 100], "language": { "search": "", "searchPlaceholder": "Filter..." } });

        $(document).on('click', '.edit-btn', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_student').val($(this).data('student'));
            $('#edit_subject').val($(this).data('subject'));
        });

        $(document).on('click', '.delete-btn', function() {
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Unenroll Student?', text: "Remove student from subject?", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, remove'
            }).then((result) => { if (result.isConfirmed) form.submit(); })
        });
    });
</script>
</body>
</html>