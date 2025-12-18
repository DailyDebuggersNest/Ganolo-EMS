<?php
require '../config/db.php';
$pageTitle = 'Students';

// --- HANDLE ADD STUDENT ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $lastname = $_POST['lastname'];
    if (substr($lastname, -2) !== '23') { $lastname .= '23'; } 

    try {
        $stmt = $pdo->prepare("INSERT INTO students (lastname, firstname, middlename, age, email, phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$lastname, $_POST['firstname'], $_POST['middlename'], $_POST['age'], $_POST['email'], $_POST['phone']]);
        $successMsg = "Student added successfully!";
    } catch (PDOException $e) {
        $errorMsg = "Error: " . $e->getMessage();
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

    <div class="card border-0 shadow-sm p-4">
        <table id="studentsTable" class="table table-hover align-middle">
            <thead class="bg-light text-secondary">
                <tr>
                    <th>ID</th> <th>Last Name</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Age</th>
                    <th>Email</th> <th>Phone</th> <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td class="font-monospace fw-bold text-secondary">
                        <?php echo str_pad($s['id'], 4, '0', STR_PAD_LEFT); ?>
                    </td>
                    <td class="fw-bold text-primary"><?php echo htmlspecialchars($s['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($s['firstname']); ?></td>
                    <td><?php echo htmlspecialchars($s['middlename']); ?></td>
                    <td><?php echo $s['age']; ?></td>
                    <td class="small text-muted"><?php echo htmlspecialchars($s['email']); ?></td>
                    <td class="small text-muted"><?php echo htmlspecialchars($s['phone']); ?></td>
                    
                    <td class="text-center">
                        <a href="../actions/edit_student.php?id=<?php echo $s['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                        <form method="POST" action="../actions/delete_student.php" class="d-inline delete-form">
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
                        <div class="col-md-4">
                            <label class="fw-bold small text-muted">Last Name</label>
                            <input type="text" name="lastname" class="form-control" required placeholder="Ends with '23'">
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold small text-muted">First Name</label>
                            <input type="text" name="firstname" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold small text-muted">Middle Name</label>
                            <input type="text" name="middlename" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold small text-muted">Age</label>
                            <input type="number" name="age" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label class="fw-bold small text-muted">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold small text-muted">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#studentsTable').DataTable();

        // FIXED: Use $(document).on('click', ...) so it works on ALL pages (1, 2, 3...)
        $(document).on('click', '.delete-btn', function() {
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Delete Student?',
                text: "Confirm deletion of this student?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    });
</script>
</body>
</html>