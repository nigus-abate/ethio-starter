<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Manager - Premium File Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #f8fafc;
            --dark: #1e293b;
            --light: #f1f5f9;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark);
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 5rem 0;
        }
        
        .feature-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--primary);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .tech-specs td {
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
        }
        
        .tech-specs tr:last-child td {
            border-bottom: none;
        }
        
        .pricing-card {
            border: 2px solid var(--light);
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .pricing-card:hover {
            border-color: var(--primary);
        }
        
        .pricing-card .price {
            font-size: 2.5rem;
            color: var(--primary);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fas fa-folder-open text-primary me-2"></i>
                Document Manager
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#screenshots">Screenshots</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tech">Technical</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">Pricing</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary" href="#">Try Demo</a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        {{ __('Dashboard') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Premium File Management System</h1>
                    <p class="lead mb-4">A complete solution for managing, sharing, and organizing files with enterprise-grade security and intuitive interface.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-light btn-lg px-4">Get Started</a>
                        <a href="#" class="btn btn-outline-light btn-lg px-4">View Demo</a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="https://via.placeholder.com/600x400" alt="Dashboard Preview" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Powerful Features</h2>
                <p class="lead text-muted">Everything you need for modern document management</p>
            </div>
            
            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card p-4 text-center">
                        <div class="feature-icon">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <h5>Modern UI</h5>
                        <p class="text-muted">Clean, intuitive interface with dark/light mode and responsive design.</p>
                    </div>
                </div>
                
                <!-- Feature 2 -->
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card p-4 text-center">
                        <div class="feature-icon">
                            <i class="fas fa-folder-tree"></i>
                        </div>
                        <h5>File Management</h5>
                        <p class="text-muted">Organize with folders, preview files, and bulk actions.</p>
                    </div>
                </div>
                
                <!-- Feature 3 -->
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card p-4 text-center">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5>Security</h5>
                        <p class="text-muted">Encryption, 2FA, and role-based access control.</p>
                    </div>
                </div>
                
                <!-- Feature 4 -->
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card p-4 text-center">
                        <div class="feature-icon">
                            <i class="fas fa-cloud"></i>
                        </div>
                        <h5>Cloud Integration</h5>
                        <p class="text-muted">Connect with AWS S3, Google Drive, Dropbox and more.</p>
                    </div>
                </div>
            </div>
            
            <!-- More features as tabs -->
            <div class="mt-5">
                <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-sharing-tab" data-bs-toggle="pill" data-bs-target="#pills-sharing" type="button">Sharing & Collaboration</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-admin-tab" data-bs-toggle="pill" data-bs-target="#pills-admin" type="button">Admin Features</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-search-tab" data-bs-toggle="pill" data-bs-target="#pills-search" type="button">Search & Organization</button>
                    </li>
                </ul>
                <div class="tab-content bg-white p-4 rounded shadow-sm" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-sharing" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Share files & folders with specific users</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Set granular permissions (view/edit/download)</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Expiry dates for shared links</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Activity logs for all file changes</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Commenting and notifications</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Version history tracking</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-admin" role="tabpanel">
                        <!-- Admin features content -->
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Role based User management</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Permissions management</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Activity logs for all file changes</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> User Impersonation</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Automated Backup</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Jobs and Queue</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-search" role="tabpanel">
                        <!-- Search features content -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Screenshots Section -->
    <section id="screenshots" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Application Screenshots</h2>
                <p class="lead text-muted">See Document Manager in action</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <img src="https://via.placeholder.com/400x250" alt="Dashboard" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-4">
                    <img src="https://via.placeholder.com/400x250" alt="File Manager" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-4">
                    <img src="https://via.placeholder.com/400x250" alt="Sharing" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Technical Specifications -->
    <section id="tech" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Technical Specifications</h2>
                <p class="lead text-muted">Built with modern technologies</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <table class="table tech-specs bg-white rounded shadow-sm">
                        <tr>
                            <td><strong>Framework</strong></td>
                            <td>Laravel 12+</td>
                        </tr>
                        <tr>
                            <td><strong>Frontend</strong></td>
                            <td>Bootstrap 5, Vanilla JS</td>
                        </tr>
                        <tr>
                            <td><strong>Database</strong></td>
                            <td>MySQL, PostgreSQL, SQLite</td>
                        </tr>
                        <tr>
                            <td><strong>Storage</strong></td>
                            <td>Local, AWS S3, Google Drive, Dropbox, FTP</td>
                        </tr>
                        <tr>
                            <td><strong>Security</strong></td>
                            <td>Encryption, 2FA, RBAC</td>
                        </tr>
                        <tr>
                            <td><strong>Browser Support</strong></td>
                            <td>Chrome, Firefox, Safari, Edge</td>
                        </tr>
                        <tr>
                            <td><strong>API Support</strong></td>
                            <td>RESTful API for integration</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Simple Pricing</h2>
                <p class="lead text-muted">One-time purchase with no recurring fees</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="pricing-card p-4 text-center">
                        <h4>Standard License</h4>
                        <div class="price fw-bold">$149</div>
                        <p class="text-muted">For single website use</p>
                        <hr>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> All Features Included</li>
                            <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> 6 Months Support</li>
                            <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> Lifetime Updates</li>
                            <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> Documentation</li>
                        </ul>
                        <a href="#" class="btn btn-primary w-100">Purchase Now</a>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="pricing-card p-4 text-center">
                        <h4>Extended License</h4>
                        <div class="price fw-bold">$299</div>
                        <p class="text-muted">For SaaS/multi-website use</p>
                        <hr>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> All Standard Features</li>
                            <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> 12 Months Support</li>
                            <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> White-label Option</li>
                            <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> Priority Support</li>
                        </ul>
                        <a href="#" class="btn btn-primary w-100">Purchase Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3"><i class="fas fa-folder-open me-2"></i> Document Manager</h5>
                    <p>A complete solution for managing, sharing, and organizing files with enterprise-grade security.</p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="mb-3">Product</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50">Features</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50">Pricing</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50">Demo</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50">Updates</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="mb-3">Support</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50">Documentation</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50">Help Center</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50">Community</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">Newsletter</h5>
                    <p>Subscribe to get updates and special offers.</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your email">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">© 2023 Document Manager. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white-50 me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="fab fa-github"></i></a>
                    <a href="#" class="text-white-50"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>