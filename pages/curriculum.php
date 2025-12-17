<?php
require '../config/db.php';
$pageTitle = 'Curriculum';

// --- HANDLE ADD SUBJECT ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    try {
        $stmt = $pdo->prepare("INSERT INTO curriculum (subject_code, description, year_level, semester, units) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['subject_code'], 
            $_POST['description'], 
            $_POST['year_level'], 
            $_POST['semester'], 
            $_POST['units']
        ]);
        $successMsg = "Subject added to curriculum!";
    } catch (PDOException $e) {
        $errorMsg = "Error: " . $e->getMessage();
    }
}

// Fetch Data
$subjects = $pdo->query("SELECT * FROM curriculum ORDER BY year_level ASC, semester ASC")->fetchAll();
?>

<?php include '../includes/sidebar.php'; ?>

<div class="container-fluid main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-secondary">Curriculum Management</h2>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
            <i class="fas fa-plus me-2"></i>Add Subject
        </button>
    </div>

    <?php if (isset($successMsg)): ?><script>Swal.fire('Success', '<?php echo $successMsg; ?>', 'success');</script><?php endif; ?>

    <div class="card border-0 shadow-sm p-4">
        <table id="curriculumTable" class="table table-hover align-middle">
            <thead class="bg-light text-secondary">
                <tr>
                    <th>Subject Code</th>
                    <th>Description</th>
                    <th>Year Level</th>
                    <th>Semester</th>
                    <th>Units</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $s): ?>
                <tr>
                    <td class="fw-bold text-primary"><?php echo htmlspecialchars($s['subject_code']); ?></td>
                    <td><?php echo htmlspecialchars($s['description']); ?></td>
                    <td><span class="badge bg-secondary"><?php echo $s['year_level']; ?></span></td>
                    <td><?php echo htmlspecialchars($s['semester']); ?></td>
                    <td><?php echo $s['units']; ?></td>
                    <td class="text-center">
                        <form method="POST" action="../actions/delete_curriculum.php" class="d-inline delete-form">
                            <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle delete-btn">
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

<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">New Curriculum Subject</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label>Subject Code</label>
                        <input type="text" name="subject_code" class="form-control" placeholder="e.g. IT101" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control" placeholder="e.g. Intro to Computing" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Year Level</label>
                            <input type="number" name="year_level" class="form-control" min="1" max="4" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Semester</label>
                            <select name="semester" class="form-select">
                                <option value="1st">1st</option>
                                <option value="2nd">2nd</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Units</label>
                            <input type="number" name="units" class="form-control" value="3" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#curriculumTable').DataTable();
        $('.delete-btn').click(function() {
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Delete Subject?',
                text: "This cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => { if (result.isConfirmed) form.submit(); })
        });
    });
</script>
</body>
</html>