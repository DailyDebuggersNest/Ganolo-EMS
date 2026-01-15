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
        } catch (PDOException $e) { $errorMsg = "Error: " . $e->getMessage(); }
    }

    // 2. DELETE ENROLLMENT
    elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM enrollments WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $successMsg = "Enrollment removed.";
        } catch (PDOException $e) { $errorMsg = "Error: " . $e->getMessage(); }
    }
}

// --- FETCH DATA ---
// We join enrollments -> students & enrollments -> curriculum -> course
$sql = "SELECT e.id, s.firstname, s.lastname, c.subject_code, c.description, co.course_code, e.enrolled_at 
        FROM enrollments e 
        JOIN students s ON e.student_id = s.id 
        JOIN curriculum c ON e.subject_id = c.CurriculumID 
        LEFT JOIN course co ON c.courseID = co.courseID 
        WHERE 1=1";

$params = [];
$searchQuery = $_GET['search'] ?? '';
$courseFilter = $_GET['courseID'] ?? '';

if (!empty($searchQuery)) {
    $sql .= " AND (s.lastname LIKE ? OR s.firstname LIKE ? OR c.subject_code LIKE ?)";
    $params[] = "%$searchQuery%";
    $params[] = "%$searchQuery%";
    $params[] = "%$searchQuery%";
}

if (!empty($courseFilter)) {
    $sql .= " AND c.courseID = ?";
    $params[] = $courseFilter;
}

$sql .= " ORDER BY e.enrolled_at DESC LIMIT 100";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$enrollments = $stmt->fetchAll();

// Fetch Options for Dropdowns
$students = $pdo->query("SELECT id, firstname, lastname FROM students ORDER BY lastname ASC")->fetchAll();
$subjects = $pdo->query("SELECT CurriculumID, subject_code, description, courseID FROM curriculum ORDER BY courseID, year_level, semester ASC")->fetchAll();
$courses = $pdo->query("SELECT * FROM course ORDER BY courseID ASC")->fetchAll();
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

    <!-- FILTER CARD -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light rounded">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-bold small text-muted">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Student Name or Subject Code..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">Filter by Course</label>
                    <select name="courseID" class="form-select">
                        <option value="">All Courses</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?php echo $c['courseID']; ?>" <?php echo ($courseFilter == $c['courseID']) ? 'selected' : ''; ?>>
                                <?php echo $c['course_code']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4">
        <table id="enrollmentTable" class="table table-hover align-middle">
            <thead class="bg-light text-secondary">
                <tr>
                    <th>ID</th> <th>Student Name</th> <th>Course</th> <th>Subject</th> <th>Description</th> <th>Date Enrolled</th> <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($enrollments) > 0): ?>
                    <?php foreach ($enrollments as $row): ?>
                        <tr>
                            <td class="text-muted small font-monospace"><?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                            <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($row['course_code']); ?></span></td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary border border-primary"><?php echo htmlspecialchars($row['subject_code']); ?></span></td>
                            <td class="text-muted small"><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['enrolled_at'])); ?></td>
                            <td class="text-center">
                                <form method="POST" class="d-inline delete-form">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="button" class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
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
                                <option value="<?php echo $s['CurriculumID']; ?>">
                                    [<?php echo $s['courseID']; ?>] <?php echo htmlspecialchars($s['subject_code'] . ' - ' . $s['description']); ?>
                                </option>
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

<script>
    $(document).ready(function() {
        $('#enrollmentTable').DataTable({ "pageLength": 5, "lengthMenu": [5, 10, 25, 50, 75, 100] });

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