<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('app_name') }} | {{ $title ?? 'Dashboard' }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @stack('styles')
    <style>
        :root {
            --primary-color: #4361ee;    /* Vibrant Blue */
            --primary-light: #e0e7ff;
            --secondary-color: #3f37c9;  /* Deep Blue */
            --accent-color: #f72585;     /* Pink */
            --success-color: #4cc9f0;    /* Teal */
            --warning-color: #f8961e;    /* Orange */
            --danger-color: #ef233c;     /* Red */
            --dark-color: #1a1a2e;       /* Dark Blue */
            --light-color: #f8f9fa;      /* Light Gray */
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --header-height: 70px;
            --transition-speed: 0.3s;
            --border-radius: 12px;
            --box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --text-primary: #1a1a2e;
            --text-secondary: #4b5563;
        }
        
        [data-bs-theme="dark"] {
            --primary-color: #4895ef;
            --primary-light: #1a1a2e;
            --secondary-color: #560bad;
            --accent-color: #f72585;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #ef233c;
            --dark-color: #0f172a;
            --light-color: #1e293b;
            --text-primary: #f8f9fa;
            --text-secondary: #e2e8f0;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--light-color);
            color: var(--text-primary);
            overflow-x: hidden;
            line-height: 1.6;
        }
        
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Glass Morphism Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--box-shadow);
        }
        
        [data-bs-theme="dark"] .glass-effect {
            background: rgba(26, 26, 46, 0.7);
        }
        
        /* Sidebar Styles - Modern Glass Design */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
            color: white;
            transition: all var(--transition-speed) ease;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            border-right: none;
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar-header {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            height: var(--header-height);
        }
        
        .brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.3rem;
            color: white;
            text-decoration: none;
            letter-spacing: 0.5px;
        }
        
        .logo-icon {
            font-size: 1.8rem;
            margin-right: 12px;
            color: white;
            transition: all var(--transition-speed) ease;
        }
        
        .sidebar.collapsed .logo-text {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
            position: absolute;
        }
        
        .btn-close-sidebar {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.3rem;
            cursor: pointer;
            display: none;
            transition: all 0.2s;
        }
        
        .btn-close-sidebar:hover {
            transform: rotate(90deg);
            color: white;
        }
        
        .sidebar-nav {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }
        
        .nav-item {
            position: relative;
            margin: 5px 15px;
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.2s;
            border-radius: var(--border-radius);
            font-weight: 500;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }
        
        .nav-link i {
            font-size: 1.2rem;
            margin-right: 12px;
            width: 24px;
            text-align: center;
            color: white;
            transition: all var(--transition-speed) ease;
        }
        
        .sidebar.collapsed .nav-link span {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
            position: absolute;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.4rem;
        }
        
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 15px 0;
        }
        
        .sidebar.collapsed .nav-link:hover {
            transform: scale(1.1);
        }
        
        /* Submenu Styles */
        .has-submenu .submenu {
            list-style: none;
            padding-left: 0;
            background-color: rgba(0, 0, 0, 0.15);
            display: none;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }
        
        .has-submenu.active .submenu {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .has-submenu .submenu .nav-link {
            padding-left: 50px;
            font-size: 0.95rem;
            font-weight: 400;
        }
        
        .sidebar.collapsed .has-submenu .submenu {
            position: absolute;
            left: calc(100% + 10px);
            top: 0;
            width: 220px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: var(--box-shadow);
            z-index: 1000;
            border-radius: var(--border-radius);
            padding: 10px 0;
        }
        
        .sidebar.collapsed .has-submenu .submenu .nav-link {
            padding-left: 15px;
        }
        
        .sidebar.collapsed .has-submenu .submenu .nav-link:hover {
            transform: translateX(0) translateY(-2px);
        }
        
        .has-submenu > .nav-link::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            transition: transform 0.3s;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
        }
        
        .has-submenu.active > .nav-link::after {
            transform: rotate(180deg);
        }
        
        .sidebar.collapsed .has-submenu > .nav-link::after {
            display: none;
        }
        
        /* Sidebar Footer */
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .theme-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .sidebar.collapsed .theme-toggle span {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
            position: absolute;
        }
        
        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.4em;
            background-color: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.3);
            cursor: pointer;
        }
        
        .form-switch .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            transition: all var(--transition-speed) ease;
            padding: 10px;
            border-radius: var(--border-radius);
            cursor: pointer;
        }
        
        .user-profile:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 12px;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .user-profile:hover .avatar {
            transform: scale(1.1);
            border-color: white;
        }
        
        .user-info {
            line-height: 1.3;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: white;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .sidebar.collapsed .user-info {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
            position: absolute;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin var(--transition-speed) ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        /* Top Navigation - Glass Design */
        .top-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 0 25px;
            height: var(--header-height);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        [data-bs-theme="dark"] .top-nav {
            background: rgba(26, 26, 46, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .nav-left, .nav-right {
            display: flex;
            align-items: center;
            height: 100%;
        }
        
        .btn-toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.4rem;
            color: var(--text-primary);
            margin-right: 15px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }
        
        .btn-toggle-sidebar:hover {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            transform: rotate(90deg);
        }
        
        .search-box {
            position: relative;
            margin-right: 15px;
        }
        
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1rem;
        }
        
        .search-box input {
            padding: 10px 15px 10px 40px;
            border-radius: 30px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            width: 200px;
            transition: all 0.3s;
            background-color: rgba(255, 255, 255, 0.7);
            color: var(--text-primary);
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        [data-bs-theme="dark"] .search-box input {
            background-color: rgba(26, 26, 46, 0.7);
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .search-box input:focus {
            width: 250px;
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            background-color: white;
        }
        
        [data-bs-theme="dark"] .search-box input:focus {
            background-color: var(--dark-color);
        }
        
        .nav-right-item {
            position: relative;
            margin-left: 15px;
            display: flex;
            align-items: center;
            height: 100%;
        }
        
        .btn-notification, .btn-message {
            background: none;
            border: none;
            font-size: 1.3rem;
            color: var(--text-primary);
            position: relative;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }
        
        .btn-notification:hover, .btn-message:hover {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }
        
        .badge {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 0.6rem;
            padding: 3px 6px;
            font-weight: 700;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 2px solid white;
        }
        
        [data-bs-theme="dark"] .badge {
            border-color: var(--dark-color);
        }
        
        .btn-user-dropdown {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            color: var(--text-primary);
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 30px;
            transition: all 0.2s;
            height: 40px;
        }
        
        .btn-user-dropdown:hover {
            background-color: rgba(67, 97, 238, 0.1);
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-size: 1rem;
            overflow: hidden;
            border: 2px solid rgba(67, 97, 238, 0.3);
        }
        
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .btn-user-dropdown .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            margin-right: 5px;
        }
        
        /* Content Area */
        .content-area {
            padding: 30px;
            flex: 1;
            background-color: var(--light-color);
        }
        
        .page-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .page-header h1 {
            font-weight: 700;
            color: var(--text-primary);
            font-size: 1.8rem;
            margin: 0;
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .breadcrumb-item a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        .breadcrumb-item.active {
            color: var(--text-secondary);
        }
        
        /* Stat Cards - Modern Glass Cards */
        .stat-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            display: flex;
            align-items: center;
            height: 100%;
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        [data-bs-theme="dark"] .stat-card {
            background: rgba(26, 26, 46, 0.7);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 20px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
        }
        
        .stat-card.bg-primary::before {
            background: linear-gradient(to bottom, var(--primary-color), #3a56e8);
        }
        
        .stat-card.bg-secondary::before {
            background: linear-gradient(to bottom, var(--secondary-color), #2b2d99);
        }
        
        .stat-card.bg-accent::before {
            background: linear-gradient(to bottom, var(--accent-color), #d91a6a);
        }
        
        .stat-card.bg-success::before {
            background: linear-gradient(to bottom, var(--success-color), #3aa8d1);
        }
        
        .stat-card.bg-warning::before {
            background: linear-gradient(to bottom, var(--warning-color), #e0871a);
        }
        
        .stat-card.bg-danger::before {
            background: linear-gradient(to bottom, var(--danger-color), #d61f36);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-right: 15px;
            flex-shrink: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon.bg-primary {
            background: linear-gradient(135deg, var(--primary-color), #3a56e8);
        }
        
        .stat-icon.bg-secondary {
            background: linear-gradient(135deg, var(--secondary-color), #2b2d99);
        }
        
        .stat-icon.bg-accent {
            background: linear-gradient(135deg, var(--accent-color), #d91a6a);
        }
        
        .stat-icon.bg-success {
            background: linear-gradient(135deg, var(--success-color), #3aa8d1);
        }
        
        .stat-icon.bg-warning {
            background: linear-gradient(135deg, var(--warning-color), #e0871a);
        }
        
        .stat-icon.bg-danger {
            background: linear-gradient(135deg, var(--danger-color), #d61f36);
        }
        
        .stat-info {
            flex: 1;
        }
        
        .stat-info h3 {
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--text-primary);
            font-size: 1.5rem;
        }
        
        .stat-info p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .stat-growth {
            margin-left: 15px;
            font-size: 0.9rem;
            font-weight: 700;
            display: flex;
            align-items: center;
        }
        
        .stat-growth i {
            margin-right: 5px;
            font-size: 1rem;
        }
        
        .stat-growth.success {
            color: var(--success-color);
        }
        
        .stat-growth.danger {
            color: var(--danger-color);
        }
        
        /* Card Styles - Glass Cards */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        [data-bs-theme="dark"] .card {
            background: rgba(26, 26, 46, 0.7);
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .card-header {
            background: rgba(255, 255, 255, 0.5);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 18px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
        }
        
        [data-bs-theme="dark"] .card-header {
            background: rgba(26, 26, 46, 0.5);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .card-header h5 {
            font-weight: 700;
            margin: 0;
            color: var(--text-primary);
            font-size: 1.2rem;
        }
        
        .card-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-body {
            padding: 25px;
        }
        
        /* Buttons - Modern Gradient Buttons */
        .btn {
            border-radius: 30px;
            padding: 8px 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-sm {
            padding: 6px 15px;
            font-size: 0.8rem;
        }
        
        .btn-lg {
            padding: 12px 30px;
            font-size: 1.1rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: 0 4px 6px rgba(67, 97, 238, 0.2);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #3a56e8, #2b2d99);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(67, 97, 238, 0.3);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .btn-accent {
            background: linear-gradient(135deg, var(--accent-color), #d91a6a);
            color: white;
            box-shadow: 0 4px 6px rgba(247, 37, 133, 0.2);
        }
        
        .btn-accent:hover {
            background: linear-gradient(135deg, #e6167a, #c2185b);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(247, 37, 133, 0.3);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #3aa8d1);
            color: white;
            box-shadow: 0 4px 6px rgba(76, 201, 240, 0.2);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #d61f36);
            color: white;
            box-shadow: 0 4px 6px rgba(239, 35, 60, 0.2);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color), #e0871a);
            color: white;
            box-shadow: 0 4px 6px rgba(248, 150, 30, 0.2);
        }
        
        /* Activity List */
        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .activity-list li {
            padding: 12px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            transition: all 0.2s;
        }
        
        [data-bs-theme="dark"] .activity-list li {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .activity-list li:last-child {
            border-bottom: none;
        }
        
        .activity-list li:hover {
            transform: translateX(5px);
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 1rem;
            flex-shrink: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .activity-icon.primary {
            background: linear-gradient(135deg, var(--primary-color), #3a56e8);
        }
        
        .activity-icon.secondary {
            background: linear-gradient(135deg, var(--secondary-color), #2b2d99);
        }
        
        .activity-icon.accent {
            background: linear-gradient(135deg, var(--accent-color), #d91a6a);
        }
        
        .activity-icon.success {
            background: linear-gradient(135deg, var(--success-color), #3aa8d1);
        }
        
        .activity-icon.warning {
            background: linear-gradient(135deg, var(--warning-color), #e0871a);
        }
        
        .activity-icon.danger {
            background: linear-gradient(135deg, var(--danger-color), #d61f36);
        }
        
        .activity-details {
            flex: 1;
        }
        
        .activity-details p {
            margin: 0;
            font-size: 0.95rem;
            color: var(--text-primary);
            font-weight: 500;
        }
        
        .activity-details span {
            font-size: 0.8rem;
            color: var(--text-secondary);
            display: block;
            margin-top: 5px;
        }
        
        /* Table Styles - Modern with hover effects */
        .table {
            margin-bottom: 0;
            color: var(--text-primary);
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .table th {
            font-weight: 700;
            color: var(--text-secondary);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: none;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 12px 15px;
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        [data-bs-theme="dark"] .table th {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background-color: rgba(67, 97, 238, 0.1);
        }
        
        .table td {
            vertical-align: middle;
            color: var(--text-primary);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 12px 15px;
        }
        
        [data-bs-theme="dark"] .table td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .table-hover tbody tr {
            transition: all 0.2s;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.1);
            transform: translateX(5px);
        }
        
        .avatar-sm {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            color: var(--primary-color);
            overflow: hidden;
        }
        
        .avatar-sm img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Badges - Modern with subtle shadows */
        .badge {
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .badge.bg-primary {
            background: linear-gradient(135deg, var(--primary-color), #3a56e8) !important;
        }
        
        .badge.bg-secondary {
            background: linear-gradient(135deg, var(--secondary-color), #2b2d99) !important;
        }
        
        .badge.bg-accent {
            background: linear-gradient(135deg, var(--accent-color), #d91a6a) !important;
        }
        
        .badge.bg-success {
            background: linear-gradient(135deg, var(--success-color), #3aa8d1) !important;
        }
        
        .badge.bg-warning {
            background: linear-gradient(135deg, var(--warning-color), #e0871a) !important;
        }
        
        .badge.bg-danger {
            background: linear-gradient(135deg, var(--danger-color), #d61f36) !important;
        }
        
        /* Events Timeline */
        .events-timeline {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .event-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }
        
        [data-bs-theme="dark"] .event-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .event-item:last-child {
            border-bottom: none;
        }
        
        .event-item:hover {
            transform: translateX(5px);
        }
        
        .event-date {
            width: 60px;
            height: 60px;
            border-radius: var(--border-radius);
            background-color: rgba(67, 97, 238, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
            transition: all 0.3s;
        }
        
        .event-item:hover .event-date {
            background-color: rgba(67, 97, 238, 0.2);
            transform: scale(1.05);
        }
        
        .event-date .day {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--primary-color);
            line-height: 1;
        }
        
        .event-date .month {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-top: 2px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .event-details {
            flex: 1;
        }
        
        .event-details h6 {
            font-weight: 700;
            margin: 0 0 5px 0;
            color: var(--text-primary);
            font-size: 1rem;
        }
        
        .event-details p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .event-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        
        /* Footer */
        .footer {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 15px 30px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        
        [data-bs-theme="dark"] .footer {
            background: rgba(26, 26, 46, 0.7);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        /* Notification Dropdown - Modern Glass Design */
        .dropdown-notifications {
            width: 350px;
            padding: 0;
            border: none;
            border-radius: var(--border-radius);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: var(--box-shadow);
            transform-origin: top right;
            animation: scaleIn 0.2s ease-out;
        }
        
        [data-bs-theme="dark"] .dropdown-notifications {
            background: rgba(26, 26, 46, 0.9);
        }
        
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .dropdown-header {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.5);
        }
        
        [data-bs-theme="dark"] .dropdown-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(26, 26, 46, 0.5);
        }
        
        .notification-item {
            padding: 12px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: block;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.2s;
        }
        
        [data-bs-theme="dark"] .notification-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .notification-item.unread {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        [data-bs-theme="dark"] .notification-item.unread {
            background-color: rgba(67, 97, 238, 0.1);
        }
        
        .notification-item:hover {
            background-color: rgba(67, 97, 238, 0.1);
            transform: translateX(3px);
        }
        
        .notification-item .d-flex {
            align-items: flex-start;
        }
        
        .notification-item p {
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .notification-item small {
            color: var(--text-secondary);
            font-size: 0.8rem;
        }
        
        /* Dropdown Menu - Modern Glass Design */
        .dropdown-menu {
            border: none;
            border-radius: var(--border-radius);
            padding: 8px 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: var(--box-shadow);
            transform-origin: top right;
            animation: scaleIn 0.2s ease-out;
        }
        
        [data-bs-theme="dark"] .dropdown-menu {
            background: rgba(26, 26, 46, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .dropdown-item {
            padding: 8px 16px;
            color: var(--text-primary);
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }
        
        .dropdown-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            color: var(--primary-color);
        }
        
        .dropdown-item:hover {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            transform: translateX(3px);
        }
        
        .dropdown-divider {
            border-color: rgba(0, 0, 0, 0.05);
            margin: 5px 0;
        }
        
        [data-bs-theme="dark"] .dropdown-divider {
            border-color: rgba(255, 255, 255, 0.05);
        }
        
        /* Alerts - Modern with icons */
        .alert {
            border-radius: var(--border-radius);
            border: none;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            box-shadow: var(--box-shadow);
        }
        
        .alert i {
            font-size: 1.5rem;
            margin-right: 15px;
        }
        
        .alert-primary {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }
        
        .alert-primary i {
            color: var(--primary-color);
        }
        
        .alert-success {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--success-color);
        }
        
        .alert-success i {
            color: var(--success-color);
        }
        
        .alert-danger {
            background-color: rgba(239, 35, 60, 0.1);
            color: var(--danger-color);
        }
        
        .alert-danger i {
            color: var(--danger-color);
        }
        
        .alert-warning {
            background-color: rgba(248, 150, 30, 0.1);
            color: var(--warning-color);
        }
        
        .alert-warning i {
            color: var(--warning-color);
        }
        
        /* Badge Pulse Animation */
        .badge-pulse {
            position: relative;
            animation: pulse 1.5s infinite;
        }
        
        .badge-pulse::after {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border-radius: 50%;
            border: 1px solid var(--accent-color);
            animation: pulse-ring 1.5s infinite;
            opacity: 0;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.7; }
            70% { transform: scale(1.2); opacity: 0; }
            100% { transform: scale(1.2); opacity: 0; }
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(67, 97, 238, 0.5);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
        
        [data-bs-theme="dark"] ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1050;
                box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
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
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
            }
            
            .sidebar.show ~ .overlay {
                display: block;
            }
            
            .search-box input {
                width: 150px;
            }
            
            .search-box input:focus {
                width: 200px;
            }
        }
        
        @media (max-width: 768px) {
            .content-area {
                padding: 20px;
            }
            
            .page-header h1 {
                font-size: 1.5rem;
            }
            
            .stat-info h3 {
                font-size: 1.3rem;
            }
            
            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
            
            .dropdown-notifications {
                width: 300px;
            }
            
            .nav-right-item {
                margin-left: 10px;
            }
            
            .btn-user-dropdown .user-name {
                display: none;
            }
            
            .btn-user-dropdown .bi-chevron-down {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .top-nav {
                padding: 0 15px;
            }
            
            .content-area {
                padding: 15px;
            }
            
            .search-box {
                display: none;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-icon {
                margin-right: 10px;
            }
            
            .dropdown-notifications {
                width: 280px;
                right: -50px !important;
            }
        }
        
        /* Animations */
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-scale-in {
            animation: scaleIn 0.2s ease-out;
        }
        
        /* Utility Classes */
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .text-accent {
            color: var(--accent-color) !important;
        }
        
        .text-success {
            color: var(--success-color) !important;
        }
        
        .text-warning {
            color: var(--warning-color) !important;
        }
        
        .text-danger {
            color: var(--danger-color) !important;
        }
        
        .bg-primary-light {
            background-color: var(--primary-light) !important;
        }
        
        /* Custom Switch */
        .form-switch .form-check-input {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        }
        
        [data-bs-theme="dark"] .form-switch .form-check-input {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%231a1a2e'/%3e%3c/svg%3e");
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="brand">
                    <span class="logo-icon">
                        <img src="{{ asset('public/logo/brand.svg') }}" alt="{{ setting('app_name') }}" height="40">
                    </span>
                    <!-- <span class="logo-text">{{ setting('app_name') }}</span> -->
                </a>
                <button class="btn-close-sidebar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
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
            
            <div class="sidebar-footer">
                <div class="theme-toggle">
                    <span>Dark Mode</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="themeToggle">
                    </div>
                </div>
                <div class="user-profile">
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
        <div class="overlay"></div>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Navigation -->
            <nav class="top-nav">
                <div class="nav-left">
                    <button class="btn-toggle-sidebar">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search...">
                    </div>
                </div>
                <div class="nav-right">
                    <div class="nav-right-item">
                        <button class="btn-notification" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge badge-pulse bg-accent">{{ auth()->user()->unreadNotifications->count() }}</span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-end dropdown-notifications animate-scale-in" aria-labelledby="notificationsDropdown">
                            <div class="dropdown-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Notifications</h6>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-link p-0 text-primary">Mark all as read</button>
                                    </form>
                                @endif
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="notifications-list" style="max-height: 300px; overflow-y: auto;">
                                @forelse(auth()->user()->notifications->take(5) as $notification)
                                    <a href="{{ $this->getNotificationUrl($notification) }}" 
                                       class="dropdown-item notification-item {{ $notification->read_at ? '' : 'unread' }}">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-sm bg-primary-light text-primary rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-bell"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="mb-1 fw-medium">{{ $notification->data['message'] }}</p>
                                                <small class="text-muted">{{ $notification->created_at->shortRelativeDiffForHumans() }}</small>
                                            </div>
                                        </div>
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
                    </div>
                    
                    <div class="nav-right-item">
                        <div class="dropdown">
                            <button class="btn-user-dropdown" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar">
                                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                                </div>
                                <span class="user-name">{{ Auth::user()->name }}</span>
                                <i class="bi bi-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end animate-scale-in" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user me-2"></i> Profile</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('settings.index') }}">
                                    <i class="fas fa-cog me-2"></i> Settings</a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                @if(session('impersonate'))
                                <li>
                                    <form action="{{ route('impersonate.stop') }}" method="POST" class="dropdown-item p-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning w-100">
                                            <i class="fas fa-arrow-left-circle me-1"></i>Return to your account
                                        </button>
                                    </form>
                                </li>
                                @endif
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
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
                            <p class="mb-0">v{{ config('app.version') }} | <span id="current-time"></span></p>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom JS -->
    <script>
         AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true
        });
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

        // Update current time in footer
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            document.getElementById('current-time').textContent = timeString;
        }
        
        setInterval(updateTime, 1000);
        updateTime();
        
        // Add animation to dropdowns
        document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
            dropdown.addEventListener('shown.bs.dropdown', function () {
                this.classList.add('animate-scale-in');
            });
        });
        
        // Smooth scroll for notifications
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (this.href) {
                    e.preventDefault();
                    document.querySelector('.dropdown-notifications').classList.remove('show');
                    setTimeout(() => {
                        window.location.href = this.href;
                    }, 300);
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>