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

<div class="container-fluid main-content">
    
    <!-- HEADER & COMPACT PROFILE -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-secondary mb-0">Student Profile</h2>
        <a href="students.php" class="btn btn-secondary btn-sm rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>

    <!-- COMPACT STUDENT CARD -->
    <div class="card border-0 shadow-sm p-3 mb-3 bg-white">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px; font-size: 1.4rem;">
                    <?php echo strtoupper(substr($student['firstname'], 0, 1) . substr($student['lastname'], 0, 1)); ?>
                </div>
                <div class="ms-3">
                    <h5 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($student['lastname'] . ', ' . $student['firstname']); ?></h5>
                    <div class="text-muted small">
                        <span class="me-3"><i class="fas fa-id-card me-1"></i> <?php echo str_pad($student['id'], 4, '0', STR_PAD_LEFT); ?></span>
                        <span><i class="fas fa-book-reader me-1"></i> <?php echo $student['age']; ?> Y/O</span>
                    </div>
                </div>
            </div>
            <div class="text-end text-muted small d-none d-md-block">
                <div><i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($student['email']); ?></div>
                <div><i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($student['phone'] ?? 'N/A'); ?></div>
            </div>
        </div>
    </div>

    <!-- CONTENT AREA -->
    <div class="row">
        <div class="col-12">
            <!-- TABS -->
            <ul class="nav nav-pills mb-3" id="profileTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold px-4" data-bs-toggle="tab" data-bs-target="#history">
                        Enrollment History
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold px-4" data-bs-toggle="tab" data-bs-target="#payments">
                        Payment History
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold px-4 text-muted" data-bs-toggle="tab" data-bs-target="#personal">
                        Personal Info
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- HISTORY TAB -->
                <div class="tab-pane fade show active" id="history">
                    
                    <?php if (empty($grouped_history)): ?>
                        <div class="text-center py-5 text-muted bg-white rounded shadow-sm">
                            <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                            <p>No enrollment records found.</p>
                        </div>
                    <?php else: ?>
                        
                        <!-- CONTROLS ROW -->
                        <div class="row align-items-end mb-4 g-3">
                            <div class="col-md-5">
                                <label class="fw-bold text-secondary mb-1 small text-uppercase">Select Term</label>
                                <select id="termSelector" class="form-select form-select-md shadow-sm border-primary fw-bold text-primary">
                                    <?php foreach ($grouped_history as $key => $group): ?>
                                        <option value="term_<?php echo $key; ?>">
                                            <?php echo $group['label']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-7">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success text-white shadow-sm flex-fill" onclick="openTermModal('grades')">
                                        <i class="fas fa-file-alt me-2"></i>View Grades
                                    </button>
                                    <button class="btn btn-secondary text-white shadow-sm flex-fill" onclick="openTermModal('schedule')">
                                        <i class="fas fa-calendar-alt me-2"></i>View Schedule
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- CONTENT BLOCKS (Subject List) -->
                        <?php $counter = 0; ?>
                        <?php foreach ($grouped_history as $key => $group): ?>
                            <div id="term_<?php echo $key; ?>" class="term-content fade <?php echo ($counter === 0) ? 'show active-term' : 'd-none'; ?>">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white p-3 d-flex align-items-center">
                                        <div class="me-3">
                                            <?php if ($group['is_completed']): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">
                                                    <i class="fas fa-check-circle me-1"></i> Completed
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3">
                                                    <i class="fas fa-spinner me-1"></i> In Progress
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-0 me-auto">
                                            <?php echo $group['label']; ?>
                                        </h5>
                                        <div class="text-muted small">
                                            <span class="me-3"><i class="fas fa-book me-1"></i> <?php echo count($group['subjects']); ?> Subs</span>
                                            <span><i class="fas fa-layer-group me-1"></i> <?php echo $group['total_units']; ?> Units</span>
                                        </div>
                                    </div>

                                    <div class="card-body p-0">
                                        <table class="table table-hover mb-0 align-middle">
                                            <thead class="bg-light text-secondary small text-uppercase">
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
                                                    <td class="ps-4 fw-bold text-primary"><?php echo htmlspecialchars($subj['subject_code']); ?></td>
                                                    <td><?php echo htmlspecialchars($subj['description']); ?></td>
                                                    <td class="text-muted font-monospace small">
                                                        <?php echo date('m/d/y', strtotime($subj['enrolled_at'])); ?>
                                                    </td>
                                                    <td class="text-center fw-bold"><?php echo $subj['units']; ?></td>
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
                        <div class="text-center py-5 text-muted bg-white rounded shadow-sm">
                            <i class="fas fa-receipt fa-3x mb-3 opacity-25"></i>
                            <p>No payment records found.</p>
                        </div>
                    <?php else: ?>
                        
                        <!-- CONTROLS ROW -->
                        <div class="row mb-4 g-3">
                            <div class="col-md-5">
                                <label class="fw-bold text-secondary mb-1 small text-uppercase">Select Term</label>
                                <select id="paymentTermSelector" class="form-select form-select-md shadow-sm border-info fw-bold text-info">
                                    <?php foreach ($grouped_payments as $key => $group): ?>
                                        <option value="pay_term_<?php echo $key; ?>">
                                            <?php echo $group['label']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- PAYMENT BLOCKS -->
                        <?php $p_counter = 0; ?>
                        <?php foreach ($grouped_payments as $key => $group): ?>
                            <div id="pay_term_<?php echo $key; ?>" class="payment-content fade <?php echo ($p_counter === 0) ? 'show active-term' : 'd-none'; ?>">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white p-3">
                                        <h5 class="fw-bold text-dark mb-0"><?php echo $group['label']; ?></h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0 align-middle">
                                                <thead class="bg-light text-secondary small text-uppercase">
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
                                                        <td class="ps-4 text-muted font-monospace small">
                                                            <?php echo date('m/d/Y', strtotime($pay['payment_date'])); ?>
                                                        </td>
                                                        <td class="fw-bold text-secondary"><?php echo htmlspecialchars($pay['remarks']); ?></td>
                                                        <td class="text-end"><?php echo number_format($pay['cost'], 2); ?></td>
                                                        <td class="text-end text-success"><?php echo number_format($pay['amount_paid'], 2); ?></td>
                                                        <td class="text-end pe-4 fw-bold <?php echo $rowBalance > 0 ? 'text-danger' : 'text-success'; ?>">
                                                            <?php echo number_format($rowBalance, 2); ?>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                                <tfoot class="bg-light fw-bold border-top">
                                                    <tr>
                                                        <td colspan="2" class="text-end text-uppercase text-secondary pe-3">Total</td>
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

                <div class="tab-pane fade" id="personal"><div class="card p-5 text-center text-muted">Personal Info Module Coming Soon</div></div>
            </div>
        </div>
    </div>
