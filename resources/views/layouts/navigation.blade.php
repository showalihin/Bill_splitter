<nav class="rs-navbar">
    <div class="rs-container">
        <div class="rs-navbar-container">
            <!-- Brand -->
            <a href="{{ route('dashboard') }}" class="rs-brand">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Bill<span>Splitter</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="rs-nav-links">
                <a href="{{ route('dashboard') }}" class="rs-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('restaurants.index') }}" class="rs-nav-link {{ request()->routeIs('restaurants.*') ? 'active' : '' }}">
                    Restaurants
                </a>
                @if (Route::has('bills.index'))
                <a href="{{ route('bills.index') }}" class="rs-nav-link {{ request()->routeIs('bills.*') ? 'active' : '' }}">
                    Split Bills
                </a>
                @endif
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="rs-nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" style="color: var(--primary-color); font-weight: bold;">
                        ⚙️ Admin
                    </a>
                @endif
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <!-- Theme Toggle -->
                <button id="theme-toggle" aria-label="Toggle Theme" style="background: none; border: none; color: var(--text-primary); cursor: pointer; padding: 0.5rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='var(--surface-alt)'" onmouseout="this.style.backgroundColor='transparent'">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </button>

                <!-- Desktop User Menu -->
                <div class="rs-nav-user rs-dropdown-wrapper" style="display: flex; align-items: center;">
                    <button class="rs-user-btn" data-dropdown-toggle="true">
                        <div class="rs-avatar">
                            @if(Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <span style="display: none; @media(min-width: 768px) { display: block; }">{{ Auth::user()->name }}</span>
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="rs-dropdown-menu">
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="rs-dropdown-item" style="color: var(--primary-color);">Admin Panel</a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="rs-dropdown-item">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rs-dropdown-item" style="border: none; background: none; font-family: inherit; cursor: pointer; text-align: left;">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile Toggle -->
                <button class="rs-mobile-toggle" aria-label="Toggle menu" aria-expanded="false">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="rs-mobile-menu">
        <a href="{{ route('dashboard') }}" class="rs-mobile-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            Dashboard
        </a>
        <a href="{{ route('restaurants.index') }}" class="rs-mobile-link {{ request()->routeIs('restaurants.*') ? 'active' : '' }}">
            Restaurants
        </a>
        @if (Route::has('bills.index'))
        <a href="{{ route('bills.index') }}" class="rs-mobile-link {{ request()->routeIs('bills.*') ? 'active' : '' }}">
            Split Bills
        </a>
        @endif
        @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="rs-mobile-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" style="color: var(--primary-color); font-weight: bold;">
                ⚙️ Admin
            </a>
        @endif

        <div style="padding: 1rem 1.5rem; margin-top: 0.5rem; border-top: 1px solid var(--border-light);">
            <div style="font-weight: 600; color: var(--text-primary);">{{ Auth::user()->name }}</div>
            <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem;">{{ Auth::user()->email }}</div>
            
            <a href="{{ route('profile.edit') }}" class="rs-mobile-link" style="padding-left: 0;">
                Profile
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rs-mobile-link" style="padding-left: 0; width: 100%; text-align: left; background: none; border: none; cursor: pointer; font-family: inherit; font-size: inherit;">
                    Sign out
                </button>
            </form>
        </div>
    </div>
</nav>
