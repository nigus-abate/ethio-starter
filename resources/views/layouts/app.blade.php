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
            --primary-color: #1E40AF;    /* Deep Blue */
            --secondary-color: #047857;  /* Dark Green */
            --background-color: #F3F4F6; /* Light Gray */
            --text-color: #111827;      /* Dark Gray / Almost Black */
            --accent-color: #FBBF24;    /* Amber / Gold */
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            overflow-x: hidden;
        }
        
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color), #1E3A8A);
            color: white;
            transition: all 0.3s;
            position: fixed;
            height: 100vh;
            z-index: 1000;
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
        }
        
        .brand {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 1.2rem;
            color: white;
            text-decoration: none;
        }
        
        .logo-icon {
            font-size: 1.5rem;
            margin-right: 10px;
            color: var(--accent-color);
        }
        
        .sidebar.collapsed .logo-text {
            display: none;
        }
        
        .btn-close-sidebar {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.2rem;
            cursor: pointer;
            display: none;
        }
        
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .nav-link i {
            font-size: 1.1rem;
            margin-right: 10px;
            width: 24px;
            text-align: center;
            color: var(--accent-color);
        }
        
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.3rem;
        }
        
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 15px 0;
        }
        
        /* Submenu Styles */
        .has-submenu .submenu {
            list-style: none;
            padding-left: 0;
            background-color: rgba(0, 0, 0, 0.2);
            display: none;
        }
        
        .has-submenu.active .submenu {
            display: block;
        }
        
        .has-submenu .submenu .nav-link {
            padding-left: 50px;
        }
        
        .sidebar.collapsed .has-submenu .submenu {
            position: absolute;
            left: 100%;
            top: 0;
            width: 200px;
            background-color: var(--primary-color);
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        
        .sidebar.collapsed .has-submenu .submenu .nav-link {
            padding-left: 20px;
        }
        
        .has-submenu > .nav-link::after {
            content: '\f054';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            transition: transform 0.3s;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .has-submenu.active > .nav-link::after {
            transform: rotate(90deg);
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
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .theme-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .sidebar.collapsed .theme-toggle span {
            display: none;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
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
            margin-right: 10px;
        }
        
        .user-info {
            line-height: 1.2;
        }
        
        .user-name {
            font-weight: 500;
            font-size: 0.9rem;
            color: white;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .sidebar.collapsed .user-info {
            display: none;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin 0.3s;
        }
        
        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        /* Top Navigation */
        .top-nav {
            background-color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .nav-left, .nav-right {
            display: flex;
            align-items: center;
        }
        
        .btn-toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--text-color);
            margin-right: 15px;
            cursor: pointer;
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6B7280;
        }
        
        .search-box input {
            padding: 8px 15px 8px 35px;
            border-radius: 20px;
            border: 1px solid #D1D5DB;
            width: 200px;
            transition: width 0.3s;
            background-color: white;
            color: var(--text-color);
        }
        
        .search-box input:focus {
            width: 250px;
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        
        .btn-notification, .btn-message {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--text-color);
            margin-right: 15px;
            position: relative;
            cursor: pointer;
        }
        
        .badge {
            background-color: var(--accent-color);
            color: var(--text-color);
        }
        
        .btn-user-dropdown {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            color: var(--text-color);
            cursor: pointer;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-size: 1rem;
        }
        
        /* Content Area */
        .content-area {
            padding: 25px;
            background-color: var(--background-color);
        }
        
        .page-header {
            margin-bottom: 25px;
        }
        
        .page-header h1 {
            font-weight: 600;
            color: var(--text-color);
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
            font-size: 0.85rem;
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        /* Stat Cards */
        .stat-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            height: 100%;
            border-left: 4px solid var(--primary-color);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-right: 15px;
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
            background-color: #F59E0B;
        }
        
        .stat-icon.bg-info {
            background-color: #3B82F6;
        }
        
        .stat-icon.bg-danger {
            background-color: #EF4444;
        }
        
        .stat-icon.bg-success {
            background-color: #10B981;
        }
        
        .stat-info h3 {
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--text-color);
        }
        
        .stat-info p {
            margin: 0;
            color: #6B7280;
            font-size: 0.85rem;
        }
        
        .stat-growth {
            margin-left: auto;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .stat-growth.success {
            color: var(--secondary-color);
        }
        
        .stat-growth.danger {
            color: #EF4444;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            background-color: white;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #E5E7EB;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px 8px 0 0 !important;
        }
        
        .card-header h5 {
            font-weight: 600;
            margin: 0;
            color: var(--text-color);
        }
        
        .card-actions {
            display: flex;
            align-items: center;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #1E3A8A;
            border-color: #1E3A8A;
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
            color: var(--text-color);
        }
        
        .btn-accent:hover {
            background-color: #F59E0B;
            border-color: #F59E0B;
            color: var(--text-color);
        }
        
        /* Activity List */
        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .activity-list li {
            padding: 10px 0;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
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
            background-color: #F59E0B;
        }
        
        .activity-icon.info {
            background-color: #3B82F6;
        }
        
        .activity-icon.danger {
            background-color: #EF4444;
        }
        
        .activity-details p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--text-color);
        }
        
        .activity-details span {
            font-size: 0.75rem;
            color: #6B7280;
        }
        
        /* Table Styles */
        .table {
            margin-bottom: 0;
            color: var(--text-color);
        }
        
        .table th {
            font-weight: 600;
            color: #6B7280;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: none;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .table td {
            vertical-align: middle;
            color: var(--text-color);
            border-bottom: 1px solid #E5E7EB;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(30, 64, 175, 0.05);
        }
        
        .avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            color: #6B7280;
        }
        
        /* Badges */
        .badge.bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .badge.bg-secondary {
            background-color: var(--secondary-color) !important;
        }
        
        .badge.bg-accent {
            background-color: var(--accent-color) !important;
            color: var(--text-color);
        }
        
        /* Events Timeline */
        .events-timeline {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .event-item {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .event-item:last-child {
            border-bottom: none;
        }
        
        .event-date {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background-color: #F9FAFB;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .event-date .day {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text-color);
        }
        
        .event-date .month {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: #6B7280;
            margin-top: -5px;
        }
        
        .event-details {
            flex: 1;
        }
        
        .event-details h6 {
            font-weight: 600;
            margin: 0 0 5px 0;
            color: var(--text-color);
        }
        
        .event-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
        }
        
        /* Footer */
        .footer {
            background-color: white;
            padding: 15px 25px;
            border-top: 1px solid #E5E7EB;
            font-size: 0.85rem;
            color: #6B7280;
        }
        
        /* Dark Mode */
        [data-bs-theme="dark"] {
            --background-color: #1F2937;
            --text-color: #F9FAFB;
        }
        
        [data-bs-theme="dark"] body {
            background-color: var(--background-color);
        }
        
        [data-bs-theme="dark"] .top-nav,
        [data-bs-theme="dark"] .card,
        [data-bs-theme="dark"] .stat-card,
        [data-bs-theme="dark"] .footer {
            background-color: #111827;
            color: var(--text-color);
        }
        
        [data-bs-theme="dark"] .card-header,
        [data-bs-theme="dark"] .table th {
            background-color: #111827;
            color: var(--text-color);
            border-color: #374151;
        }
        
        [data-bs-theme="dark"] .table td {
            color: var(--text-color);
            border-color: #374151;
        }
        
        [data-bs-theme="dark"] .table {
            background-color: #111827;
            color: var(--text-color);
        }
        
        [data-bs-theme="dark"] .page-header h1,
        [data-bs-theme="dark"] .stat-info h3,
        [data-bs-theme="dark"] .card-header h5,
        [data-bs-theme="dark"] .activity-details p,
        [data-bs-theme="dark"] .event-details h6,
        [data-bs-theme="dark"] .event-date .day {
            color: var(--text-color);
        }
        
        [data-bs-theme="dark"] .search-box input {
            background-color: #1F2937;
            border-color: #374151;
            color: var(--text-color);
        }
        
        [data-bs-theme="dark"] .btn-toggle-sidebar,
        [data-bs-theme="dark"] .btn-notification,
        [data-bs-theme="dark"] .btn-message,
        [data-bs-theme="dark"] .btn-user-dropdown {
            color: var(--text-color);
        }
        
        [data-bs-theme="dark"] .table-hover tbody tr:hover {
            background-color: rgba(30, 64, 175, 0.2);
        }
        
        [data-bs-theme="dark"] .stat-info p,
        [data-bs-theme="dark"] .activity-details span,
        [data-bs-theme="dark"] .event-date .month,
        [data-bs-theme="dark"] .breadcrumb-item.active {
            color: #9CA3AF;
        }
        
        /* Notification Dropdown */
        .dropdown-notifications {
            width: 350px;
            padding: 0;
        }
        
        .notification-item {
            padding: 10px 15px;
            border-bottom: 1px solid #E5E7EB;
            display: block;
            color: var(--text-color);
            text-decoration: none;
        }
        
        .notification-item.unread {
            background-color: #F9FAFB;
        }
        
        .notification-item:hover {
            background-color: #F3F4F6;
        }
        
        [data-bs-theme="dark"] .notification-item {
            color: var(--text-color);
            border-color: #374151;
        }
        
        [data-bs-theme="dark"] .notification-item.unread {
            background-color: #1F2937;
        }
        
        [data-bs-theme="dark"] .notification-item:hover {
            background-color: #374151;
        }
        
        /* Responsive */
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
        
        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 8px;
            padding: 8px 0;
        }
        
        .dropdown-item {
            padding: 8px 16px;
            color: var(--text-color);
        }
        
        .dropdown-item:hover {
            background-color: #F3F4F6;
            color: var(--text-color);
        }
        
        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #111827;
            border: 1px solid #374151;
        }
        
        [data-bs-theme="dark"] .dropdown-item {
            color: var(--text-color);
        }
        
        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: #374151;
        }
        
        /* Alerts */
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .alert-primary {
            background-color: rgba(30, 64, 175, 0.1);
            color: var(--primary-color);
        }
        
        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: #047857;
        }
        
        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #B91C1C;
        }
        
        .alert-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #B45309;
        }
        
        [data-bs-theme="dark"] .alert-primary {
            background-color: rgba(30, 64, 175, 0.2);
        }
        
        [data-bs-theme="dark"] .alert-success {
            background-color: rgba(16, 185, 129, 0.2);
        }
        
        [data-bs-theme="dark"] .alert-danger {
            background-color: rgba(239, 68, 68, 0.2);
        }
        
        [data-bs-theme="dark"] .alert-warning {
            background-color: rgba(245, 158, 11, 0.2);
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h3 class="brand">
                    <span class="logo-icon">
                        <img src="{{ asset('public/logo/brand.svg') }}" alt="{{ setting('app_name') }}" height="40">
                    </span>
                    <!-- <span class="logo-text">{{ setting('app_name') }}</span> -->
                </h3>
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
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
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
                    <button class="btn-notification" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge badge-pulse">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end dropdown-notifications animate-fade-in" aria-labelledby="notificationsDropdown">
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
                        <div class="notifications-list">
                            @forelse(auth()->user()->notifications->take(5) as $notification)
                                <a href="{{ $this->getNotificationUrl($notification) }}" 
                                   class="dropdown-item notification-item {{ $notification->read_at ? '' : 'unread' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <p class="mb-1 fw-medium">{{ $notification->data['message'] }}</p>
                                        <small class="text-muted">{{ $notification->created_at->shortRelativeDiffForHumans() }}</small>
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
                        <button class="btn-user-dropdown" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            </div>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end animate-fade-in" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="fas fa-user me-2 text-primary"></i> Profile</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('settings.index') }}">
                                <i class="fas fa-cog me-2 text-primary"></i> Settings</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @if(session('impersonate'))
                            <li>
                                <form action="{{ route('impersonate.stop') }}" method="POST" class="dropdown-item">
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
                                    <i class="fas fa-sign-out-alt me-2 text-primary"></i> Logout
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
    </script>
    @stack('scripts')
</body>
</html>