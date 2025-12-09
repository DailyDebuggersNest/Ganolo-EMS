<?php
require '../config/db.php';
$pageTitle = 'Dashboard';

// Fetch stats
$stmt = $pdo->query("SELECT COUNT(*) AS total_students FROM students");
$totalStudents = $stmt->fetch()['total_students'];

$stmt = $pdo->query("SELECT COUNT(*) AS total_courses FROM courses");
$totalCourses = $stmt->fetch()['total_courses'];

$stmt = $pdo->query("SELECT COUNT(*) AS total_enrollments FROM enrollments");
$totalEnrollments = $stmt->fetch()['total_enrollments'];

$stmt = $pdo->query("SELECT s.firstname, s.lastname, e.enrolled_at FROM enrollments e JOIN students s ON e.student_id = s.id ORDER BY e.enrolled_at DESC LIMIT 1");
$latestEnrollment = $stmt->fetch();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="container">
    <h1 class="mb-4">Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <p class="card-text"><?php echo $totalStudents; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Courses</h5>
                    <p class="card-text"><?php echo $totalCourses; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Enrollments</h5>
                    <p class="card-text"><?php echo $totalEnrollments; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Latest Enrolled Student</h5>
                    <p class="card-text"><?php echo $latestEnrollment ? $latestEnrollment['firstname'] . ' ' . $latestEnrollment['lastname'] . ' (' . $latestEnrollment['enrolled_at'] . ')' : 'None'; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>