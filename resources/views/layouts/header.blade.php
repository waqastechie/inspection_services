<!-- Elite Professional Header -->
<header class="professional-header">
    <div class="header-gradient"></div>
    <div class="container-fluid">
        <div class="header-content">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="header-title">
                        <i class="fas fa-cogs me-2 fa-sm"></i>
                        Professional Inspection Services
                    </h1>
                    <p class="header-subtitle">
                        Oil & Gas Industry Compliance | API, LOLER, BS Certified Standards
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    @auth
                        <div class="header-user-info d-flex align-items-center justify-content-md-end">
                            <div class="user-details me-3">
                                <div class="text-white small">
                                    <i class="fas fa-user me-2"></i>{{ Auth::user()->name }}
                                </div>
                                <div class="text-white-50 small">
                                    <span class="badge bg-{{ Auth::user()->role_color }} me-2">{{ Auth::user()->role_name }}</span>
                                    {{ date('M j, Y H:i') }}
                                </div>
                            </div>
                            <div class="header-actions">
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light me-2">
                                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                    </a>
                                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-light me-2">
                                        <i class="fas fa-users me-1"></i>Users
                                    </a>
                                @endif
                                <a href="{{ route('inspections.index') }}" class="btn btn-sm btn-outline-light me-2">
                                    <i class="fas fa-list me-1"></i>Inspections
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="header-info">
                            <div class="text-white-50 small">
                                <i class="fas fa-calendar-alt me-2"></i>
                                {{ date('F j, Y') }}
                            </div>
                            <div class="text-white-50 small">
                                <i class="fas fa-clock me-2"></i>
                                {{ date('H:i T') }}
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>
