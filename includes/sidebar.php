<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS - Dashboard</title>
    <?php include 'header_styles.php'; ?>
    
    <style>
        /* --- NEW SIDEBAR DESIGN --- */
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--sidebar-bg);
            padding: 30px 15px; /* Added side padding for the "pill" look */
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar.collapsed { 
            width: 90px; 
            padding: 30px 10px;
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
        }
        
        .brand-text {
            font-size: 1.5rem;
            font-weight: 800;
            margin-left: 15px;
            letter-spacing: -1px;
            white-space: nowrap;
        }
        
        .sidebar.collapsed .brand-text { display: none; }

        /* Menu Links - "Pill Style" */
        .nav-link {
            display: flex;
            align-items: center;
            color: #94a3b8; /* Muted Blue-Grey */
            text-decoration: none;
            padding: 14px 20px;
            margin-bottom: 8px;
            border-radius: 16px; /* Rounded Pill */
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 1rem;
        }

        .nav-link i {
            width: 25px;
            font-size: 1.2rem;
            margin-right: 15px;
            text-align: center;
            transition: margin 0.3s;
        }

        /* Hover State */
        .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }

        /* Active State (The Blue Pill) */
        .nav-link.active {
            background: var(--accent);
            color: white;
            box-shadow: 0 8px 20px -6px rgba(59, 130, 246, 0.6);
            font-weight: 600;
        }

        /* Collapsed Adjustments */
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 14px 0;
        }
        .sidebar.collapsed .nav-link span { display: none; }
        .sidebar.collapsed .nav-link i { margin-right: 0; }

        /* Content Area */
        .content {
            margin-left: 280px;
            padding: 40px;
            transition: margin-left 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            min-height: 100vh;
        }
        .content.collapsed { margin-left: 90px; }

        /* Toggle Button */
        #toggleBtn {
            position: absolute;
            top: 35px;
            right: -20px;
            width: 40px;
            height: 40px;
            background: white;
            color: var(--sidebar-bg);
            border-radius: 50%;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s;
        }
        #toggleBtn:hover { transform: scale(1.1); color: var(--accent); }
    </style>
</head>
<body>

    <div class="sidebar" id="sidebar">
        <button id="toggleBtn"><i class="fas fa-chevron-left"></i></button>

        <div class="brand-box">
            <div class="brand-logo"><i class="fas fa-cube"></i></div>
            <span class="brand-text">EMS 23</span>
        </div>
        
        <div class="nav flex-column">
            <a href="/ice-ems/pages/dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-pie"></i><span>Dashboard</span>
            </a>
            <a href="/ice-ems/pages/students.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'students.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-astronaut"></i><span>Students</span>
            </a>
            <a href="/ice-ems/pages/curriculum.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'curriculum.php' ? 'active' : ''; ?>">
                <i class="fas fa-layer-group"></i><span>Curriculum</span>
            </a>
            <a href="/ice-ems/pages/enrollments.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'enrollments.php' ? 'active' : ''; ?>">
                <i class="fas fa-clipboard-check"></i><span>Enrollments</span>
            </a>
        </div>
    </div>

    <div class="content" id="content">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script>
            // Smooth Toggle Logic
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const toggleBtn = document.getElementById('toggleBtn');
            const icon = toggleBtn.querySelector('i');

            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('collapsed');
                
                if (sidebar.classList.contains('collapsed')) {
                    icon.classList.remove('fa-chevron-left');
                    icon.classList.add('fa-chevron-right');
                } else {
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-chevron-left');
                }
            });
        </script>