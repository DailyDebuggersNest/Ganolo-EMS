<?php
require '../config/db.php';
$pageTitle = 'Enrollments';

// --- HANDLE ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. ADD ENROLLMENT
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        try {
            // Insert enrollment
            $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, subject_id) VALUES (?, ?)");
            $stmt->execute([$_POST['student_id'], $_POST['subject_id']]);
            $enrollmentId = $pdo->lastInsertId();

            // Auto-generate schedule for this enrollment
            $days = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'MON/WED', 'TUE/THU', 'MON/THU', 'WED/FRI'];
            $times = [
                ['07:00 AM', '10:00 AM'], ['08:00 AM', '11:00 AM'], ['09:00 AM', '12:00 PM'],
                ['10:00 AM', '01:00 PM'], ['01:00 PM', '04:00 PM'], ['02:00 PM', '05:00 PM'],
                ['03:00 PM', '06:00 PM'], ['04:00 PM', '07:00 PM']
            ];
            $rooms = ['ROOM 101','ROOM 102','ROOM 201','ROOM 202','ROOM 301','ROOM 302','ROOM 401','LAB 1','LAB 2','LAB 3','LAB 4','LEC 1','LEC 2','AVR','GYM'];

            $day = $days[array_rand($days)];
            $time = $times[array_rand($times)];
            $room = $rooms[array_rand($rooms)];

            $schedStmt = $pdo->prepare("INSERT INTO schedule (enrollment_id, day, time_in, time_out, room) VALUES (?, ?, ?, ?, ?)");
            $schedStmt->execute([$enrollmentId, $day, $time[0], $time[1], $room]);

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
$subjects = $pdo->query("SELECT cur.CurriculumID, cur.subject_code, cur.description, cur.courseID, COALESCE(co.course_code, '') as course_code FROM curriculum cur LEFT JOIN course co ON cur.courseID = co.courseID ORDER BY cur.courseID, cur.year_level, cur.semester ASC")->fetchAll();
$courses = $pdo->query("SELECT * FROM course ORDER BY courseID ASC")->fetchAll();

// Build student-to-course mapping (which course each student belongs to based on their enrollments)
$studentCourseMap = $pdo->query("
    SELECT DISTINCT e.student_id, c.courseID, COALESCE(co.course_code, '') as course_code
    FROM enrollments e 
    JOIN curriculum c ON e.subject_id = c.CurriculumID 
    LEFT JOIN course co ON c.courseID = co.courseID
    WHERE c.courseID IS NOT NULL
")->fetchAll();
$studentCourses = [];
$studentCourseNames = [];
foreach ($studentCourseMap as $sc) {
    $studentCourses[$sc['student_id']] = $sc['courseID'];
    $studentCourseNames[$sc['student_id']] = $sc['course_code'];
}
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
    <div class="modal-dialog modal-xl modal-dialog-centered">
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
                    
                    <!-- Step 1: Select Course -->
                    <div class="mb-3">
                        <label class="form-label">Course <span class="text-danger">*</span></label>
                        <select id="modal_course" class="form-select" required>
                            <option value="">Select a course first...</option>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?php echo $c['courseID']; ?>"><?php echo htmlspecialchars($c['course_code'] . ' - ' . $c['description']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Select the course to filter students and subjects</div>
                    </div>

                    <div class="row">
                        <!-- Step 2: Student (filtered by course) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Student <span class="text-danger">*</span></label>
                            <select name="student_id" id="modal_student" class="form-select" required disabled>
                                <option value="">Select a course first...</option>
                            </select>
                            <div class="form-text">Students enrolled in the selected course</div>
                        </div>
                        
                        <!-- Step 3: Subject (filtered by course) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" id="modal_subject" class="form-select" required disabled>
                                <option value="">Select a course first...</option>
                            </select>
                            <div class="form-text">Subjects under the selected course</div>
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

        // === DATA FOR DYNAMIC FILTERING ===
        // All students with their course mapping
        var allStudents = [
            <?php foreach ($students as $s): ?>
                { id: <?php echo $s['id']; ?>, name: <?php echo json_encode($s['lastname'] . ', ' . $s['firstname']); ?>, courseID: <?php echo json_encode($studentCourses[$s['id']] ?? ''); ?>, courseName: <?php echo json_encode($studentCourseNames[$s['id']] ?? ''); ?> },
            <?php endforeach; ?>
        ];

        // All subjects with course info
        var allSubjects = [
            <?php foreach ($subjects as $s): ?>
                { id: <?php echo $s['CurriculumID']; ?>, courseID: <?php echo json_encode($s['courseID']); ?>, label: <?php echo json_encode($s['course_code'] . ' | ' . $s['subject_code'] . ' - ' . $s['description']); ?> },
            <?php endforeach; ?>
        ];

        // === COURSE CHANGE → FILTER STUDENT + SUBJECT ===
        $('#modal_course').on('change', function() {
            var selectedCourse = $(this).val();
            var $studentSelect = $('#modal_student');
            var $subjectSelect = $('#modal_subject');

            // Reset both
            $studentSelect.html('<option value="">Select a student...</option>');
            $subjectSelect.html('<option value="">Select a subject...</option>');

            if (!selectedCourse) {
                $studentSelect.prop('disabled', true);
                $subjectSelect.prop('disabled', true);
                return;
            }

            // Enrolled students in this course + unenrolled students (available to all)
            var enrolledInCourse = allStudents.filter(function(s) { return s.courseID === selectedCourse; });
            var unenrolled = allStudents.filter(function(s) { return s.courseID === ''; });

            var studentHtml = '<option value="">Select a student...</option>';

            // Show enrolled students first with their course tag
            if (enrolledInCourse.length > 0) {
                studentHtml += '<optgroup label="Enrolled in this course">';
                enrolledInCourse.forEach(function(s) {
                    studentHtml += '<option value="' + s.id + '">' + s.name + ' (' + s.courseName + ')</option>';
                });
                studentHtml += '</optgroup>';
            }

            // Show unenrolled students with indicator
            if (unenrolled.length > 0) {
                studentHtml += '<optgroup label="Not yet enrolled">';
                unenrolled.forEach(function(s) {
                    studentHtml += '<option value="' + s.id + '">' + s.name + ' — Not Enrolled</option>';
                });
                studentHtml += '</optgroup>';
            }

            if (enrolledInCourse.length === 0 && unenrolled.length === 0) {
                $studentSelect.html('<option value="">No students available</option>');
            } else {
                $studentSelect.html(studentHtml);
            }
            $studentSelect.prop('disabled', false);

            // Filter subjects by course
            var filteredSubjects = allSubjects.filter(function(s) {
                return s.courseID === selectedCourse;
            });

            if (filteredSubjects.length === 0) {
                $subjectSelect.html('<option value="">No subjects found for this course</option>');
            } else {
                var subjectHtml = '<option value="">Select a subject...</option>';
                filteredSubjects.forEach(function(s) {
                    subjectHtml += '<option value="' + s.id + '">' + s.label + '</option>';
                });
                $subjectSelect.html(subjectHtml);
            }
            $subjectSelect.prop('disabled', false);
        });

        // Reset Add modal form when opened
        $('#addModal').on('show.bs.modal', function() {
            $(this).find('form')[0].reset();
            $('#modal_student').html('<option value="">Select a course first...</option>').prop('disabled', true);
            $('#modal_subject').html('<option value="">Select a course first...</option>').prop('disabled', true);
        });

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