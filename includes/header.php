<!-- Local CSS (No External CDN Dependencies - Offline Ready) -->
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/all.min.css" rel="stylesheet">
<link href="../assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="../assets/css/app.css" rel="stylesheet">

<style>
    /* ═══ CALM PROFESSIONAL THEME ═══ */
    :root {
        --bg-app: #f8f9fa;
        --bg-card: #ffffff;
        --bg-sidebar: #1a1d23;
        --primary: #4a5568;
        --primary-hover: #2d3748;
        --text-primary: #1a202c;
        --text-secondary: #4a5568;
        --text-muted: #718096;
        --border-color: #e2e8f0;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        background-color: var(--bg-app);
        color: var(--text-primary);
        -webkit-font-smoothing: antialiased;
    }

    /* Card Styling */
    .card {
        border: 1px solid var(--border-color);
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        transition: all 0.25s ease;
        background: var(--bg-card);
    }
    .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }

    /* Modern Table Styling */
    .table thead th {
        background-color: var(--bg-app);
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.06em;
        border-bottom: 1px solid var(--border-color);
        padding: 14px 20px;
    }
    
    .table tbody td {
        padding: 16px 20px;
        vertical-align: middle;
        color: var(--text-primary);
        border-bottom: 1px solid #edf2f7;
    }

    .table-hover tbody tr:hover {
        background-color: var(--bg-app);
    }
    
    /* Buttons */
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
        transition: all 0.2s ease;
    }
    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-1px);
    }
    
    /* Utility */
    .fw-bold { font-weight: 600 !important; }
    .text-secondary { color: var(--text-secondary) !important; }
    
    /* Main Content Padding */
    .main-content {
        padding: 0;
        max-width: 1400px;
        margin: 0 auto;
    }
</style>