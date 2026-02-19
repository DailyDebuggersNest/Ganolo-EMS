<?php
require '../config/db.php';
$pageTitle = 'Student List';

// --- HANDLE ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO students (lastname, firstname, middlename, age) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['lastname'], $_POST['firstname'], $_POST['middlename'], $_POST['age']]);
            $successMsg = "Student added successfully!";
        } catch (PDOException $e) { $errorMsg = "Error: " . $e->getMessage(); }
    }

    elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        try {
            $stmt = $pdo->prepare("UPDATE students SET lastname=?, firstname=?, middlename=?, age=? WHERE id=?");
            $stmt->execute([$_POST['lastname'], $_POST['firstname'], $_POST['middlename'], $_POST['age'], $_POST['id']]);
            $successMsg = "Student updated successfully!";
        } catch (PDOException $e) { $errorMsg = "Error: " . $e->getMessage(); }
    }

    elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $successMsg = "Student record deleted!";
        } catch (PDOException $e) { $errorMsg = "Error: Cannot delete student. They might be enrolled."; }
    }
}

$students = $pdo->query("SELECT * FROM students ORDER BY lastname ASC")->fetchAll();
?>

<?php include '../includes/sidebar.php'; ?>

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Student List</h1>
            <p class="page-subtitle">Manage student records and information</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <i class="fas fa-user-plus"></i>
            <span>Add Student</span>
        </button>
    </div>

    <?php if (isset($successMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire({title: 'Success', text: '<?php echo $successMsg; ?>', icon: 'success', confirmButtonColor: '#5a6578'}));</script><?php endif; ?>
    <?php if (isset($errorMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire({title: 'Error', text: '<?php echo $errorMsg; ?>', icon: 'error', confirmButtonColor: '#5a6578'}));</script><?php endif; ?>

    <!-- Data Table Card (Flex Fill) -->
    <div class="card flex-fill">
        <div class="table-wrapper">
            <table id="studentsTable" class="table table-hover">
                <thead>
                    <tr>
                        <th style="padding-left: 20px; width: 80px;">ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th style="width: 70px;">Age</th>
                        <th class="text-center" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                    <tr>
                        <td style="padding-left: 20px;">
                            <span class="badge-id"><?php echo str_pad($s['id'], 4, '0', STR_PAD_LEFT); ?></span>
                        </td>
                        <td class="fw-semibold"><?php echo htmlspecialchars($s['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($s['firstname']); ?></td>
                        <td class="text-muted"><?php echo htmlspecialchars($s['middlename']); ?></td>
                        <td><?php echo $s['age']; ?></td>
                        <td class="text-center">
                            <a href="student_profile.php?id=<?php echo $s['id']; ?>" class="btn btn-outline-primary btn-sm" title="View Profile">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-outline-primary btn-sm edit-btn"
                                    data-id="<?php echo $s['id']; ?>"
                                    data-lastname="<?php echo htmlspecialchars($s['lastname']); ?>"
                                    data-firstname="<?php echo htmlspecialchars($s['firstname']); ?>"
                                    data-middlename="<?php echo htmlspecialchars($s['middlename']); ?>"
                                    data-age="<?php echo $s['age']; ?>"
                                    data-bs-toggle="modal" data-bs-target="#editStudentModal"
                                    title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form method="POST" class="d-inline delete-form">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
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

<!-- ADD MODAL -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add New Student</h5>
                    <small class="text-muted">Create a new student record</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="lastname" class="form-control" required placeholder="e.g. Dela Cruz">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="firstname" class="form-control" required placeholder="e.g. Juan">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middlename" class="form-control" placeholder="Optional">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Age <span class="text-danger">*</span></label>
                            <input type="number" name="age" class="form-control" min="15" max="80" required placeholder="e.g. 18">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Student</h5>
                    <small class="text-muted">Update student information</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="lastname" id="edit_lastname" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="firstname" id="edit_firstname" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middlename" id="edit_middlename" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Age <span class="text-danger">*</span></label>
                            <input type="number" name="age" id="edit_age" class="form-control" min="15" max="80" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#studentsTable').DataTable({ 
            "pageLength": 10, 
            "lengthMenu": [5, 10, 25],
            "language": {
                "search": "",
                "searchPlaceholder": "Search students...",
                "lengthMenu": "Show _MENU_"
            }
        });

        // Reset Add modal form when opened
        $('#addStudentModal').on('show.bs.modal', function() {
            $(this).find('form')[0].reset();
        });

        // Edit button
        $(document).on('click', '.edit-btn', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_lastname').val($(this).data('lastname'));
            $('#edit_firstname').val($(this).data('firstname'));
            $('#edit_middlename').val($(this).data('middlename'));
            $('#edit_age').val($(this).data('age'));
        });

        // Delete confirmation
        $(document).on('click', '.delete-btn', function() {
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Delete this student?',
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
