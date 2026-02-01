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

// --- LATEST ACTIVITY ---
$recent_activity = $pdo->query("
    SELECT s.firstname, s.lastname, c.subject_code, c.description, e.enrolled_at 
    FROM enrollments e 
    JOIN students s ON e.student_id = s.id 
    JOIN curriculum c ON e.subject_id = c.CurriculumID 
    ORDER BY e.enrolled_at DESC LIMIT 8
")->fetchAll();
?>

<?php include '../includes/sidebar.php'; ?>

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Welcome back! Here's your overview.</p>
        </div>
    </div>
    
    <!-- Stats Row (Compact) -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-info">
                <p class="stat-label">Total Students</p>
                <p class="stat-value"><?php echo $stats['students']; ?></p>
            </div>
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p class="stat-label">Total Courses</p>
                <p class="stat-value"><?php echo $stats['courses']; ?></p>
            </div>
            <div class="stat-icon info">
                <i class="fas fa-book-open"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p class="stat-label">Total Subjects</p>
                <p class="stat-value"><?php echo $stats['subjects']; ?></p>
            </div>
            <div class="stat-icon success">
                <i class="fas fa-list-alt"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p class="stat-label">Enrollments</p>
                <p class="stat-value"><?php echo $stats['enrollments']; ?></p>
            </div>
            <div class="stat-icon warning">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>
    </div>

    <!-- Recent Activity Card (Flex Fill) -->
    <div class="card flex-fill">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="font-size: 0.95rem; color: var(--text-secondary);">
                <i class="fas fa-clock me-2" style="opacity: 0.5;"></i>Recent Enrollments
            </h5>
            <span class="badge bg-primary"><?php echo count($recent_activity); ?> records</span>
        </div>
        <div class="table-wrapper">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="padding-left: 20px;">Student Name</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th style="width: 160px;">Date Enrolled</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($recent_activity) > 0): ?>
                        <?php foreach ($recent_activity as $act): ?>
                        <tr>
                            <td style="padding-left: 20px;">
                                <span class="fw-semibold"><?php echo htmlspecialchars($act['firstname'] . ' ' . $act['lastname']); ?></span>
                            </td>
                            <td>
                                <span class="badge-subject"><?php echo htmlspecialchars($act['subject_code']); ?></span>
                            </td>
                            <td class="text-muted"><?php echo htmlspecialchars($act['description']); ?></td>
                            <td>
                                <span class="text-muted small">
                                    <?php echo date('M d, Y', strtotime($act['enrolled_at'])); ?>
                                    <span style="opacity: 0.5; margin-left: 6px;"><?php echo date('h:i A', strtotime($act['enrolled_at'])); ?></span>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h5>No Recent Activity</h5>
                                    <p>No enrollment records found yet.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
