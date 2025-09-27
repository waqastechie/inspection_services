<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Management') - Inspection Services</title>
    
    <!-- Meta Tags -->
    <meta name="description" content="User management system for inspection services">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Font Awesome 6.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/validation.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1e40af;
            --secondary-color: #64748b;
            --accent-color: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #0ea5e9;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --border-radius: 8px;
            --border-radius-lg: 12px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--light-bg);
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Navigation Styles */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            box-shadow: var(--shadow-md);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.2s ease;
        }

        .navbar-nav .nav-link:hover {
            color: white !important;
            transform: translateY(-1px);
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow-lg);
            border-radius: var(--border-radius);
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 140px);
            padding: 2rem 0;
        }

        /* Card Styles */
        .card {
            border: none;
            box-shadow: var(--shadow-md);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--light-bg) 0%, #e2e8f0 100%);
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
        }

        /* Button Styles */
        .btn {
            font-weight: 600;
            border-radius: var(--border-radius);
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info-color) 0%, #0284c7 100%);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #475569 100%);
        }

        /* Form Styles */
        .form-control, .form-select {
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Table Styles */
        .table {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table thead th {
            background: linear-gradient(135deg, var(--light-bg) 0%, #e2e8f0 100%);
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
        }

        /* Badge Styles */
        .badge {
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius);
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            border-bottom: none;
        }

        /* Footer */
        .footer {
            background: var(--text-primary);
            color: white;
            padding: 1.5rem 0;
            margin-top: auto;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem 0;
            }
            
            .navbar-brand {
                font-size: 1.25rem;
            }
        }

        /* Animation utilities */
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    @stack('styles')
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-cogs me-1 fa-sm"></i>
                Inspection Services
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inspections.index') }}">
                            <i class="fas fa-clipboard-list me-1"></i> Inspections
                        </a>
                    </li>
                    @if(auth()->user()->canApproveInspections())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="qaDropdown" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-shield-check me-1"></i> QA Review
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="qaDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('qa.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> QA Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('qa.pending') }}">
                                        <i class="fas fa-clock me-2"></i> Pending Review
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('qa.under-review') }}">
                                        <i class="fas fa-eye me-2"></i> Under Review
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('qa.history') }}">
                                        <i class="fas fa-history me-2"></i> Review History
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">
                                <i class="fas fa-users me-1"></i> Users
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->isSuperAdmin())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog me-1"></i> Admin
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                <li><h6 class="dropdown-header">Resource Management</h6></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.clients.index') }}">
                                        <i class="fas fa-building me-2"></i> Clients
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.personnel.index') }}">
                                        <i class="fas fa-user-tie me-2"></i> Personnel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.equipment.index') }}">
                                        <i class="fas fa-tools me-2"></i> Equipment
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.consumables.index') }}">
                                        <i class="fas fa-box me-2"></i> Consumables
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">System Monitoring</h6></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.logs.dashboard') }}">
                                        <i class="fas fa-chart-line me-2"></i> Log Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.logs.system') }}">
                                        <i class="fas fa-exclamation-triangle me-2"></i> System Logs
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.logs.activity') }}">
                                        <i class="fas fa-user-clock me-2"></i> Activity Logs
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i>
                            {{ auth()->user()->name }}
                            <span class="badge bg-light text-dark ms-2">{{ ucfirst(auth()->user()->role) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user-circle me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cog me-2"></i> Settings
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid fade-in">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} Inspection Services. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        Powered by Laravel {{ app()->version() }} | 
                        <i class="fas fa-shield-alt"></i> Secure System
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

    <!-- Alpine.js for reactive validation -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Add fade-in animation to main content
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                mainContent.classList.add('fade-in');
            }
        });
    </script>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
