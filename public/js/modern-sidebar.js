/**
 * Modern Sidebar JavaScript
 * Handles sidebar interactions, responsive behavior, and animations
 */

class ModernSidebar {
    constructor() {
        this.sidebar = document.querySelector('.admin-sidebar');
        this.layout = document.querySelector('.admin-layout');
        this.sidebarToggle = document.querySelector('.sidebar-toggle');
        this.mobileSidebarToggle = document.querySelector('.mobile-sidebar-toggle');
        this.sidebarOverlay = document.querySelector('.sidebar-overlay');
        this.dropdownToggles = document.querySelectorAll('.nav-dropdown-toggle');
        
        this.isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
        this.isMobile = window.innerWidth <= 1024;
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.setupDropdowns();
        this.setupResponsive();
        this.restoreState();
        this.highlightActiveNav();
    }
    
    setupEventListeners() {
        // Desktop sidebar toggle
        if (this.sidebarToggle) {
            this.sidebarToggle.addEventListener('click', () => {
                this.toggleSidebar();
            });
        }
        
        // Mobile sidebar toggle
        if (this.mobileSidebarToggle) {
            this.mobileSidebarToggle.addEventListener('click', () => {
                this.toggleMobileSidebar();
            });
        }
        
        // Overlay click to close mobile sidebar
        if (this.sidebarOverlay) {
            this.sidebarOverlay.addEventListener('click', () => {
                this.closeMobileSidebar();
            });
        }
        
        // Window resize handler
        window.addEventListener('resize', () => {
            this.handleResize();
        });
        
        // Escape key to close mobile sidebar
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isMobile) {
                this.closeMobileSidebar();
            }
        });
    }
    
    setupDropdowns() {
        this.dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const dropdown = toggle.closest('.nav-dropdown');
                this.toggleDropdown(dropdown);
            });
        });
    }
    
    setupResponsive() {
        this.handleResize();
    }
    
    toggleSidebar() {
        if (this.isMobile) {
            this.toggleMobileSidebar();
            return;
        }
        
        this.isCollapsed = !this.isCollapsed;
        this.updateSidebarState();
        this.saveState();
    }
    
    toggleMobileSidebar() {
        if (!this.isMobile) return;
        
        const isOpen = this.sidebar.classList.contains('mobile-open');
        
        if (isOpen) {
            this.closeMobileSidebar();
        } else {
            this.openMobileSidebar();
        }
    }
    
    openMobileSidebar() {
        this.sidebar.classList.add('mobile-open');
        this.sidebarOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    closeMobileSidebar() {
        this.sidebar.classList.remove('mobile-open');
        this.sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    toggleDropdown(dropdown) {
        const isOpen = dropdown.classList.contains('open');
        
        // Close all other dropdowns
        this.dropdownToggles.forEach(toggle => {
            const otherDropdown = toggle.closest('.nav-dropdown');
            if (otherDropdown !== dropdown) {
                otherDropdown.classList.remove('open');
            }
        });
        
        // Toggle current dropdown
        if (isOpen) {
            dropdown.classList.remove('open');
        } else {
            dropdown.classList.add('open');
        }
    }
    
    updateSidebarState() {
        if (this.isCollapsed) {
            this.sidebar.classList.add('collapsed');
            this.layout.classList.add('sidebar-collapsed');
        } else {
            this.sidebar.classList.remove('collapsed');
            this.layout.classList.remove('sidebar-collapsed');
        }
        
        // Update toggle button aria-label for accessibility
        if (this.sidebarToggle) {
            const ariaLabel = this.isCollapsed ? 'Expand sidebar' : 'Collapse sidebar';
            this.sidebarToggle.setAttribute('aria-label', ariaLabel);
        }
    }
    
    handleResize() {
        const wasMobile = this.isMobile;
        this.isMobile = window.innerWidth <= 1024;
        
        if (wasMobile !== this.isMobile) {
            if (this.isMobile) {
                // Switched to mobile
                this.closeMobileSidebar();
                this.sidebar.classList.remove('collapsed');
                this.layout.classList.remove('sidebar-collapsed');
            } else {
                // Switched to desktop
                this.updateSidebarState();
                document.body.style.overflow = '';
            }
        }
    }
    
    highlightActiveNav() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href) && href !== '/') {
                link.classList.add('active');
                
                // Open parent dropdown if this is a dropdown item
                const dropdown = link.closest('.nav-dropdown');
                if (dropdown) {
                    dropdown.classList.add('open');
                }
            }
        });
    }
    
    saveState() {
        localStorage.setItem('sidebar-collapsed', this.isCollapsed.toString());
    }
    
    restoreState() {
        if (!this.isMobile) {
            this.updateSidebarState();
        }
    }
    
    // Public methods for external use
    collapse() {
        if (!this.isMobile) {
            this.isCollapsed = true;
            this.updateSidebarState();
            this.saveState();
        }
    }
    
    expand() {
        if (!this.isMobile) {
            this.isCollapsed = false;
            this.updateSidebarState();
            this.saveState();
        }
    }
    
    openDropdown(dropdownSelector) {
        const dropdown = document.querySelector(dropdownSelector);
        if (dropdown) {
            dropdown.classList.add('open');
        }
    }
    
    closeDropdown(dropdownSelector) {
        const dropdown = document.querySelector(dropdownSelector);
        if (dropdown) {
            dropdown.classList.remove('open');
        }
    }
}

