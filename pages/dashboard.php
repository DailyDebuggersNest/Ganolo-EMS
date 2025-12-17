<?php
require '../config/db.php';
$pageTitle = 'Dashboard';

// --- UPDATED STATS LOGIC ---
$stats = [
    'students' => $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn(),
    'subjects' => $pdo->query("SELECT COUNT(*) FROM curriculum")->fetchColumn(),
    'enrollments' => $pdo->query("SELECT COUNT(*) FROM enrollments")->fetchColumn()
];

// --- UPDATED LATEST ENROLLMENT QUERY ---
// Joins 'curriculum' and uses 'subject_id'
$latest = $pdo->query("SELECT s.firstname, s.lastname, c.subject_code, c.description, e.enrolled_at 
                       FROM enrollments e 
                       JOIN students s ON e.student_id = s.id 
                       JOIN curriculum c ON e.subject_id = c.id 
                       ORDER BY e.enrolled_at DESC LIMIT 1")->fetch();
?>

<?php include '../includes/sidebar.php'; ?>

<div class="container-fluid main-content">
    <h2 class="mb-4 fw-bold text-secondary">Dashboard Overview</h2>
    
    <div class="row g-4">
        <div class="col-md-4">
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

        <div class="col-md-4">
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

        <div class="col-md-4">
            <div class="card p-3 border-start border-4 border-warning shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold small">Total Enrollments</p>
                        <h2 class="fw-bold mb-0 text-warning"><?php echo $stats['enrollments']; ?></h2>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                        <i class="fas fa-tasks fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-secondary"><i class="fas fa-history me-2"></i>Latest Activity</h5>
                </div>
                <div class="card-body p-4">
                    <?php if ($latest): ?>
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3 text-info">
                                <i class="fas fa-bell fa-lg"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark fs-5">
                                    <?php echo htmlspecialchars($latest['firstname'] . ' ' . $latest['lastname']); ?>
                                </span>
                                <span class="text-muted">enrolled in</span>
                                <span class="fw-bold text-primary">
                                    <?php echo htmlspecialchars($latest['subject_code']); ?>
                                </span>
                                <span class="text-muted small">(<?php echo htmlspecialchars($latest['description']); ?>)</span>
                                <br>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    <?php echo date('F j, Y, g:i a', strtotime($latest['enrolled_at'])); ?>
                                </small>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                            <p>No recent activity found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>