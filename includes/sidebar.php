<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS - Dashboard</title>
    <?php include 'header_styles.php'; ?>
    
    <style>
        /* --- REFACTORED SIDEBAR (Collapsed by Default) --- */
        .sidebar {
            width: 90px; /* Collapsed by default */
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--sidebar-bg);
            padding: 30px 10px; 
            transition: width 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar.expanded { 
            width: 280px; 
            padding: 30px 15px;
        }

        /* Brand Area */
        .brand-box {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 50px;
            color: white;
            transition: all 0.3s;
        }
        
        .brand-logo {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
            flex-shrink: 0;
        }
        
        .brand-text {
            font-size: 1.5rem;
            font-weight: 800;
            margin-left: 15px;
            letter-spacing: -1px;
            white-space: nowrap;
            display: none; /* Hidden by default */
        }
        
        .sidebar.expanded .brand-text { display: block; }

        /* Menu Links */
        .nav-link {
            display: flex;
            align-items: center;
            justify-content: center; /* Centered by default */
            color: #94a3b8; 
            text-decoration: none;
            padding: 14px 0;
            margin-bottom: 8px;
            border-radius: 16px; 
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 1rem;
            white-space: nowrap;
        }
        .sidebar.expanded .nav-link {
            justify-content: flex-start;
            padding: 14px 20px;
        }

        .nav-link i {
            width: 25px;
            font-size: 1.2rem;
            text-align: center;
            transition: margin 0.3s;
            margin-right: 0; /* No margin by default */
        }
        .sidebar.expanded .nav-link i {
            margin-right: 15px;
        }

        .nav-link span { display: none; } /* Hidden by default */
        .sidebar.expanded .nav-link span { display: inline; }

        /* Hover State */
        .sidebar.expanded .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }
        .sidebar:not(.expanded) .nav-link:hover {
             color: white;
        }

        /* Active State */
        .nav-link.active {
            background: var(--accent);
            color: white;
            box-shadow: 0 8px 20px -6px rgba(59, 130, 246, 0.6);
            font-weight: 600;
        }

        /* Content Area */
        .content {
            margin-left: 90px; /* Collapsed margin by default */
            padding: 40px;
            transition: margin-left 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            min-height: 100vh;
        }
        .content.expanded { margin-left: 280px; }

        /* Toggle Button - Minimalist & Integrated */
        #toggleBtn {
            position: absolute;
            top: 25px;
            right: -15px; /* Half hanging out */
            width: 30px;
            height: 30px;
            background: var(--sidebar-bg); /* Match sidebar */
            color: #94a3b8; /* Muted text */
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            z-index: 1001;
            font-size: 0.8rem;
        }
        #toggleBtn:hover {
            color: white;
            border-color: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }
    </style>
</head>
<body>

    <div class="sidebar" id="sidebar">
        <button id="toggleBtn"><i class="fas fa-chevron-right"></i></button>

        <div class="brand-box">
            <div class="brand-logo"><i class="fas fa-cube"></i></div>
            <span class="brand-text">EMS 23</span>
        </div>
        
        <div class="nav flex-column">
            <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-pie"></i><span>Dashboard</span>
            </a>
            <a href="courses.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'courses.php' ? 'active' : ''; ?>">
                <i class="fas fa-university"></i><span>Courses</span>
            </a>
            <a href="students.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'students.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-astronaut"></i><span>Students</span>
            </a>
            <a href="curriculum.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'curriculum.php' ? 'active' : ''; ?>">
                <i class="fas fa-book"></i><span>Curriculum</span>
            </a>
            <a href="enrollments.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'enrollments.php' ? 'active' : ''; ?>">
                <i class="fas fa-clipboard-check"></i><span>Enrollments</span>
            </a>
        </div>
    </div> <div class="content" id="content">
        <?php include '../includes/scripts.php'; ?>
        
        <script>
            // Definitive flicker-free sidebar script
            (function() {
                const sidebar = document.getElementById('sidebar');
                const content = document.getElementById('content');
                const toggleBtn = document.getElementById('toggleBtn');
                const icon = toggleBtn.querySelector('i');

                // 1. Temporarily disable transitions to prevent any flash
                sidebar.style.transition = 'none';
                content.style.transition = 'none';

                // 2. Define the function that applies the visual state
                const applyState = (isExpanded) => {
                    if (isExpanded) {
                        sidebar.classList.add('expanded');
                        content.classList.add('expanded');
                        icon.classList.remove('fa-chevron-right');
                        icon.classList.add('fa-chevron-left');
                    } else {
                        sidebar.classList.remove('expanded');
                        content.classList.remove('expanded');
                        icon.classList.remove('fa-chevron-left');
                        icon.classList.add('fa-chevron-right');
                    }
                };

                // 3. Apply the saved state immediately
                const isExpanded = localStorage.getItem('sidebarExpanded') === 'true';
                applyState(isExpanded);

                // 4. Re-enable transitions after the browser has painted the initial state
                // Using setTimeout ensures this runs after the current execution stack is clear.
                setTimeout(() => {
                    sidebar.style.transition = '';
                    content.style.transition = '';
                });

                // 5. Attach the click listener for user interactions
                toggleBtn.addEventListener('click', () => {
                    const isNowExpanded = !sidebar.classList.contains('expanded');
                    applyState(isNowExpanded);
                    localStorage.setItem('sidebarExpanded', isNowExpanded);
                });
            })();
        </script>