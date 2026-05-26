
document.addEventListener('DOMContentLoaded', () => {
    
    // --- Theme Toggle ---
    const themeToggleBtn = document.getElementById('theme-toggle');
    
    // Initial check (run immediately to avoid flash, but we also do it here for button state if needed)
    const initTheme = () => {
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
        } else if (savedTheme === 'light') {
            document.documentElement.classList.add('light');
            document.documentElement.classList.remove('dark');
        }
    };
    initTheme();

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                localStorage.setItem('theme', 'light');
            } else if (document.documentElement.classList.contains('light')) {
                document.documentElement.classList.remove('light');
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                // If neither class is present, rely on system preference to determine current state and invert it
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (systemPrefersDark) {
                    document.documentElement.classList.remove('dark');
                    document.documentElement.classList.add('light');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    document.documentElement.classList.remove('light');
                    localStorage.setItem('theme', 'dark');
                }
            }
        });
    }

    
    // --- Mobile Navigation Toggle ---
    const mobileToggle = document.querySelector('.rs-mobile-toggle');
    const mobileMenu = document.querySelector('.rs-mobile-menu');
    
    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('show');
            const isExpanded = mobileMenu.classList.contains('show');
            mobileToggle.setAttribute('aria-expanded', isExpanded);
        });
    }

    // --- Dropdowns ---
    const dropdownWrappers = document.querySelectorAll('.rs-dropdown-wrapper');
    
    dropdownWrappers.forEach(wrapper => {
        const toggleBtn = wrapper.querySelector('[data-dropdown-toggle]');
        const menu = wrapper.querySelector('.rs-dropdown-menu');
        
        if (toggleBtn && menu) {
            // Toggle on click (for mobile/touch devices or explicitly clicking)
            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                // Close others
                document.querySelectorAll('.rs-dropdown-menu.show').forEach(m => {
                    if (m !== menu) m.classList.remove('show');
                });
                menu.classList.toggle('show');
            });
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.rs-dropdown-wrapper')) {
            document.querySelectorAll('.rs-dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });

    // --- Modals ---
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    const modalCloses = document.querySelectorAll('[data-modal-close]');
    
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = trigger.getAttribute('data-modal-target');
            const targetModal = document.getElementById(targetId);
            if (targetModal) {
                targetModal.classList.add('show');
            }
        });
    });

    modalCloses.forEach(close => {
        close.addEventListener('click', (e) => {
            e.preventDefault();
            const modal = close.closest('.rs-modal-overlay');
            if (modal) {
                modal.classList.remove('show');
            }
        });
    });

    // Close modal on click outside content
    document.querySelectorAll('.rs-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.classList.remove('show');
            }
        });
    });

    // --- Inline Form Toggles (e.g. edit item inline) ---
    const inlineToggles = document.querySelectorAll('[data-toggle-inline]');
    inlineToggles.forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = toggle.getAttribute('data-toggle-inline');
            const targetEl = document.getElementById(targetId);
            if (targetEl) {
                targetEl.style.display = targetEl.style.display === 'none' ? 'block' : 'none';
            }
        });
    });

});
