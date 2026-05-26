<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Split restaurant bills effortlessly with friends.">

        <title>{{ config('app.name', 'BillSplitter') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <!-- Sans-serif and Serif for the mature theme -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .rs-app-wrapper {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
            .rs-page-header {
                background-color: var(--surface-color);
                border-bottom: 1px solid var(--border-color);
                padding: 2rem 0 1rem;
                margin-bottom: 2rem;
            }
            .rs-page-header h2 {
                font-size: 2rem;
                color: var(--text-primary);
                margin: 0;
            }
            .rs-page-content {
                flex: 1;
                padding-bottom: 4rem;
            }
        </style>
    </head>
    <body>
        <div class="rs-app-wrapper">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="rs-page-header">
                    <div class="rs-container">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="rs-page-content rs-fade-in">
                <div class="rs-container">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
