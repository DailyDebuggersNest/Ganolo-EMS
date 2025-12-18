<?php
require '../config/db.php';
$pageTitle = 'Dashboard';

// --- STATS LOGIC ---
$stats = [
    'students' => $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn(),
    'subjects' => $pdo->query("SELECT COUNT(*) FROM curriculum")->fetchColumn(),
    'courses'  => $pdo->query("SELECT COUNT(*) FROM course")->fetchColumn(),
    'enrollments' => $pdo->query("SELECT COUNT(*) FROM enrollments")->fetchColumn()
];

// --- LATEST ACTIVITY (Fetch Last 5 Enrollments) ---
$recent_activity = $pdo->query("SELECT s.firstname, s.lastname, c.subject_code, c.description, e.enrolled_at 
                                FROM enrollments e 
                                JOIN students s ON e.student_id = s.id 
                                JOIN curriculum c ON e.subject_id = c.CurriculumID 
                                ORDER BY e.enrolled_at DESC LIMIT 5")->fetchAll();
?>

<?php include '../includes/sidebar.php'; ?>

<div class="container-fluid main-content">
    <h2 class="mb-4 fw-bold text-secondary">Dashboard Overview</h2>
    
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card p-3 border-start border-4 border-primary shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold small">Total Students</p>
                        <h2 class="fw-bold mb-0 text-primary"><?php echo $stats['students']; ?></h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 border-start border-4 border-info shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold small">Total Courses</p>
                        <h2 class="fw-bold mb-0 text-info"><?php echo $stats['courses']; ?></h2>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info">
                        <i class="fas fa-university fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 border-start border-4 border-success shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold small">Total Subjects</p>
                        <h2 class="fw-bold mb-0 text-success"><?php echo $stats['subjects']; ?></h2>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                        <i class="fas fa-book-open fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 border-start border-4 border-warning shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold small">Enrollments</p>
                        <h2 class="fw-bold mb-0 text-warning"><?php echo $stats['enrollments']; ?></h2>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                        <i class="fas fa-tasks fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4">
        <h5 class="fw-bold text-secondary mb-3">Recently Added Enrollments</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Student Name</th>
                        <th>Subject</th>
                        <th>Date Enrolled</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($recent_activity) > 0): ?>
                        <?php foreach ($recent_activity as $act): ?>
                        <tr>
                            <td class="fw-bold text-dark">
                                <?php echo htmlspecialchars($act['firstname'] . ' ' . $act['lastname']); ?>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    <?php echo htmlspecialchars($act['subject_code']); ?>
                                </span>
                                <span class="text-muted small ms-2"><?php echo htmlspecialchars($act['description']); ?></span>
                            </td>
                            <td class="text-muted small">
                                <i class="far fa-clock me-1"></i>
                                <?php echo date('M d, Y h:i A', strtotime($act['enrolled_at'])); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No recent activity found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>