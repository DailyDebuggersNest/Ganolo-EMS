<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/all.min.css" rel="stylesheet">
<link href="../assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<style>
    :root {
        /* --- CALM & PROFESSIONAL PALETTE --- */
        --app-bg: #f8fafc;        /* Soft Porcelain */
        --sidebar-bg: #1e293b;    /* Deep Charcoal */
        --card-bg: #ffffff;       /* Pure White */
        
        /* Accents */
        --primary: #475569;       /* Slate 600 - The "Professional" Grey-Blue */
        --primary-hover: #334155; /* Slate 700 */
        --accent: #64748b;        /* Slate 500 */
        
        /* Text */
        --text-dark: #1e293b;     /* Slate 900 */
        --text-muted: #94a3b8;    /* Slate 400 */
        
        /* Functional Colors (Softened) */
        --success: #10b981;       /* Emerald */
        --warning: #f59e0b;       /* Amber */
        --danger: #ef4444;        /* Rose */
        --info: #3b82f6;          /* Sky Blue */

        --radius-lg: 16px;
        --radius-md: 10px;
    }

    body {
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        background-color: var(--app-bg);
        color: var(--text-dark);
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased; /* Crisper text */
    }

    /* --- SOFT CARDS --- */
    .card {
        background: var(--card-bg);
        border: 1px solid #f1f5f9; /* Very subtle border */
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02); /* Ultra soft shadow */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
    }

    /* --- REFINED TABLES --- */
    .table thead th {
        background-color: transparent;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 20px 16px;
        border-bottom: 2px solid #f1f5f9;
    }

    .table tbody td {
        padding: 16px;
        vertical-align: middle;
        color: var(--text-dark);
        border-bottom: 1px solid #f8fafc;
        font-size: 0.95rem;
    }

    .table-hover tbody tr:hover td {
        background-color: #f8fafc; /* Very subtle hover tint */
    }

    /* --- CALM INPUTS --- */
    .form-control, .form-select {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: var(--radius-md);
        padding: 12px 16px;
        color: var(--text-dark);
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1); /* Soft Slate Glow */
    }

    /* --- PROFESSIONAL BUTTONS --- */
    .btn {
        padding: 10px 20px;
        border-radius: var(--radius-md);
        font-weight: 500;
        letter-spacing: 0.02em;
        border: none;
        transition: all 0.2s;
    }

    /* Primary - Slate */
    .btn-primary {
        background-color: var(--primary);
        color: white;
    }
    .btn-primary:hover {
        background-color: var(--primary-hover);
        transform: translateY(-1px);
    }

    /* Warning - Amber (Edit) */
    .btn-warning {
        background-color: white;
        color: var(--warning);
        border: 1px solid #e2e8f0;
    }
    .btn-warning:hover {
        background-color: var(--warning);
        color: white;
        border-color: var(--warning);
    }

    /* Danger - Rose (Delete) */
    .btn-danger {
        background-color: white;
        color: var(--danger);
        border: 1px solid #e2e8f0;
    }
    .btn-danger:hover {
        background-color: var(--danger);
        color: white;
        border-color: var(--danger);
    }

    /* Info - Sky (View/Profile) */
    .btn-info {
        background-color: white;
        color: var(--info);
        border: 1px solid #e2e8f0;
    }
    .btn-info:hover {
        background-color: var(--info);
        color: white;
        border-color: var(--info);
    }

    /* Rounded Icon Buttons (Table Actions) */
    .table .btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        margin: 0 2px;
    }

    /* --- BADGES (Subtle) --- */
    .badge {
        font-weight: 500;
        padding: 6px 10px;
        border-radius: 6px;
        border: none !important;
    }
    .badge.bg-primary { background-color: #e2e8f0 !important; color: var(--primary) !important; }
    .badge.bg-success { background-color: #dcfce7 !important; color: var(--success) !important; }
    .badge.bg-warning { background-color: #fef3c7 !important; color: #b45309 !important; }
    .badge.bg-info    { background-color: #e0f2fe !important; color: var(--info) !important; }
    
</style>