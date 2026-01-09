<?php
require '../config/db.php';

// --- AUTO-FIX DATABASE (One-time check) ---
// This ensures the new columns exist without you needing to run SQL manually.
try {
    $pdo->query("SELECT grade FROM enrollments LIMIT 1");
} catch (Exception $e) {
    // Columns missing, add them silently
    $pdo->exec("ALTER TABLE enrollments ADD COLUMN grade VARCHAR(5) DEFAULT NULL");
    $pdo->exec("ALTER TABLE enrollments ADD COLUMN schedule_id VARCHAR(50) DEFAULT 'TBA'");
}

// 1. Safety Check
if (!isset($_GET['id'])) {
    header('Location: students.php');
    exit;
}

$student_id = $_GET['id'];

// 2. Fetch Student Information
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) die("Student not found.");

// 3. Fetch History & Group by Year/Sem
// We sort by Year Level, then Semester
$history = $pdo->query("
    SELECT c.subject_code, c.description, c.units, c.year_level, c.semester, 
           e.enrolled_at, e.grade, e.schedule_id, e.id as enrollment_id
    FROM enrollments e 
    JOIN curriculum c ON e.subject_id = c.CurriculumID 
    WHERE e.student_id = '$student_id' 
    ORDER BY c.year_level ASC, c.semester ASC, e.enrolled_at DESC
")->fetchAll();

// 4. Grouping Logic
$grouped_history = [];
foreach ($history as $row) {
    // Create a unique key for grouping (e.g., "1-1st")
    $key = $row['year_level'] . '-' . $row['semester'];
    
    if (!isset($grouped_history[$key])) {
        $grouped_history[$key] = [
            'year' => $row['year_level'],
            'sem' => $row['semester'],
            'subjects' => [],
            'is_completed' => true, // Assume true, prove false
            'total_units' => 0
        ];
    }
    
    // Add subject to group
    $grouped_history[$key]['subjects'][] = $row;
    $grouped_history[$key]['total_units'] += $row['units'];
    
    // If ANY subject has no grade, the semester is NOT completed
    if (empty($row['grade'])) {
        $grouped_history[$key]['is_completed'] = false;
    }
}

$pageTitle = 'Student Profile';
include '../includes/sidebar.php'; 
?>

<div class="container-fluid main-content">
    
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-secondary mb-0">Student Profile</h2>
            <p class="text-muted">Academic Record</p>
        </div>
        <a href="students.php" class="btn btn-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>

    <!-- STUDENT CARD -->
    <div class="card border-0 shadow-sm p-4 mb-4">
        <div class="d-flex align-items-center">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; font-size: 1.8rem;">
                <?php echo strtoupper(substr($student['firstname'], 0, 1) . substr($student['lastname'], 0, 1)); ?>
            </div>
            <div class="ms-4">
                <h4 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($student['lastname'] . ', ' . $student['firstname']); ?></h4>
                <div class="d-flex gap-3 text-muted small">
                    <span><i class="fas fa-id-card me-1"></i> <?php echo str_pad($student['id'], 4, '0', STR_PAD_LEFT); ?></span>
                    <span><i class="fas fa-envelope me-1"></i> <?php echo htmlspecialchars($student['email']); ?></span>
                    <span><i class="fas fa-birthday-cake me-1"></i> <?php echo $student['age']; ?> Years Old</span>
                </div>
            </div>
        </div>
    </div>

    <!-- TABS -->
    <ul class="nav nav-pills mb-4" id="profileTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active fw-bold px-4" data-bs-toggle="tab" data-bs-target="#history">
                <i class="fas fa-graduation-cap me-2"></i>Enrollment History
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 text-muted" data-bs-toggle="tab" data-bs-target="#personal">
                <i class="fas fa-user me-2"></i>Personal Info
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 text-muted" data-bs-toggle="tab" data-bs-target="#payments">
                <i class="fas fa-wallet me-2"></i>Payments
            </button>
        </li>
    </ul>

    <!-- CONTENT -->
    <div class="tab-content">
        
        <!-- HISTORY TAB (SELECTOR STYLE) -->
        <div class="tab-pane fade show active" id="history">
            
            <?php if (empty($grouped_history)): ?>
                <div class="text-center py-5 text-muted bg-white rounded shadow-sm">
                    <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                    <p>No enrollment records found.</p>
                </div>
            <?php else: ?>
                
                <!-- 1. THE SELECTOR -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-secondary mb-0">Academic Progress</h5>
                    <div class="d-flex align-items-center">
                        <label class="me-2 text-muted small fw-bold">Select Term:</label>
                        <select id="termSelector" class="form-select form-select-sm w-auto shadow-sm border-primary" style="min-width: 200px;">
                            <?php $isFirst = true; ?>
                            <?php foreach ($grouped_history as $key => $group): ?>
                                <option value="term_<?php echo $key; ?>">
                                    Year <?php echo $group['year']; ?> - <?php echo $group['sem']; ?> Semester
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- 2. THE CONTENT BLOCKS -->
                <?php $counter = 0; ?>
                <?php foreach ($grouped_history as $key => $group): ?>
                    <div id="term_<?php echo $key; ?>" class="term-content fade <?php echo ($counter === 0) ? 'show active-term' : 'd-none'; ?>">
                        <div class="card border-0 shadow-sm">
                            
                            <!-- CARD HEADER -->
                            <div class="card-header bg-white p-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="fw-bold text-primary mb-1">
                                        Year <?php echo $group['year']; ?> &bull; <?php echo $group['sem']; ?> Semester
                                    </h4>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="text-muted small">
                                            <i class="fas fa-book me-1"></i> <?php echo count($group['subjects']); ?> Subjects
                                        </span>
                                        <span class="text-muted small">
                                            <i class="fas fa-layer-group me-1"></i> <?php echo $group['total_units']; ?> Units
                                        </span>
                                        <!-- STATUS BADGE -->
                                        <?php if ($group['is_completed']): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border-0">
                                                <i class="fas fa-check-circle me-1"></i> Completed
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-info bg-opacity-10 text-info border-0">
                                                <i class="fas fa-spinner me-1"></i> In Progress
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- ACTION BUTTONS -->
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#gradesModal">
                                        <i class="fas fa-chart-bar me-2"></i>View Grades
                                    </button>
                                    <button class="btn btn-outline-secondary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#scheduleModal" 
                                            onclick="loadSchedule(this)"
                                            data-schedule='<?php echo json_encode($group['subjects']); ?>'>
                                        <i class="fas fa-calendar-alt me-2"></i>Schedule
                                    </button>
                                </div>
                            </div>

                            <!-- TABLE -->
                            <div class="card-body p-0">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4" style="width: 20%;">Subject Code</th>
                                            <th style="width: 60%;">Description</th>
                                            <th style="width: 20%;">Units</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($group['subjects'] as $subj): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary"><?php echo htmlspecialchars($subj['subject_code']); ?></td>
                                            <td><?php echo htmlspecialchars($subj['description']); ?></td>
                                            <td><?php echo $subj['units']; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php $counter++; ?>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

        <script>
            // Simple JS to handle the selector switch
            document.getElementById('termSelector').addEventListener('change', function() {
                // Hide all terms
                document.querySelectorAll('.term-content').forEach(el => {
                    el.classList.add('d-none');
                    el.classList.remove('show');
                });
                
                // Show selected term
                const selectedId = this.value;
                const target = document.getElementById(selectedId);
                if(target) {
                    target.classList.remove('d-none');
                    // Small timeout to allow d-none removal to register before fading in
                    setTimeout(() => target.classList.add('show'), 10);
                }
            });

            // DYNAMIC SCHEDULE LOADER
            function loadSchedule(btn) {
                // 1. Get Data
                const subjects = JSON.parse(btn.getAttribute('data-schedule'));
                const list = document.getElementById('scheduleList');
                
                // 2. Clear List
                list.innerHTML = '';

                // 3. Build List
                if(subjects.length > 0) {
                    subjects.forEach(subj => {
                        const sched = subj.schedule_id ? subj.schedule_id : 'TBA';
                        const item = `
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="text-primary">${subj.subject_code}</strong><br>
                                    <small>${subj.description}</small>
                                </div>
                                <span class="badge bg-light text-dark border font-monospace">${sched}</span>
                            </div>
                        `;
                        list.insertAdjacentHTML('beforeend', item);
                    });
                } else {
                    list.innerHTML = '<p class="text-center text-muted py-3">No subjects enrolled for this term.</p>';
                }
            }
        </script>

        <!-- PLACEHOLDER TABS -->
        <div class="tab-pane fade" id="personal"><div class="card p-5 text-center text-muted">Personal Info Module Coming Soon</div></div>
        <div class="tab-pane fade" id="payments"><div class="card p-5 text-center text-muted">Payments Module Coming Soon</div></div>
    </div>
</div>

<!-- GRADES MODAL (Dummy Content for Future Proofing) -->
<div class="modal fade" id="gradesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Official Grades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fas fa-award fa-3x text-warning mb-3"></i>
                <p>Grade Report generation is enabled for this semester.</p>
                <div class="alert alert-info small text-start">
                    <strong>Note:</strong> This is a future-proof module. In the complete version, this will display the downloadable PDF Grade Slip.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SCHEDULE MODAL -->
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Class Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="scheduleList" class="list-group list-group-flush">
                    <!-- Dynamic Content Will Be Loaded Here -->
                </div>
                <div class="alert alert-secondary mt-3 small mb-0">
                    <i class="fas fa-info-circle me-1"></i> Schedules are managed via the Curriculum ID system.
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>