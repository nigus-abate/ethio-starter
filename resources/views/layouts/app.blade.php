<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('app_name') }} | {{ $title ?? 'Dashboard' }}</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        
    @stack('styles')
    <style>
        :root {
            /* Colors */
            --primary-color: #4361ee;
            --primary-light: #e6ecfe;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --success-color: #4ade80;
            --warning-color: #fbbf24;
            --danger-color: #f87171;
            --info-color: #60a5fa;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --text-primary: #334155;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            
            /* Layout */
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
            --top-nav-height: 70px;
            --card-radius: 12px;
            --transition-speed: 0.3s;
            --body-bg: #f1f5f9;
            
            /* Borders */
            --border-color: #e2e8f0;
            --border-dark: #334155;
            
            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 2px 5px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        /* Dark Mode Variables */
        [data-bs-theme="dark"] {
            --body-bg: #111827;
            --dark-color: #f8fafc;
            --light-color: #1e293b;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --border-dark: #475569;
            --primary-light: rgba(67, 97, 238, 0.2);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.2);
            --shadow-md: 0 2px 5px rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* ========== Base Styles ========== */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--body-bg);
            color: var(--text-primary);
            overflow-x: hidden;
            font-weight: 400;
            line-height: 1.5;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* ========== Sidebar Styles ========== */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            color: var(--text-secondary);
            transition: all var(--transition-speed) ease;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
            border-right: 1px solid var(--border-color);
        }
        
        [data-bs-theme="dark"] .sidebar {
            background-color: var(--light-color);
            border-right-color: var(--border-dark);
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar-header {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            height: var(--top-nav-height);
        }
        
        [data-bs-theme="dark"] .sidebar-header {
            border-bottom-color: var(--border-dark);
        }
        
        .brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .logo-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }
        
        .logo-icon img {
            max-width: 100%;
            max-height: 100%;
        }
        
        .sidebar.collapsed .logo-text {
            display: none;
        }
        
        .btn-close-sidebar {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.2rem;
            cursor: pointer;
            display: none;
        }
        
        /* Sidebar Scrollable Area */
        .sidebar-scrollable {
            position: absolute;
            top: var(--top-nav-height);
            bottom: 150px;
            left: 0;
            right: 0;
            overflow-y: auto;
            padding-bottom: 20px;
            scrollbar-width: thin;
            scrollbar-color: var(--border-color) transparent;
        }
        
        .sidebar-scrollable::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-scrollable::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-scrollable::-webkit-scrollbar-thumb {
            background-color: var(--border-color);
            border-radius: 3px;
        }
        
        [data-bs-theme="dark"] .sidebar-scrollable {
            scrollbar-color: var(--border-dark) transparent;
        }
        
        [data-bs-theme="dark"] .sidebar-scrollable::-webkit-scrollbar-thumb {
            background-color: var(--border-dark);
        }
        
        /* Navigation */
        .sidebar-nav {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
            border-radius: var(--card-radius);
            margin: 0 10px;
        }
        
        .nav-link:hover, 
        .nav-link.active,
        .nav-link:focus {
            background-color: var(--primary-light);
            color: var(--primary-color);
            outline: none;
        }
        
        .nav-link.active {
            font-weight: 600;
        }
        
        .nav-link i {
            font-size: 1.1rem;
            margin-right: 12px;
            width: 24px;
            text-align: center;
            color: var(--text-muted);
        }
        
        .nav-link:hover i, 
        .nav-link.active i,
        .nav-link:focus i {
            color: var(--primary-color);
        }
        
        /* Collapsed State */
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.3rem;
        }
        
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px 0;
        }
        
        /* Submenu Styles */
        .has-submenu .submenu {
            list-style: none;
            padding-left: 0;
            background-color: white;
            display: none;
            margin-top: 5px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1001;
        }
        
        [data-bs-theme="dark"] .has-submenu .submenu {
            background-color: var(--light-color);
            border: 1px solid var(--border-dark);
        }
        
        .has-submenu.active .submenu {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }
        
        .has-submenu .submenu .nav-link {
            padding-left: 56px;
            font-size: 0.9rem;
            font-weight: 400;
            margin: 0 10px;
        }
        
        .sidebar.collapsed .has-submenu .submenu {
            position: absolute;
            left: 100%;
            top: 0;
            width: 220px;
            background-color: white;
            box-shadow: var(--shadow-lg);
            border-radius: var(--card-radius);
            z-index: 1000;
            padding: 5px 0;
        }
        
        [data-bs-theme="dark"] .sidebar.collapsed .has-submenu .submenu {
            background-color: var(--light-color);
        }
        
        .sidebar.collapsed .has-submenu .submenu .nav-link {
            padding-left: 20px;
        }
        
        .has-submenu > .nav-link::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            transition: transform 0.3s;
            color: var(--text-muted);
            font-size: 0.7rem;
        }
        
        .has-submenu.active > .nav-link::after {
            transform: rotate(180deg);
            color: var(--primary-color);
        }
        
        .sidebar.collapsed .has-submenu > .nav-link::after {
            display: none;
        }
        
        /* Sidebar Footer */
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 15px;
            border-top: 1px solid var(--border-color);
        }
        
        [data-bs-theme="dark"] .sidebar-footer {
            border-top-color: var(--border-dark);
        }
        
        .theme-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        [data-bs-theme="dark"] .theme-toggle {
            color: var(--text-muted);
        }
        
        .sidebar.collapsed .theme-toggle span {
            display: none;
        }
        
        /* User Profile */
        .user-profile {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: var(--card-radius);
            transition: background-color 0.2s;
            cursor: pointer;
        }
        
        .user-profile:hover {
            background-color: var(--primary-light);
        }
        
        [data-bs-theme="dark"] .user-profile:hover {
            background-color: var(--border-dark);
        }
        
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-right: 10px;
            flex-shrink: 0;
        }
        
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .user-info {
            line-height: 1.3;
            overflow: hidden;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--dark-color);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .sidebar.collapsed .user-info {
            display: none;
        }
        
        /* ========== Main Content Styles ========== */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin var(--transition-speed);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        /* Top Navigation */
        .top-nav {
            background-color: white;
            padding: 0 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
            height: var(--top-nav-height);
            border-bottom: 1px solid var(--border-color);
        }
        
        [data-bs-theme="dark"] .top-nav {
            background-color: var(--light-color);
            border-bottom-color: var(--border-dark);
        }
        
        .nav-left, .nav-right {
            display: flex;
            align-items: center;
        }
        
        .btn-toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-right: 15px;
            cursor: pointer;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }
        
        .btn-toggle-sidebar:hover {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }
        
        [data-bs-theme="dark"] .btn-toggle-sidebar {
            color: var(--text-secondary);
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .search-box input {
            padding: 8px 15px 8px 35px;
            border-radius: var(--card-radius);
            border: 1px solid var(--border-color);
            width: 200px;
            transition: all 0.3s;
            background-color: white;
            color: var(--text-primary);
            font-size: 0.9rem;
            height: 36px;
        }
        
        [data-bs-theme="dark"] .search-box input {
            background-color: var(--light-color);
            border-color: var(--border-dark);
            color: var(--text-primary);
        }
        
        .search-box input:focus {
            width: 250px;
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .btn-notification, .btn-message {
            background: none;
            border: none;
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-right: 10px;
            position: relative;
            cursor: pointer;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }
        
        [data-bs-theme="dark"] .btn-notification,
        [data-bs-theme="dark"] .btn-message {
            color: var(--text-secondary);
        }
        
        .btn-notification:hover, .btn-message:hover {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }
        
        .badge {
            position: relative;
            top: 2px;
            right: 2px;
            font-size: 0.6rem;
            font-weight: 600;
            padding: 3px 5px;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-user-dropdown {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            color: var(--text-primary);
            cursor: pointer;
            padding: 5px;
            border-radius: var(--card-radius);
            transition: all 0.2s;
        }
        
        [data-bs-theme="dark"] .btn-user-dropdown {
            color: var(--text-primary);
        }
        
        .btn-user-dropdown:hover {
            background-color: var(--primary-light);
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            overflow: hidden;
        }
        
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Content Area */
        .content-area {
            padding: 25px;
            background-color: var(--body-bg);
            flex: 1;
        }
        
        .page-header {
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-header h1 {
            font-weight: 700;
            color: var(--dark-color);
            font-size: 1.75rem;
            margin: 0;
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
            font-size: 0.85rem;
            margin-top: 5px;
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .breadcrumb-item.active {
            color: var(--text-muted);
        }
                
        /* Stat Cards */
        .stat-card {
            background-color: white;
            border-radius: var(--card-radius);
            padding: 20px;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            height: 100%;
            border-left: 4px solid var(--primary-color);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        [data-bs-theme="dark"] .stat-card {
            background-color: var(--light-color);
            border-left-color: var(--primary-color);
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--card-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .stat-icon.bg-primary {
            background-color: var(--primary-color);
        }
        
        .stat-icon.bg-secondary {
            background-color: var(--secondary-color);
        }
        
        .stat-icon.bg-accent {
            background-color: var(--accent-color);
        }
        
        .stat-icon.bg-warning {
            background-color: var(--warning-color);
        }
        
        .stat-icon.bg-info {
            background-color: var(--info-color);
        }
        
        .stat-icon.bg-danger {
            background-color: var(--danger-color);
        }
        
        .stat-icon.bg-success {
            background-color: var(--success-color);
        }
        
        .stat-info h3 {
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--dark-color);
            font-size: 1.5rem;
        }
        
        .stat-info p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .stat-growth {
            margin-left: auto;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .stat-growth i {
            margin-right: 3px;
            font-size: 0.7rem;
        }
        
        .stat-growth.success {
            color: var(--success-color);
        }
        
        .stat-growth.danger {
            color: var(--danger-color);
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: var(--card-radius);
            box-shadow: var(--shadow-md);
            margin-bottom: 25px;
            background-color: white;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        [data-bs-theme="dark"] .card {
            background-color: var(--light-color);
        }
        
        .card:hover {
            box-shadow: var(--shadow-lg);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--border-color);
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: var(--card-radius) var(--card-radius) 0 0 !important;
        }
        
        [data-bs-theme="dark"] .card-header {
            background-color: var(--light-color);
            border-bottom-color: var(--border-dark);
        }
        
        .card-header h5 {
            font-weight: 600;
            margin: 0;
            color: var(--dark-color);
            font-size: 1.1rem;
        }
        
        .card-actions {
            display: flex;
            align-items: center;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* Buttons */
        .btn {
            border-radius: var(--card-radius);
            font-weight: 500;
            padding: 8px 16px;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #3a56e8;
            border-color: #3a56e8;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }
        
        .btn-accent:hover {
            background-color: #3abce8;
            border-color: #3abce8;
            color: white;
        }
        
        /* Activity List */
        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .activity-list li {
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
            display: flex;
        }
        
        [data-bs-theme="dark"] .activity-list li {
            border-bottom-color: var(--border-dark);
        }
        
        .activity-list li:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        
        .activity-icon.primary {
            background-color: var(--primary-color);
        }
        
        .activity-icon.secondary {
            background-color: var(--secondary-color);
        }
        
        .activity-icon.accent {
            background-color: var(--accent-color);
        }
        
        .activity-icon.warning {
            background-color: var(--warning-color);
        }
        
        .activity-icon.info {
            background-color: var(--info-color);
        }
        
        .activity-icon.danger {
            background-color: var(--danger-color);
        }
        
        .activity-details p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--dark-color);
            font-weight: 500;
        }
        
        .activity-details span {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: block;
            margin-top: 3px;
        }
        
        /* Table Styles */
        .table {
            margin-bottom: 0;
            color: var(--text-primary);
            font-size: 0.85rem;
        }
        
        .table th {
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: none;
            border-bottom: 1px solid var(--border-color);
            padding-top: 12px;
            padding-bottom: 12px;
        }
        
        [data-bs-theme="dark"] .table th {
            border-bottom-color: var(--border-dark);
        }
        
        .table td {
            vertical-align: middle;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
            padding: 12px;
        }
        
        [data-bs-theme="dark"] .table td {
            border-bottom-color: var(--border-dark);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.03);
        }
        
        [data-bs-theme="dark"] .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.1);
        }
        
        .avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            color: var(--text-muted);
            overflow: hidden;
        }
        
        .avatar-sm img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Badges */
        .badge {
            border-radius: 20px;
            padding: 5px 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge.bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .badge.bg-secondary {
            background-color: var(--secondary-color) !important;
        }
        
        .badge.bg-accent {
            background-color: var(--accent-color) !important;
            color: white;
        }
        
        .badge.bg-success {
            background-color: var(--success-color) !important;
            color: white;
        }
        
        .badge.bg-warning {
            background-color: var(--warning-color) !important;
            color: white;
        }
        
        .badge.bg-danger {
            background-color: var(--danger-color) !important;
            color: white;
        }
        
        .badge.bg-info {
            background-color: var(--info-color) !important;
            color: white;
        }
        
        /* Events Timeline */
        .events-timeline {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .event-item {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        [data-bs-theme="dark"] .event-item {
            border-bottom-color: var(--border-dark);
        }
        
        .event-item:last-child {
            border-bottom: none;
        }
        
        .event-date {
            width: 50px;
            height: 50px;
            border-radius: var(--card-radius);
            background-color: var(--primary-light);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .event-date .day {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--dark-color);
        }
        
        .event-date .month {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-top: -5px;
        }
        
        .event-details {
            flex: 1;
        }
        
        .event-details h6 {
            font-weight: 600;
            margin: 0 0 5px 0;
            color: var(--dark-color);
            font-size: 0.95rem;
        }
        
        .event-details p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 0;
        }
        
        .event-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
        }
        
        /* Footer */
        .footer {
            background-color: white;
            padding: 15px 25px;
            border-top: 1px solid var(--border-color);
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        
        [data-bs-theme="dark"] .footer {
            background-color: var(--light-color);
            border-top-color: var(--border-dark);
        }
        
        /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        /* Notification Dropdown */
        .dropdown-notifications {
            width: 350px;
            padding: 0;
            border-radius: var(--card-radius);
            border: 1px solid var(--border-color);
            overflow: hidden;
            background-color: white;
        }
        
        [data-bs-theme="dark"] .dropdown-notifications {
            background-color: var(--light-color);
            border-color: var(--border-dark);
        }
        
        .notification-item {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            display: block;
            color: var(--text-primary);
            text-decoration: none;
            transition: background-color 0.2s;
        }
        
        [data-bs-theme="dark"] .notification-item {
            border-bottom-color: var(--border-dark);
        }
        
        .notification-item.unread {
            background-color: var(--primary-light);
        }
        
        [data-bs-theme="dark"] .notification-item.unread {
            background-color: rgba(15, 23, 42, 0.5);
        }
        
        .notification-item:hover {
            background-color: var(--primary-light);
        }
        
        [data-bs-theme="dark"] .notification-item:hover {
            background-color: var(--border-dark);
        }
        
        .notification-item .notification-title {
            font-weight: 500;
            margin-bottom: 3px;
        }
        
        .notification-item .notification-time {
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        
        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow-lg);
            border-radius: var(--card-radius);
            padding: 8px 0;
            border: 1px solid var(--border-color);
            background-color: white;
        }
        
        [data-bs-theme="dark"] .dropdown-menu {
            background-color: var(--light-color);
            border-color: var(--border-dark);
        }
        
        .dropdown-item {
            padding: 8px 16px;
            color: var(--text-primary);
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }
        
        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: var(--border-dark);
        }
        
        .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 8px;
            color: var(--text-muted);
        }
        
        .dropdown-item:hover i {
            color: var(--primary-color);
        }
        
        .dropdown-divider {
            border-color: var(--border-color);
            margin: 5px 0;
        }
        
        [data-bs-theme="dark"] .dropdown-divider {
            border-color: var(--border-dark);
        }
        
        /* Alerts */
        .alert {
            border-radius: var(--card-radius);
            border: none;
            padding: 12px 16px;
            font-size: 0.9rem;
        }
        
        .alert-primary {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }
        
        .alert-success {
            background-color: rgba(74, 222, 128, 0.1);
            color: #16a34a;
        }
        
        .alert-danger {
            background-color: rgba(248, 113, 113, 0.1);
            color: #dc2626;
        }
        
        .alert-warning {
            background-color: rgba(251, 191, 36, 0.1);
            color: #d97706;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.3s ease forwards;
        }
        
        /* Badge Pulse Animation */
        .badge-pulse {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1050;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .sidebar.collapsed {
                width: var(--sidebar-width);
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .btn-close-sidebar {
                display: block;
            }
            
            .sidebar.collapsed ~ .overlay {
                display: none;
            }
            
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                display: none;
            }
            
            .sidebar.show ~ .overlay {
                display: block;
            }
            
            .search-box input {
                width: 150px;
            }
            
            .search-box input:focus {
                width: 180px;
            }
            
            /* Mobile submenu adjustments */
            .has-submenu > .nav-link::after {
                display: block !important;
            }
            
            .has-submenu.active .submenu {
                position: static;
                width: 100%;
                background-color: var(--primary-light);
            }
            
            [data-bs-theme="dark"] .has-submenu.active .submenu {
                background-color: rgba(67, 97, 238, 0.2);
            }
            
            .has-submenu .submenu .nav-link {
                padding-left: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .stat-card {
                padding: 15px;
            }
            
            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
                margin-right: 10px;
            }
            
            .stat-info h3 {
                font-size: 1.3rem;
            }
            
            .content-area {
                padding: 15px;
            }
            
            .top-nav {
                padding: 0 15px;
            }
            
            .btn-user-dropdown span,
            .btn-user-dropdown .bi-chevron-down {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
             <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="brand" aria-label="{{ setting('app_name') }}">
                    <span class="logo-icon">
                        <img src="{{ asset('public/logo/favicon.svg') }}" alt="{{ setting('app_name') }}">
                    </span>
                    <span class="logo-text">{{ setting('app_name') }}</span>
                </a>
                <button class="btn-close-sidebar" aria-label="Close sidebar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="sidebar-scrollable">
            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>                
                @canany(['view users', 'view roles', 'view permissions', 'view activity logs', 'view jobs', 'view backups'])
                <li class="nav-item has-submenu">
                    <a href="#" class="nav-link">
                        <i class="fas fa-user-shield"></i>
                        <span>Admin</span>
                    </a>
                    <ul class="submenu">
                        @can('view users')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">Users</a>
                        </li>
                        @endcan
                        @can('view roles')
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">Roles</a>
                        </li>
                        @endcan
                        @can('view permissions')
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.index') ? 'active' : '' }}">Permissions</a>
                        </li>
                        @endcan
                        @can('view activity logs')
                        <li class="nav-item">
                            <a href="{{ route('activity-logs.index') }}" class="nav-link {{ request()->routeIs('activity-logs.index') ? 'active' : '' }}">Activity Logs</a>
                        </li>
                        @endcan
                        @can('view jobs')
                        <li class="nav-item">
                            <a href="{{ route('jobs.index') }}" class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}">Job Queue</a>
                        </li>
                        @endcan
                        @can('view backups')
                        <li class="nav-item">
                            <a href="{{ route('backups.index') }}" class="nav-link {{ request()->routeIs('backups.index') ? 'active' : '' }}">Backups</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany
                
                <li class="nav-item">
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>
            
            <div class="sidebar-footer">
                <div class="theme-toggle">
                    <span>Dark Mode</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="themeToggle" aria-label="Toggle dark mode">
                    </div>
                </div>
                <div class="user-profile" tabindex="0" aria-label="User profile">
                    @auth
                    <div class="avatar">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                    </div>
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">
                            @foreach(Auth::user()->roles as $role)
                                {{ $role->name }}@if(!$loop->last), @endif
                            @endforeach
                        </span>
                    </div>
                    @endauth
                </div>
            </div>
        </aside>
        
        <!-- Overlay for mobile sidebar -->
        <div class="overlay" tabindex="-1" aria-hidden="true"></div>
        
        <!-- Main Content -->
        <main class="main-content" id="main-content">
            <!-- Top Navigation -->
            <nav class="top-nav" aria-label="Top navigation">
                <div class="nav-left">
                    <button class="btn-toggle-sidebar" aria-label="Toggle sidebar">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="search-box">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input type="text" placeholder="Search..." aria-label="Search">
                    </div>
                </div>
                <div class="nav-right">
                    <button class="btn-notification" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                        <i class="fas fa-bell" aria-hidden="true"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge badge-pulse bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end dropdown-notifications animate-fade-in" aria-labelledby="notificationsDropdown">
                        <div class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2">
                            <h6 class="mb-0">Notifications</h6>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link p-0 text-primary">Mark all as read</button>
                                </form>
                            @endif
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="notifications-list">
                            @forelse(auth()->user()->notifications->take(5) as $notification)
                                <a href="{{ $this->getNotificationUrl($notification) }}" 
                                   class="dropdown-item notification-item {{ $notification->read_at ? '' : 'unread' }}"
                                   aria-label="Notification: {{ $notification->data['message'] }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <p class="mb-1 fw-medium notification-title">{{ $notification->data['message'] }}</p>
                                        <small class="notification-time">{{ $notification->created_at->shortRelativeDiffForHumans() }}</small>
                                    </div>
                                    <small class="text-muted">Role: {{ ucfirst($notification->data['role']) }}</small>
                                </a>
                            @empty
                                <div class="dropdown-item text-center py-3 text-muted">
                                    No notifications found
                                </div>
                            @endforelse
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-primary fw-medium">
                            View all notifications
                        </a>
                    </div>
                    
                    <div class="dropdown">
                        <button class="btn-user-dropdown" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User menu">
                            <div class="user-avatar">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                            </div>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down ms-1 d-none d-md-inline"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end animate-fade-in" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="fas fa-user me-2" aria-hidden="true"></i> Profile</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('settings.index') }}">
                                <i class="fas fa-cog me-2" aria-hidden="true"></i> Settings</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @if(session('impersonate'))
                            <li>
                                <form action="{{ route('impersonate.stop') }}" method="POST" class="dropdown-item p-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning w-100">
                                        <i class="fas fa-arrow-left-circle me-1" aria-hidden="true"></i>Return to your account
                                    </button>
                                </form>
                            </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2" aria-hidden="true"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <!-- Content Area -->
            <div class="content-area">
                @include('layouts.partials.alerts')
                @yield('content')
            </div>
            
            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0">© {{ date('Y') }} {{ setting('app_name') }}. All rights reserved.</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-0">v{{ config('app.version') }}</p>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
    <!-- Custom JS -->
    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('themeToggle');
        const htmlElement = document.documentElement;
        
        // Check for saved user preference, if any, on load
        const currentTheme = localStorage.getItem('theme') || 'light';
        htmlElement.setAttribute('data-bs-theme', currentTheme);
        
        if (currentTheme === 'dark') {
            themeToggle.checked = true;
        }
        
        // Listen for toggle changes
        themeToggle.addEventListener('change', function() {
            if (this.checked) {
                htmlElement.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            } else {
                htmlElement.setAttribute('data-bs-theme', 'light');
                localStorage.setItem('theme', 'light');
            }
        });
        
        // Sidebar toggle functionality
        // Sidebar toggle functionality
        const sidebarToggle = document.querySelector('.btn-toggle-sidebar');
        const closeSidebar = document.querySelector('.btn-close-sidebar');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.querySelector('.overlay');
        
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            overlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
        });
        
        closeSidebar.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.style.display = 'none';
        });
        
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.style.display = 'none';
        });
        
        // Submenu toggle functionality
        document.querySelectorAll('.nav-item.has-submenu > a').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                parent.classList.toggle('active');
            });
        });
        
        // Close submenus when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.has-submenu')) {
                document.querySelectorAll('.has-submenu').forEach(item => {
                    item.classList.remove('active');
                });
            }
        });

        // Focus management for submenu items
        document.querySelectorAll('.has-submenu .submenu a').forEach(link => {
            link.addEventListener('keydown', function(e) {
                const submenu = this.closest('.submenu');
                const items = Array.from(submenu.querySelectorAll('a'));
                const currentIndex = items.indexOf(this);
                
                // Arrow up moves to previous item or button
                if (e.key === 'ArrowUp' && currentIndex === 0) {
                    e.preventDefault();
                    this.closest('.has-submenu').querySelector('button').focus();
                }
                
                // Arrow down moves to next item
                if (e.key === 'ArrowDown' && currentIndex < items.length - 1) {
                    e.preventDefault();
                    items[currentIndex + 1].focus();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>