</div>

<!-- LARGE REPORT MODAL (Existing) -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0">
            <div class="modal-header text-white" id="reportHeader">
                <h5 class="modal-title fw-bold" id="reportTitle">Term Report</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0 align-middle" id="reportTable">
                        <thead class="bg-light text-secondary text-uppercase small"></thead>
                        <tbody id="reportBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    const termData = <?php echo json_encode($js_term_data); ?>;

    // HISTORY TERM SELECTOR
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

    // PAYMENT TERM SELECTOR
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
        const thead = document.querySelector('#reportTable thead');
        const tbody = document.getElementById('reportBody');
        
        tbody.innerHTML = ''; 

        if (type === 'grades') {
            header.className = 'modal-header bg-success text-white';
            title.innerHTML = `<i class="fas fa-file-alt me-2"></i>OFFICIAL GRADES: ${data.title}`;
            thead.innerHTML = `<tr><th class="ps-4">Subject</th><th>Description</th><th class="text-center">Midterm</th><th class="text-center">Final</th><th class="text-center">Units</th></tr>`;
            data.subjects.forEach(sub => {
                const row = `<tr><td class="ps-4 fw-bold text-primary">${sub.code}</td><td>${sub.desc}</td><td class="text-center fw-bold">${sub.mid}</td><td class="text-center fw-bold ${sub.fin !== '--' ? 'text-success' : ''}">${sub.fin}</td><td class="text-center">${sub.units}</td></tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        } else if (type === 'schedule') {
            header.className = 'modal-header bg-secondary text-white';
            title.innerHTML = `<i class="fas fa-calendar-alt me-2"></i>CLASS SCHEDULE: ${data.title}`;
            thead.innerHTML = `<tr><th class="ps-4">Subject</th><th>Description</th><th>Day</th><th>Time In</th><th>Time Out</th><th>Room</th><th class="text-center">Units</th></tr>`;
            data.subjects.forEach(sub => {
                const row = `<tr><td class="ps-4 fw-bold text-primary">${sub.code}</td><td>${sub.desc}</td><td class="fw-bold">${sub.day}</td><td>${sub.in}</td><td>${sub.out}</td><td><span class="badge bg-light text-dark border">${sub.room}</span></td><td class="text-center">${sub.units}</td></tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }
        new bootstrap.Modal(document.getElementById('reportModal')).show();
    }
</script>

</body>
</html>