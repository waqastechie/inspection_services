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
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/auto-save.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/equipment.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/pagination.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/modern-dashboard.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/modern-sidebar.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/tablet-responsive.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/validation.css') }}?v={{ time() }}" rel="stylesheet">
    
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
            color: var(--text-primary) !important; /* ensure dark text on grey headers */
        }

        /* Accessibility contrast: prevent white text inside light/grey headers */
        .card-header .text-white,
        .bg-light .text-white,
        .card.bg-light .text-white {
            color: var(--text-primary) !important;
        }

        .card-header h1,
        .card-header h2,
        .card-header h3,
        .card-header h4,
        .card-header h5,
        .card-header h6 {
            color: var(--text-primary) !important;
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
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="sidebar-brand">
                    <i class="fas fa-cogs"></i>
                    <span class="sidebar-brand-text">Inspection Services</span>
                </a>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="sidebar-nav">
                <!-- Main Navigation -->
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('inspections.index') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <span class="nav-text">Inspections</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('notifications.index') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <span class="nav-text">Notifications</span>
                            @php
                                $unreadCount = auth()->user()->unreadNotifications()->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="nav-badge">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                <!-- Quick Actions Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Quick Actions</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="nav-text">Manage Users</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('users.create') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <span class="nav-text">Add New User</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('inspections.index') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fas fa-list"></i>
                            </div>
                            <span class="nav-text">View All Inspections</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('inspections.create') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <span class="nav-text">New Inspection</span>
                        </a>
                    </div>
                    
                    @if(Auth::user()->canApproveInspections())
                    <div class="nav-item">
                        <a href="{{ route('qa.pending') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <span class="nav-text">QA Review Queue</span>
                        </a>
                    </div>
                    @endif
                    
                    @if(Auth::user()->isSuperAdmin())
                    <div class="nav-item">
                        <a href="{{ route('admin.clients.index') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <span class="nav-text">Manage Clients</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.personnel.index') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <span class="nav-text">Manage Personnel</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.equipment.index') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <span class="nav-text">Manage Equipment</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.consumables.index') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fas fa-flask"></i>
                            </div>
                            <span class="nav-text">Manage Consumables</span>
                        </a>
                    </div>
                    @endif
                </div>

                <!-- QA Review Section -->
                @if(auth()->user()->canApproveInspections())
                <div class="nav-section">
                    <div class="nav-section-title">Quality Assurance</div>
                    
                    <div class="nav-item nav-dropdown">
                        <a href="#" class="nav-link nav-dropdown-toggle">
                            <div class="nav-icon">
                                <i class="fas fa-shield-check"></i>
                            </div>
                            <span class="nav-text">QA Review</span>
                            <i class="fas fa-chevron-down nav-dropdown-icon"></i>
                        </a>
                        <div class="nav-dropdown-menu">
                            <a href="{{ route('qa.dashboard') }}" class="nav-link nav-dropdown-item">
                                <i class="fas fa-tachometer-alt me-2"></i> QA Dashboard
                            </a>
                            <a href="{{ route('qa.pending') }}" class="nav-link nav-dropdown-item">
                                <i class="fas fa-clock me-2"></i> Pending Review
                            </a>
                            <a href="{{ route('qa.under-review') }}" class="nav-link nav-dropdown-item">
                                <i class="fas fa-eye me-2"></i> Under Review
                            </a>
                            <a href="{{ route('qa.history') }}" class="nav-link nav-dropdown-item">
                                <i class="fas fa-history me-2"></i> Review History
                            </a>
                        </div>
                    </div>
                </div>
                @endif



                <!-- Admin Section -->
                @if(auth()->user()->isSuperAdmin())
                <div class="nav-section">
                    <div class="nav-section-title">Administration</div>
                    

                    
                    <div class="nav-item nav-dropdown">
                        <a href="#" class="nav-link nav-dropdown-toggle">
                            <div class="nav-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span class="nav-text">System Monitoring</span>
                            <i class="fas fa-chevron-down nav-dropdown-icon"></i>
                        </a>
                        <div class="nav-dropdown-menu">
                            <a href="{{ route('admin.logs.dashboard') }}" class="nav-link nav-dropdown-item">
                                <i class="fas fa-chart-line me-2"></i> Log Dashboard
                            </a>
                            <a href="{{ route('admin.logs.system') }}" class="nav-link nav-dropdown-item">
                                <i class="fas fa-exclamation-triangle me-2"></i> System Logs
                            </a>
                            <a href="{{ route('admin.logs.activity') }}" class="nav-link nav-dropdown-item">
                                <i class="fas fa-user-clock me-2"></i> Activity Logs
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </nav>
            
            <!-- Sidebar Toggle Button at Bottom -->
            <div class="sidebar-footer">
                <button class="sidebar-toggle" type="button" aria-label="Collapse sidebar" title="Toggle sidebar">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
        </aside>

        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay"></div>

        <!-- Main Content Area -->
        <div class="admin-content">
            <!-- Top Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="mobile-sidebar-toggle" type="button">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <nav class="breadcrumb-nav">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        @if(isset($breadcrumbs))
                            @foreach($breadcrumbs as $breadcrumb)
                                <span class="breadcrumb-separator">/</span>
                                @if($loop->last)
                                    <span>{{ $breadcrumb['title'] }}</span>
                                @else
                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                @endif
                            @endforeach
                        @endif
                    </nav>
                </div>
                
                <div class="header-right">
                    <div class="user-menu">
                        <button class="user-menu-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ auth()->user()->name }}</div>
                                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                            </div>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
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
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Main Content -->
            <main class="main-content">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="admin-footer">
                <div class="footer-content">
                    <div class="footer-left">
                        <p>&copy; {{ date('Y') }} Inspection Services. All rights reserved.</p>
                    </div>
                    <div class="footer-right">
                        <p>Version 1.0.0</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/modern-sidebar.js') }}"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Add fade-in animation to main content
        document.addEventListener('DOMContentLoaded', function() {
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                mainContent.style.opacity = '0';
                mainContent.style.transform = 'translateY(20px)';
                mainContent.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                
                setTimeout(function() {
                    mainContent.style.opacity = '1';
                    mainContent.style.transform = 'translateY(0)';
                }, 100);
            }
        });
    </script>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
