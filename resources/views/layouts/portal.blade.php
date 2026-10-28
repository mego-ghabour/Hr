@php
    $portalFont = \App\Models\Setting::get('font_family', 'Cairo');
    $fontUrls = [
        'Cairo' => 'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap',
        'Tajawal' => 'https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap',
        'IBM Plex Sans Arabic' => 'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap',
        'Almarai' => 'https://fonts.googleapis.com/css2?family=Almarai:wght@400;700;800&display=swap',
        'Readex Pro' => 'https://fonts.googleapis.com/css2?family=Readex+Pro:wght@400;500;600;700&display=swap',
        'Alexandria' => 'https://fonts.googleapis.com/css2?family=Alexandria:wght@400;500;600;700;800&display=swap',
        'Noto Sans Arabic' => 'https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap',
    ];
    $portalFontUrl = $fontUrls[$portalFont] ?? "https://fonts.googleapis.com/css2?family=" . urlencode($portalFont) . ":wght@400;500;600;700;800&display=swap";
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'بوابة الموظف الذاتية' }} | {{ \App\Models\Setting::get('brand_name', 'نظام الموارد البشرية') }}</title>

    <!-- PWA & Mobile Capabilities -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d9488">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ \App\Models\Setting::get('brand_name', 'Bwsla HR') }}">
    <link rel="apple-touch-icon" href="/icons/icon.svg">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">

    <!-- Dynamic Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ $portalFontUrl }}" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        cairo: ["{{ $portalFont }}", 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: "{{ $portalFont }}", sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Print styling for Payslip */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
            }
            .print-only {
                display: block !important;
            }
            .payslip-container {
                box-shadow: none !important;
                border: 1px solid #e2e8f0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>

    @livewireStyles
</head>
<body class="h-full flex flex-col font-cairo text-slate-800 antialiased selection:bg-brand-500 selection:text-white">

    <!-- Page Content Slot -->
    <div class="flex-1 flex flex-col min-h-screen">
        {{ $slot ?? '' }}
        @yield('content')
    </div>

    @livewireScripts

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((reg) => console.log('Bwsla HR PWA ServiceWorker registered:', reg.scope))
                    .catch((err) => console.warn('Bwsla HR PWA ServiceWorker registration failed:', err));
            });
        }
    </script>
</body>
</html>