// Notification Badge Animation
class NotificationBadge {
    constructor() {
        this.badges = document.querySelectorAll('.nav-badge');
        this.init();
    }
    
    init() {
        this.animateBadges();
    }
    
    animateBadges() {
        this.badges.forEach(badge => {
            if (parseInt(badge.textContent) > 0) {
                badge.style.animation = 'pulse 2s infinite';
            }
        });
    }
    
    updateBadge(selector, count) {
        const badge = document.querySelector(selector);
        if (badge) {
            badge.textContent = count;
            if (count > 0) {
                badge.style.display = 'block';
                badge.style.animation = 'pulse 2s infinite';
            } else {
                badge.style.display = 'none';
            }
        }
    }
}

// Search Functionality
class SidebarSearch {
    constructor() {
        this.searchInput = document.querySelector('.sidebar-search-input');
        this.navItems = document.querySelectorAll('.nav-item');
        this.init();
    }
    
    init() {
        if (this.searchInput) {
            this.setupSearch();
        }
    }
    
    setupSearch() {
        this.searchInput.addEventListener('input', (e) => {
            this.filterNavItems(e.target.value);
        });
    }
    
    filterNavItems(query) {
        const searchTerm = query.toLowerCase().trim();
        
        this.navItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            const matches = text.includes(searchTerm);
            
            item.style.display = matches || searchTerm === '' ? 'block' : 'none';
        });
    }
}

// Tooltip Management
class SidebarTooltips {
    constructor() {
        this.navLinks = document.querySelectorAll('.nav-link');
        this.init();
    }
    
    init() {
        this.createTooltips();
    }
    
    createTooltips() {
        this.navLinks.forEach(link => {
            const text = link.querySelector('.nav-text');
            if (text) {
                const tooltip = document.createElement('div');
                tooltip.className = 'nav-tooltip';
                tooltip.textContent = text.textContent.trim();
                link.appendChild(tooltip);
            }
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sidebar
    window.modernSidebar = new ModernSidebar();
    
    // Initialize notification badges
    window.notificationBadge = new NotificationBadge();
    
    // Initialize search (if search input exists)
    window.sidebarSearch = new SidebarSearch();
    
    // Initialize tooltips
    window.sidebarTooltips = new SidebarTooltips();
    
    // Add smooth scrolling to navigation
    document.querySelectorAll('.nav-link[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add loading states for navigation
    document.querySelectorAll('.nav-link:not([href^="#"])').forEach(link => {
        link.addEventListener('click', function() {
            if (!this.classList.contains('active')) {
                this.style.opacity = '0.7';
                this.style.pointerEvents = 'none';
                
                // Reset after navigation (fallback)
                setTimeout(() => {
                    this.style.opacity = '';
                    this.style.pointerEvents = '';
                }, 3000);
            }
        });
    });
});

// Add CSS for pulse animation
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(style);