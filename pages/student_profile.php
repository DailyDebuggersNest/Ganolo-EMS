<?php
require '../config/db.php';

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

// 3. Fetch History & Group
// JOIN grades AND schedule tables
$history = $pdo->query("
    SELECT c.subject_code, c.description, c.units, c.year_level, c.semester, 
           e.enrolled_at, e.id as enrollment_id,
           g.midterm, g.final,
           s.day as sched_day, s.time_in as sched_in, s.time_out as sched_out, s.room as sched_room
    FROM enrollments e 
    JOIN curriculum c ON e.subject_id = c.CurriculumID 
    LEFT JOIN grades g ON e.id = g.enrollment_id
    LEFT JOIN schedule s ON e.id = s.enrollment_id
    WHERE e.student_id = '$student_id' 
    ORDER BY c.year_level ASC, c.semester ASC, e.enrolled_at DESC
")->fetchAll();

// 4. Fetch Payments
$payments_stmt = $pdo->prepare("SELECT * FROM payments WHERE student_id = ? ORDER BY year_level ASC, semester ASC, payment_date ASC");
$payments_stmt->execute([$student_id]);
$all_payments = $payments_stmt->fetchAll();

// 5. Grouping Logic & Data Prep
$grouped_history = [];
$grouped_payments = [];
$js_term_data = []; 

// Helper to determine School Year based on enrollment date
function getSchoolYear($date) {
    $timestamp = strtotime($date);
    $year = date('Y', $timestamp);
    $month = date('n', $timestamp);
    
    if ($month < 6) {
        $startYear = $year - 1;
    } else {
        $startYear = $year;
    }
    return $startYear . '-' . ($startYear + 1);
}

// Group Enrollment History
foreach ($history as $row) {
    $key = $row['year_level'] . '-' . $row['semester'];
    $termKey = 'term_' . $key;
    
    $sy = getSchoolYear($row['enrolled_at']);

    if (!isset($grouped_history[$key])) {
        $termLabel = "Year " . $row['year_level'] . " • " . $row['semester'] . " Sem • S.Y. " . $sy;

        $grouped_history[$key] = [
            'label' => $termLabel,
            'year' => $row['year_level'],
            'sem' => $row['semester'],
            'sy' => $sy,
            'subjects' => [],
            'total_units' => 0,
            'is_completed' => true 
        ];
        
        $js_term_data[$termKey] = [
            'title' => $termLabel,
            'subjects' => []
        ];
    }
    
    $grouped_history[$key]['subjects'][] = $row;
    $grouped_history[$key]['total_units'] += $row['units'];
    
    $js_term_data[$termKey]['subjects'][] = [
        'code' => $row['subject_code'],
        'desc' => $row['description'],
        'units' => $row['units'],
        'mid' => $row['midterm'] ?? '--',
        'fin' => $row['final'] ?? '--',
        'day' => $row['sched_day'] ?? 'TBA',
        'in' => $row['sched_in'] ?? '--',
        'out' => $row['sched_out'] ?? '--',
        'room' => $row['sched_room'] ?? 'TBA'
    ];

    if (empty($row['midterm']) && empty($row['final'])) {
        $grouped_history[$key]['is_completed'] = false;
    }
}

// Group Payments
foreach ($all_payments as $row) {
    $key = $row['year_level'] . '-' . $row['semester'];
    
    $sy = $row['school_year']; 
    if (empty($sy)) $sy = getSchoolYear($row['payment_date']);

    if (!isset($grouped_payments[$key])) {
        $termLabel = "Year " . $row['year_level'] . " • " . $row['semester'] . " Sem • S.Y. " . $sy;
        $grouped_payments[$key] = [
            'label' => $termLabel,
            'records' => [],
            'total_cost' => 0,
            'total_paid' => 0
        ];
    }
    
    $grouped_payments[$key]['records'][] = $row;
    $grouped_payments[$key]['total_cost'] += $row['cost'];
    $grouped_payments[$key]['total_paid'] += $row['amount_paid'];
}

$pageTitle = 'Student Profile';
include '../includes/sidebar.php'; 
?>

<style>
    /* Student Profile Specific Styles */
    .profile-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        padding: 20px 24px;
        margin-bottom: 16px;
        border: 1px solid var(--border-color);
        flex-shrink: 0;
    }
    
    .profile-avatar {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary), var(--info));
        color: white;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .profile-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 2px;
    }
    
    .profile-meta {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    
    .profile-meta i {
        width: 14px;
        text-align: center;
        margin-right: 4px;
        color: var(--text-light);
    }
    
    /* Nav Pills - Calm Style */
    .nav-pills-calm {
        gap: 0.5rem;
        margin-bottom: 16px;
        flex-shrink: 0;
    }
    
    .nav-pills-calm .nav-link {
        background: var(--bg-card);
        color: var(--text-muted);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 8px 16px;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all var(--transition-fast);
    }
    
    .nav-pills-calm .nav-link:hover {
        background: var(--bg-light);
        color: var(--text-primary);
    }
    
    .nav-pills-calm .nav-link.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    /* Term Selector */
    .term-selector-wrapper {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 16px 20px;
        margin-bottom: 16px;
        flex-shrink: 0;
    }
    
    .term-selector-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    
    /* Status Badge */
    .status-completed {
        background: var(--success-light);
        color: var(--success);
        border: 1px solid rgba(107, 158, 125, 0.2);
    }
    
    .status-progress {
        background: var(--info-light);
        color: var(--info);
        border: 1px solid rgba(106, 143, 179, 0.2);
    }
    
    /* Term Card */
    .term-card-header {
        background: var(--bg-card);
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .term-card-title {
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        font-size: 0.95rem;
    }
    
    .term-card-stats {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    
    .term-card-stats i {
        margin-right: 4px;
        color: var(--text-light);
    }
    
    /* Fade transition for term switching */
    .term-content {
        transition: opacity var(--transition-normal);
    }
    
    .term-content.fade:not(.show) {
        opacity: 0;
    }
</style>

<div class="main-content">
    
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Student Profile</h1>
            <p class="page-subtitle">View student details and academic records</p>
        </div>
        <a href="students.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Students
        </a>
    </div>

    <!-- Student Profile Card -->
    <div class="profile-card">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($student['firstname'], 0, 1) . substr($student['lastname'], 0, 1)); ?>
                </div>
                <div class="ms-3">
                    <h5 class="profile-name"><?php echo htmlspecialchars($student['lastname'] . ', ' . $student['firstname']); ?></h5>
                    <div class="profile-meta">
                        <span class="me-3"><i class="fas fa-id-card"></i><?php echo str_pad($student['id'], 4, '0', STR_PAD_LEFT); ?></span>
                        <span><i class="fas fa-user"></i><?php echo $student['age']; ?> years old</span>
                    </div>
                </div>
            </div>
            <div class="profile-meta text-end d-none d-md-block">
                <div class="mb-1"><i class="fas fa-envelope"></i><?php echo htmlspecialchars($student['email']); ?></div>
                <div><i class="fas fa-phone"></i><?php echo htmlspecialchars($student['phone'] ?? 'N/A'); ?></div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills nav-pills-calm" id="profileTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#history">
                <i class="fas fa-book-open me-2"></i>Enrollment History
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#payments">
                <i class="fas fa-receipt me-2"></i>Payment History
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#personal">
                <i class="fas fa-user-circle me-2"></i>Personal Info
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- HISTORY TAB -->
        <div class="tab-pane fade show active" id="history">
            
            <?php if (empty($grouped_history)): ?>
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h5>No Enrollment Records</h5>
                    <p>This student has not been enrolled in any subjects yet.</p>
                </div>
            <?php else: ?>
                
                <!-- Controls Row -->
                <div class="term-selector-wrapper">
                    <div class="row align-items-end g-3">
                        <div class="col-md-5">
                            <div class="term-selector-label">Select Term</div>
                            <select id="termSelector" class="form-select">
                                <?php foreach ($grouped_history as $key => $group): ?>
                                    <option value="term_<?php echo $key; ?>">
                                        <?php echo $group['label']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-7">
                            <div class="d-flex gap-2">
                                <button class="btn btn-success flex-fill" onclick="openTermModal('grades')">
                                    <i class="fas fa-file-alt me-2"></i>View Grades
                                </button>
                                <button class="btn btn-secondary flex-fill" onclick="openTermModal('schedule')">
                                    <i class="fas fa-calendar-alt me-2"></i>View Schedule
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Term Content Blocks -->
                <?php $counter = 0; ?>
                <?php foreach ($grouped_history as $key => $group): ?>
                    <div id="term_<?php echo $key; ?>" class="term-content fade <?php echo ($counter === 0) ? 'show active-term' : 'd-none'; ?>">
                        <div class="card">
                            <div class="term-card-header">
                                <div class="d-flex align-items-center gap-3">
                                    <?php if ($group['is_completed']): ?>
                                        <span class="badge status-completed rounded-pill px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i> Completed
                                        </span>
                                    <?php else: ?>
                                        <span class="badge status-progress rounded-pill px-3 py-2">
                                            <i class="fas fa-spinner me-1"></i> In Progress
                                        </span>
                                    <?php endif; ?>
                                    <h5 class="term-card-title"><?php echo $group['label']; ?></h5>
                                </div>
                                <div class="term-card-stats">
                                    <span class="me-3"><i class="fas fa-book"></i><?php echo count($group['subjects']); ?> Subjects</span>
                                    <span><i class="fas fa-layer-group"></i><?php echo $group['total_units']; ?> Units</span>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th class="ps-4" style="width: 20%;">Subject</th>
                                            <th style="width: 50%;">Description</th>
                                            <th style="width: 20%;">Date Enrolled</th>
                                            <th class="text-center" style="width: 10%;">Units</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($group['subjects'] as $subj): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge badge-subject"><?php echo htmlspecialchars($subj['subject_code']); ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($subj['description']); ?></td>
                                            <td class="text-muted">
                                                <?php echo date('M d, Y', strtotime($subj['enrolled_at'])); ?>
                                            </td>
                                            <td class="text-center fw-medium"><?php echo $subj['units']; ?></td>
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

        <!-- PAYMENTS TAB -->
        <div class="tab-pane fade" id="payments">
             <?php if (empty($grouped_payments)): ?>
                <div class="empty-state">
                    <i class="fas fa-receipt"></i>
                    <h5>No Payment Records</h5>
                    <p>No payment records have been found for this student.</p>
                </div>
            <?php else: ?>
                
                <!-- Controls Row -->
                <div class="term-selector-wrapper">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="term-selector-label">Select Term</div>
                            <select id="paymentTermSelector" class="form-select">
                                <?php foreach ($grouped_payments as $key => $group): ?>
                                    <option value="pay_term_<?php echo $key; ?>">
                                        <?php echo $group['label']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Payment Blocks -->
                <?php $p_counter = 0; ?>
                <?php foreach ($grouped_payments as $key => $group): ?>
                    <div id="pay_term_<?php echo $key; ?>" class="payment-content fade <?php echo ($p_counter === 0) ? 'show active-term' : 'd-none'; ?>">
                        <div class="card">
                            <div class="term-card-header">
                                <h5 class="term-card-title"><?php echo $group['label']; ?></h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead>
                                            <tr>
                                                <th class="ps-4">Date</th>
                                                <th>Remarks</th>
                                                <th class="text-end">Cost</th>
                                                <th class="text-end">Paid Amount</th>
                                                <th class="text-end pe-4">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($group['records'] as $pay): 
                                                $rowBalance = $pay['cost'] - $pay['amount_paid'];
                                            ?>
                                            <tr>
                                                <td class="ps-4 text-muted">
                                                    <?php echo date('M d, Y', strtotime($pay['payment_date'])); ?>
                                                </td>
                                                <td class="fw-medium"><?php echo htmlspecialchars($pay['remarks']); ?></td>
                                                <td class="text-end"><?php echo number_format($pay['cost'], 2); ?></td>
                                                <td class="text-end text-success"><?php echo number_format($pay['amount_paid'], 2); ?></td>
                                                <td class="text-end pe-4 fw-medium <?php echo $rowBalance > 0 ? 'text-danger' : 'text-success'; ?>">
                                                    <?php echo number_format($rowBalance, 2); ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr class="fw-semibold">
                                                <td colspan="2" class="text-end pe-3">Total</td>
                                                <td class="text-end"><?php echo number_format($group['total_cost'], 2); ?></td>
                                                <td class="text-end text-success"><?php echo number_format($group['total_paid'], 2); ?></td>
                                                <td class="text-end pe-4 <?php echo ($group['total_cost'] - $group['total_paid']) > 0 ? 'text-danger' : 'text-success'; ?>">
                                                    <?php echo number_format($group['total_cost'] - $group['total_paid'], 2); ?>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $p_counter++; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Personal Info Tab -->
        <div class="tab-pane fade" id="personal">
            <div class="empty-state">
                <i class="fas fa-user-circle"></i>
                <h5>Personal Info Module</h5>
                <p>This section is coming soon with detailed student information.</p>
            </div>
        </div>
    </div>
</div>

<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" id="reportHeader">
                <div>
                    <h5 class="modal-title" id="reportTitle">Term Report</h5>
                    <small class="text-muted" id="reportSubtitle">Academic records</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" id="reportTable">
                        <thead></thead>
                        <tbody id="reportBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    const termData = <?php echo json_encode($js_term_data); ?>;

    // History Term Selector
    document.getElementById('termSelector')?.addEventListener('change', function() {
        document.querySelectorAll('#history .term-content').forEach(el => {
            el.classList.add('d-none');
            el.classList.remove('show');
        });
        const target = document.getElementById(this.value);
        if(target) {
            target.classList.remove('d-none');
            setTimeout(() => target.classList.add('show'), 10);
        }
    });

    // Payment Term Selector
    document.getElementById('paymentTermSelector')?.addEventListener('change', function() {
        document.querySelectorAll('#payments .payment-content').forEach(el => {
            el.classList.add('d-none');
            el.classList.remove('show');
        });
        const target = document.getElementById(this.value);
        if(target) {
            target.classList.remove('d-none');
            setTimeout(() => target.classList.add('show'), 10);
        }
    });

    function openTermModal(type) {
        const activeTermKey = document.getElementById('termSelector').value;
        const data = termData[activeTermKey];
        if(!data) return;

        const header = document.getElementById('reportHeader');
        const title = document.getElementById('reportTitle');
        const subtitle = document.getElementById('reportSubtitle');
        const thead = document.querySelector('#reportTable thead');
        const tbody = document.getElementById('reportBody');
        
        tbody.innerHTML = ''; 

        if (type === 'grades') {
            header.style.borderLeft = '4px solid #48bb78';
            title.innerHTML = '<i class="fas fa-file-alt me-2"></i>Official Grades';
            subtitle.textContent = data.title;
            thead.innerHTML = '<tr><th class="ps-4">Subject</th><th>Description</th><th class="text-center">Midterm</th><th class="text-center">Final</th><th class="text-center">Units</th></tr>';
            data.subjects.forEach(sub => {
                const row = `<tr>
                    <td class="ps-4"><span class="badge badge-subject">${sub.code}</span></td>
                    <td>${sub.desc}</td>
                    <td class="text-center fw-medium">${sub.mid}</td>
                    <td class="text-center fw-medium ${sub.fin !== '--' ? 'text-success' : ''}">${sub.fin}</td>
                    <td class="text-center">${sub.units}</td>
                </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        } else if (type === 'schedule') {
            header.style.borderLeft = '4px solid #4a5568';
            title.innerHTML = '<i class="fas fa-calendar-alt me-2"></i>Class Schedule';
            subtitle.textContent = data.title;
            thead.innerHTML = '<tr><th class="ps-4">Subject</th><th>Description</th><th>Day</th><th>Time In</th><th>Time Out</th><th>Room</th><th class="text-center">Units</th></tr>';
            data.subjects.forEach(sub => {
                const row = `<tr>
                    <td class="ps-4"><span class="badge badge-subject">${sub.code}</span></td>
                    <td>${sub.desc}</td>
                    <td class="fw-medium">${sub.day}</td>
                    <td>${sub.in}</td>
                    <td>${sub.out}</td>
                    <td><span class="badge bg-light text-dark border">${sub.room}</span></td>
                    <td class="text-center">${sub.units}</td>
                </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }
        new bootstrap.Modal(document.getElementById('reportModal')).show();
    }
</script>

</body>
</html>