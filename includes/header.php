<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-bg: #f8fafc; /* Slate 50 */
        --sidebar-bg: #1e293b; /* Slate 900 */
        --sidebar-hover: #334155; /* Slate 700 */
        --accent-color: #3b82f6; /* Blue 500 */
        --text-main: #334155; /* Slate 700 */
        --text-light: #64748b; /* Slate 500 */
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--primary-bg);
        color: var(--text-main);
    }

    /* Card Styling */
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
        background: white;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    /* Modern Table Styling */
    .table thead th {
        background-color: #f1f5f9; /* Slate 100 */
        color: #475569; /* Slate 600 */
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
        padding: 16px;
    }
    
    .table tbody td {
        padding: 16px;
        vertical-align: middle;
        color: var(--text-main);
        border-bottom: 1px solid #f1f5f9;
    }

    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
    
    /* Buttons */
    .btn-primary {
        background-color: var(--sidebar-bg);
        border-color: var(--sidebar-bg);
        padding: 8px 16px;
    }
    .btn-primary:hover {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }
    
    /* Utility */
    .fw-bold { font-weight: 600 !important; }
    .text-secondary { color: var(--text-light) !important; }
    
    /* Main Content Padding (Matches sidebar logic) */
    .main-content {
        padding: 2rem;
    }
</style>