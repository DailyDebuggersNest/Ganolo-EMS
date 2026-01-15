<?php
require '../config/db.php';
$pageTitle = 'Student Directory';

// --- HANDLE ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. ADD STUDENT
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO students (lastname, firstname, middlename, age) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['lastname'], $_POST['firstname'], $_POST['middlename'], $_POST['age']]);
            $successMsg = "Student added successfully!";
        } catch (PDOException $e) {
            $errorMsg = "Error: " . $e->getMessage();
        }
    }

    // 2. EDIT STUDENT
    elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        try {
            $stmt = $pdo->prepare("UPDATE students SET lastname=?, firstname=?, middlename=?, age=? WHERE id=?");
            $stmt->execute([
                $_POST['lastname'], 
                $_POST['firstname'], 
                $_POST['middlename'], 
                $_POST['age'], 
                $_POST['id']
            ]);
            $successMsg = "Student updated successfully!";
        } catch (PDOException $e) {
            $errorMsg = "Error updating student: " . $e->getMessage();
        }
    }

    // 3. DELETE STUDENT
    elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        try {
            // Check for dependencies first (optional but good practice)
            $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $successMsg = "Student record deleted!";
        } catch (PDOException $e) {
            $errorMsg = "Error: Cannot delete student. They might be enrolled in programs.";
        }
    }
}

$students = $pdo->query("SELECT * FROM students ORDER BY lastname ASC")->fetchAll();
?>

<?php include '../includes/sidebar.php'; ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-secondary">Student Directory</h2>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <i class="fas fa-user-plus me-2"></i>Add Student
        </button>
    </div>

    <?php if (isset($successMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire('Success', '<?php echo $successMsg; ?>', 'success'));</script><?php endif; ?>
    <?php if (isset($errorMsg)): ?><script>document.addEventListener("DOMContentLoaded", () => Swal.fire('Error', '<?php echo $errorMsg; ?>', 'error'));</script><?php endif; ?>

    <div class="card border-0 shadow-sm p-4">
        <table id="studentsTable" class="table table-hover align-middle">
            <thead class="bg-light text-secondary">
                <tr>
                    <th>ID</th> <th>Last Name</th> <th>First Name</th> <th>Middle Name</th>
                    <th>Age</th> <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td class="font-monospace fw-bold text-secondary"><?php echo str_pad($s['id'], 4, '0', STR_PAD_LEFT); ?></td>
                    <td class="fw-bold text-primary"><?php echo htmlspecialchars($s['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($s['firstname']); ?></td>
                    <td><?php echo htmlspecialchars($s['middlename']); ?></td>
                    <td><?php echo $s['age']; ?></td>
                    
                    <td class="text-center">
                        <!-- PROFILE / HISTORY BUTTON -->
                        <a href="student_profile.php?id=<?php echo $s['id']; ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        <!-- EDIT BUTTON -->
                        <button type="button" class="btn btn-warning btn-sm edit-btn"
                                data-id="<?php echo $s['id']; ?>"
                                data-lastname="<?php echo htmlspecialchars($s['lastname']); ?>"
                                data-firstname="<?php echo htmlspecialchars($s['firstname']); ?>"
                                data-middlename="<?php echo htmlspecialchars($s['middlename']); ?>"
                                data-age="<?php echo $s['age']; ?>"
                                data-bs-toggle="modal" data-bs-target="#editStudentModal">
                            <i class="fas fa-pen"></i>
                        </button>

                        <form method="POST" class="d-inline delete-form">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
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
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add New Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4 bg-light">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="fw-bold small">Last Name</label><input type="text" name="lastname" class="form-control" required></div>
                        <div class="col-md-4"><label class="fw-bold small">First Name</label><input type="text" name="firstname" class="form-control" required></div>
                        <div class="col-md-4"><label class="fw-bold small">Middle Name</label><input type="text" name="middlename" class="form-control"></div>
                        <div class="col-md-4"><label class="fw-bold small">Age</label><input type="number" name="age" class="form-control" required></div>
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
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4 bg-light">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="fw-bold small">Last Name</label><input type="text" name="lastname" id="edit_lastname" class="form-control" required></div>
                        <div class="col-md-4"><label class="fw-bold small">First Name</label><input type="text" name="firstname" id="edit_firstname" class="form-control" required></div>
                        <div class="col-md-4"><label class="fw-bold small">Middle Name</label><input type="text" name="middlename" id="edit_middlename" class="form-control"></div>
                        <div class="col-md-4"><label class="fw-bold small">Age</label><input type="number" name="age" id="edit_age" class="form-control" required></div>
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
        $('#studentsTable').DataTable({
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50, 75, 100]
        });

        // HANDLE DELETE
        $(document).on('click', '.delete-btn', function() {
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Delete Student?', text: "Confirm deletion?", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Yes, delete'
            }).then((result) => { if (result.isConfirmed) form.submit(); })
        });

        // HANDLE EDIT
        $(document).on('click', '.edit-btn', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_lastname').val($(this).data('lastname'));
            $('#edit_firstname').val($(this).data('firstname'));
            $('#edit_middlename').val($(this).data('middlename'));
            $('#edit_age').val($(this).data('age'));
        });
    });
</script>
</body>
</html>