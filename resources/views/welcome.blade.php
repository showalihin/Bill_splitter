<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <title>{{ config('app.name', 'BillSplitter') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <!-- Sans-serif and Serif for the mature theme -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                margin: 0;
            }
            .hero-section {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                padding: 4rem 1.5rem;
                background-color: var(--background-color);
                position: relative;
                overflow: hidden;
            }
            
            /* Decorative background elements */
            .hero-section::before, .hero-section::after {
                content: '';
                position: absolute;
                border-radius: 50%;
                z-index: 0;
            }
            .hero-section::before {
                width: 600px;
                height: 600px;
                background: radial-gradient(circle, rgba(163,135,88,0.08) 0%, rgba(253,252,240,0) 70%);
                top: -150px;
                left: -150px;
            }
            .hero-section::after {
                width: 500px;
                height: 500px;
                background: radial-gradient(circle, rgba(90,107,83,0.06) 0%, rgba(253,252,240,0) 70%);
                bottom: -100px;
                right: -100px;
            }

            .hero-content {
                max-width: 800px;
                z-index: 1;
            }

            .hero-title {
                font-family: var(--font-serif);
                font-size: 3.5rem;
                font-weight: 800;
                color: var(--text-primary);
                line-height: 1.1;
                margin-bottom: 1.5rem;
                letter-spacing: -0.02em;
            }
            
            .hero-title span {
                color: var(--primary-color);
            }

            .hero-subtitle {
                font-size: 1.25rem;
                color: var(--text-secondary);
                margin-bottom: 3rem;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
                line-height: 1.6;
            }

            .hero-actions {
                display: flex;
                gap: 1rem;
                justify-content: center;
                flex-wrap: wrap;
            }

            .feature-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 2rem;
                max-width: 1200px;
                margin: 0 auto;
                padding: 4rem 1.5rem;
                background-color: var(--surface-color);
                width: 100%;
                border-top: 1px solid var(--border-color);
            }

            .feature-card {
                padding: 2rem;
                background-color: var(--background-color);
                border-radius: var(--radius-lg);
                border: 1px solid var(--border-color);
                text-align: center;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: var(--shadow-md);
            }

            .feature-icon {
                font-size: 2.5rem;
                margin-bottom: 1rem;
                color: var(--accent-color);
            }

            .feature-title {
                font-size: 1.25rem;
                font-weight: 600;
                color: var(--text-primary);
                margin-bottom: 0.75rem;
            }

            .feature-desc {
                font-size: 0.95rem;
                color: var(--text-secondary);
                line-height: 1.5;
            }

            .top-nav {
                position: absolute;
                top: 0;
                right: 0;
                padding: 1.5rem 2rem;
                z-index: 10;
                display: flex;
                gap: 1rem;
            }

            @media (max-width: 768px) {
                .hero-title { font-size: 2.5rem; }
                .hero-subtitle { font-size: 1.1rem; }
            }
        </style>
    </head>
    <body class="rs-fade-in">
        
        @if (Route::has('login'))
            <nav class="top-nav">
                <button id="theme-toggle" aria-label="Toggle Theme" style="background: none; border: none; color: var(--text-primary); cursor: pointer; padding: 0.5rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: background-color 0.2s; margin-right: 1rem;" onmouseover="this.style.backgroundColor='var(--surface-alt)'" onmouseout="this.style.backgroundColor='transparent'">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </button>
                @auth
                    <a href="{{ url('/dashboard') }}" class="rs-btn rs-btn-secondary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="rs-btn rs-btn-secondary" style="background: transparent; border-color: transparent;">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="rs-btn rs-btn-primary">Register</a>
                    @endif
                @endauth
            </nav>
        @endif

        <main class="hero-section">
            <div class="hero-content rs-slide-up">
                <h1 class="hero-title">
                    Dining Out,<br>
                    <span>Perfectly Split.</span>
                </h1>
                <p class="hero-subtitle">
                    The sophisticated way to manage restaurant bills. Add participants, assign dishes, calculate VAT & service charges, and split fairly without the awkwardness.
                </p>
                <div class="hero-actions">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rs-btn rs-btn-primary rs-btn-lg">Go to Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="rs-btn rs-btn-primary rs-btn-lg">Get Started Free</a>
                        <a href="{{ route('login') }}" class="rs-btn rs-btn-secondary rs-btn-lg">Sign In</a>
                    @endauth
                </div>
            </div>
        </main>

        <section class="feature-grid">
            <div class="feature-card rs-slide-up" style="animation-delay: 0.1s;">
                <div class="feature-icon">🍽️</div>
                <h3 class="feature-title">Global Menus</h3>
                <p class="feature-desc">Access a curated database of real restaurant menus, or add your own private dining spots.</p>
            </div>
            
            <div class="feature-card rs-slide-up" style="animation-delay: 0.2s;">
                <div class="feature-icon">🧮</div>
                <h3 class="feature-title">Precise Splitting</h3>
                <p class="feature-desc">Assign specific items to people. Share a pizza? Split it 50/50. It handles the math.</p>
            </div>
            
            <div class="feature-card rs-slide-up" style="animation-delay: 0.3s;">
                <div class="feature-icon">🧾</div>
                <h3 class="feature-title">Tax & Service</h3>
                <p class="feature-desc">Automatically calculates proportional VAT and service charges based on what each person ordered.</p>
            </div>
        </section>

        <footer style="padding: 2rem; text-align: center; border-top: 1px solid var(--border-light); background-color: var(--surface-color);">
            <p style="color: var(--text-muted); font-size: 0.85rem;">&copy; {{ date('Y') }} BillSplitter. Crafted with elegance.</p>
        </footer>
    </body>
</html>
