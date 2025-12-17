<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>
    :root {
        --app-bg: #D7E8FA;
        /* Cool White */
        --sidebar-bg: #0f172a;
        --card-bg: #ffffff;
        --accent: #3b82f6;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --radius-lg: 20px;
        --radius-md: 12px;
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: var(--app-bg);
        color: var(--text-dark);
        overflow-x: hidden;
    }

    /* --- FIX 1: INPUT TRANSPARENCY & AUTOFILL --- */
    /* This forces the input background to be white, even when the browser tries to autocomplete it */
    input,
    select,
    textarea,
    .form-control {
        background-color: #ffffff !important;
        border: 2px solid #e2e8f0;
        border-radius: var(--radius-md);
        padding: 12px 15px;
        box-shadow: none !important;
        /* Removes default Bootstrap glow */
    }

    /* The 'Autofill Hack' - Forces white background on Chrome/Edge autofilled fields */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px white inset !important;
        -webkit-text-fill-color: var(--text-dark) !important;
    }

    .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
        /* Custom soft glow */
    }

    /* --- FIX 2: STRETCHED ICONS IN TABLES --- */
    /* This targets buttons specifically inside your data tables */
    .table .btn {
        width: 35px;
        /* Fixed width */
        height: 35px;
        /* Fixed height */
        padding: 0;
        /* Remove padding that stretches it */
        display: inline-flex;
        /* Perfect centering */
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        /* Soft square look */
        margin: 0 3px;
        /* Spacing between edit and delete */
        transition: all 0.2s ease;
    }

    /* Prevent the icon inside from stretching */
    .table .btn i {
        font-size: 1rem;
        width: auto;
        margin: 0;
    }

    /* Specific Colors for Actions */
    .btn-warning {
        background-color: #fbbf24;
        border: none;
        color: white;
    }

    .btn-danger {
        background-color: #ef4444;
        border: none;
    }

    .table .btn:hover {
        transform: translateY(-2px);
        /* Little hop on hover */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* --- CARDS & GENERAL UI --- */
    .card {
        background: var(--card-bg);
        border: none;
        border-radius: var(--radius-lg);
        box-shadow: 0 10px 30px -5px rgba(59, 130, 246, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -5px rgba(59, 130, 246, 0.15);
    }

    /* --- TABLES --- */
    .table thead th {
        background-color: #f8fafc;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding: 18px;
        border-bottom: 2px solid #e2e8f0;
    }

    .table tbody td {
        background-color: white;
        padding: 18px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-hover tbody tr:hover td {
        background-color: #f0f7ff;
        /* Theme tint on hover */
    }
    /* =========================================
       FIX 3: BADGE VISIBILITY OVERRIDE
       ========================================= */
    /* This overrides the default Bootstrap white text */
    .badge {
        color: var(--text-dark) !important;
        /* Forces dark text (slate-900) */
        background-color: #ffffff !important;
        /* Forces a white background */
        border: 1px solid #cbd5e1;
        /* Adds a subtle border so it pops */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        /* clear visibility */
    }

    /* Optional: If you want specific colors for different statuses, 
       ensure the text remains dark or the background is dark enough */
    .badge.bg-primary {
        background-color: var(--accent) !important;
        color: #ffffff !important;
        /* Keep white text ONLY on dark blue backgrounds */
        border: none;
    }

    .badge.bg-warning {
        background-color: #fcd34d !important;
        /* lighter yellow */
        color: #78350f !important;
        /* dark brown text for contrast */
        border: none;
    }
</style>