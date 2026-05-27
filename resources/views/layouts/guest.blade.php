<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Split restaurant bills effortlessly with friends. Track who pays what, handle VAT & service charges, and settle up instantly.">

        <title>{{ config('app.name', 'BillSplitter') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <!-- Sans-serif and Serif for the mature theme -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .rs-auth-layout {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 2rem 1rem;
            }
            /* Allow scrolling on mobile when keyboard opens */
            @media (max-height: 750px), (max-width: 600px) {
                .rs-auth-layout {
                    justify-content: flex-start;
                    padding-top: 2rem;
                }
            }
            .rs-auth-container {
                width: 100%;
                max-width: 440px;
            }
            .rs-auth-logo {
                text-align: center;
                margin-bottom: 2rem;
            }
            .rs-auth-logo h1 {
                font-size: 2rem;
                margin-bottom: 0.5rem;
                color: var(--primary-color);
            }
            .rs-auth-logo h1 span {
                color: var(--accent-color);
            }
            .rs-auth-logo p {
                color: var(--text-secondary);
                font-size: 0.95rem;
            }
        </style>
    </head>
    <body>
        <div class="rs-auth-layout">
            <div class="rs-auth-container rs-slide-up">
                
                <!-- Logo -->
                <div class="rs-auth-logo">
                    <a href="/">
                        <h1>Bill<span>Splitter</span></h1>
                        <p>Split bills, not friendships</p>
                    </a>
                </div>

                <!-- Auth Card -->
                <div class="rs-card">
                    {{ $slot }}
                </div>
                
            </div>
        </div>
    </body>
</html>
