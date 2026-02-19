<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎓</text></svg>">
    <title>EMS - <?php echo $pageTitle ?? 'Dashboard'; ?></title>
    <?php include 'header_styles.php'; ?>
</head>
<body>
    <!-- Sidebar (Fixed) -->
    <div class="sidebar" id="sidebar">
        <!-- Toggle at top of sidebar -->
        <div class="sidebar-toggle-area">
            <button id="toggleBtn" aria-label="Toggle sidebar">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="brand-box">
            <div class="brand-logo"><i class="fas fa-graduation-cap"></i></div>
            <span class="brand-text">EMS</span>
        </div>
        
        <div class="nav-menu">
            <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right">
                <i class="fas fa-th-large"></i><span>Dashboard</span>
            </a>
            <a href="courses.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'courses.php' ? 'active' : ''; ?>" title="Courses" data-bs-toggle="tooltip" data-bs-placement="right">
                <i class="fas fa-book-open"></i><span>Courses</span>
            </a>
            <a href="students.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'students.php' ? 'active' : ''; ?>" title="Student List" data-bs-toggle="tooltip" data-bs-placement="right">
                <i class="fas fa-users"></i><span>Student List</span>
            </a>
            <a href="curriculum.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'curriculum.php' ? 'active' : ''; ?>" title="Curriculum" data-bs-toggle="tooltip" data-bs-placement="right">
                <i class="fas fa-list-alt"></i><span>Curriculum</span>
            </a>
            <a href="enrollments.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'enrollments.php' ? 'active' : ''; ?>" title="Enrollments" data-bs-toggle="tooltip" data-bs-placement="right">
                <i class="fas fa-clipboard-list"></i><span>Enrollments</span>
            </a>
        </div>
    </div>

    <!-- Main Content (Fit-to-Screen) -->
    <div class="content" id="content">
        <?php include '../includes/scripts.php'; ?>
        
        <script>
            // Sidebar Toggle (Smooth, No Flicker)
            (function() {
                const sidebar = document.getElementById('sidebar');
                const content = document.getElementById('content');
                const toggleBtn = document.getElementById('toggleBtn');

                // Disable transitions initially to prevent flash
                sidebar.style.transition = 'none';
                content.style.transition = 'none';

                const applyState = (isExpanded) => {
                    if (isExpanded) {
                        sidebar.classList.add('expanded');
                        content.classList.add('expanded');
                    } else {
                        sidebar.classList.remove('expanded');
                        content.classList.remove('expanded');
                    }
                };

                // Apply saved state immediately
                const isExpanded = localStorage.getItem('sidebarExpanded') === 'true';
                applyState(isExpanded);

                // Re-enable transitions after first paint
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        sidebar.style.transition = '';
                        content.style.transition = '';
                    });
                });

                // Toggle handler
                toggleBtn.addEventListener('click', () => {
                    const isNowExpanded = !sidebar.classList.contains('expanded');
                    applyState(isNowExpanded);
                    localStorage.setItem('sidebarExpanded', isNowExpanded);
                });
            })();

            // Initialize Bootstrap tooltips on sidebar nav links
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.nav-menu [data-bs-toggle="tooltip"]').forEach(function(el) {
                    new bootstrap.Tooltip(el);
                });
            });
        </script>
