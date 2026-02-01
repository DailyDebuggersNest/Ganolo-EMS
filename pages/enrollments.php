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

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Enrollments</h1>
            <p class="page-subtitle">Manage student course enrollments</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-2"></i>New Enrollment
        </button>
    </div>

    <?php if (isset($successMsg)): ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => Swal.fire({
            title: 'Success',
            text: '<?php echo $successMsg; ?>',
            icon: 'success',
            confirmButtonColor: '#4a5568'
        }));
    </script>
    <?php endif; ?>
    <?php if (isset($errorMsg)): ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => Swal.fire({
            title: 'Error',
            text: '<?php echo $errorMsg; ?>',
            icon: 'error',
            confirmButtonColor: '#f56565'
        }));
    </script>
    <?php endif; ?>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Student name or subject code..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Filter by Course</label>
                <select name="courseID" class="form-select">
                    <option value="">All Courses</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?php echo $c['courseID']; ?>" <?php echo ($courseFilter == $c['courseID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['course_code']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="fas fa-filter me-2"></i>Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Enrollments Table -->
    <div class="card flex-fill">
        <div class="table-wrapper">
            <?php if (count($enrollments) > 0): ?>
            <table id="enrollmentTable" class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Course</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Date Enrolled</th>
                        <th class="text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $row): ?>
                        <tr>
                            <td>
                                <span class="badge-id"><?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></span>
                            </td>
                            <td>
                                <span class="fw-medium"><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></span>
                            </td>
                            <td>
                                <span class="badge badge-course"><?php echo htmlspecialchars($row['course_code']); ?></span>
                            </td>
                            <td>
                                <span class="badge badge-subject"><?php echo htmlspecialchars($row['subject_code']); ?></span>
                            </td>
                            <td class="text-muted"><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['enrolled_at'])); ?></td>
                            <td class="text-center">
                                <form method="POST" class="d-inline delete-form">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" title="Remove enrollment">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-user-graduate"></i>
                <h5>No Enrollments Found</h5>
                <p>No enrollment records match your criteria. Try adjusting your filters or add a new enrollment.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i>New Enrollment
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Enroll Student</h5>
                    <small class="text-muted">Add a new student enrollment</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Student <span class="text-danger">*</span></label>
                            <select name="student_id" class="form-select" required>
                                <option value="">Select a student...</option>
                                <?php foreach ($students as $s): ?>
                                    <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['lastname'] . ', ' . $s['firstname']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Choose the student to enroll</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" class="form-select" required>
                                <option value="">Select a subject...</option>
                                <?php foreach ($subjects as $s): ?>
                                    <option value="<?php echo $s['CurriculumID']; ?>">
                                        [<?php echo $s['courseID']; ?>] <?php echo htmlspecialchars($s['subject_code'] . ' - ' . $s['description']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Choose the subject to enroll in</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Enroll Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .badge-course {
        background-color: rgba(56, 178, 172, 0.1);
        color: #319795;
        font-weight: 500;
        padding: 0.35em 0.65em;
        border-radius: var(--border-radius-sm);
    }
    .badge-subject {
        background-color: rgba(66, 153, 225, 0.1);
        color: #3182ce;
        font-weight: 500;
        padding: 0.35em 0.65em;
        border-radius: var(--border-radius-sm);
    }
</style>

<script>
    $(document).ready(function() {
        <?php if (count($enrollments) > 0): ?>
        $('#enrollmentTable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            language: {
                search: "",
                searchPlaceholder: "Search enrollments...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ enrollments",
                paginate: {
                    first: '<i class="fas fa-angle-double-left"></i>',
                    last: '<i class="fas fa-angle-double-right"></i>',
                    next: '<i class="fas fa-angle-right"></i>',
                    previous: '<i class="fas fa-angle-left"></i>'
                }
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
        });
        <?php endif; ?>

        // Delete confirmation with calm styling
        $(document).on('click', '.delete-btn', function() {
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Remove Enrollment?',
                text: "This will unenroll the student from this subject.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f56565',
                cancelButtonColor: '#4a5568',
                confirmButtonText: '<i class="fas fa-trash-alt me-2"></i>Yes, remove',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
</body>
</html>