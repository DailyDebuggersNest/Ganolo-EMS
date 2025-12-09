<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS - <?php echo $pageTitle ?? 'Dashboard'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
       .sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    background: #343a40;
    color: white;
    padding-top: 20px;
    transition: width 0.3s ease;
    z-index: 1000;
}
.sidebar.collapsed {
    width: 150px; /* Your custom width */
}
.sidebar .ems-title {
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}
.sidebar.collapsed .ems-title {
    opacity: 0;
    visibility: hidden;
}
.sidebar a {
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    transition: padding 0.3s ease;
}
.sidebar.collapsed a {
    padding: 10px 15px; /* Keep some padding for breathing room */
    justify-content: flex-start; /* Left-align icons and text in collapsed mode */
}
.sidebar a:hover {
    background: #495057;
}
.sidebar .text-label {
    margin-left: 10px;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}
.sidebar.collapsed .text-label {
    opacity: 0;
    visibility: hidden;
}
.sidebar .toggle-btn {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    padding: 10px 20px;
    width: 100%;
    text-align: left; /* Keep toggle left-aligned for consistency */
    cursor: pointer;
    display: flex;
    align-items: center;
    margin-bottom: 10px; /* Add space below toggle to separate from menu */
}
.sidebar.collapsed .toggle-btn {
    justify-content: center; /* Center toggle icon when collapsed, but keep it distinct */
}
.content {
    margin-left: 250px;
    padding: 20px;
    transition: margin-left 0.3s ease;
}
.content.collapsed {
    margin-left: 150px; /* Match your collapsed width */
}

    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="ems-title">EMS</div>  <!-- Added back the EMS title -->
        <button class="toggle-btn" id="toggleBtn">
            <i class="fas fa-bars"></i>
            <span class="text-label">Menu</span>
        </button>
        <a href="/ice-ems/pages/dashboard.php">
            <i class="fas fa-tachometer-alt"></i>
            <span class="text-label">Dashboard</span>
        </a>
        <a href="/ice-ems/pages/students.php">
            <i class="fas fa-users"></i>
            <span class="text-label">Students</span>
        </a>
        <a href="/ice-ems/pages/courses.php">
            <i class="fas fa-book"></i>
            <span class="text-label">Courses</span>
        </a>
        <a href="/ice-ems/pages/enrollments.php">
            <i class="fas fa-clipboard-list"></i>
            <span class="text-label">Enrollments</span>
        </a>
    </div>
    <div class="content" id="content">
        <script>
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const toggleBtn = document.getElementById('toggleBtn');

            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('collapsed');
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>