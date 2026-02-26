<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex">
            {{-- Left decorative panel --}}
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-20 left-20 w-72 h-72 bg-blue-500 rounded-full filter blur-3xl"></div>
                    <div class="absolute bottom-20 right-20 w-96 h-96 bg-cyan-500 rounded-full filter blur-3xl"></div>
                </div>
                <div class="relative z-10 flex flex-col justify-center px-12 xl:px-20">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-white font-bold text-2xl tracking-tight">HDS Finance</span>
                    </div>
                    <h1 class="text-4xl xl:text-5xl font-bold text-white leading-tight mb-4">
                        Financial Management<br>System
                    </h1>
                    <p class="text-lg text-blue-200 max-w-md">
                        Manage cash advances, allowances, and reimbursements in one unified platform.
                    </p>
                    <div class="mt-10 flex items-center gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">CA</div>
                            <div class="text-xs text-blue-300 mt-1">Cash Advance</div>
                        </div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">ALW</div>
                            <div class="text-xs text-blue-300 mt-1">Allowance</div>
                        </div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">RMB</div>
                            <div class="text-xs text-blue-300 mt-1">Reimbursement</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right form panel --}}
            <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 bg-gray-50">
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-900 to-cyan-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-gray-900 font-bold text-xl">HDS Finance</span>
                </div>
                <div class="w-full sm:max-w-md">
                    <div class="bg-white px-8 py-8 rounded-2xl border border-gray-100 shadow-sm">
                        {{ $slot }}
                    </div>
                    <p class="text-center text-xs text-gray-400 mt-6">&copy; {{ date('Y') }} HDS Finance. All rights reserved.</p>
                </div>
            </div>
        </div>
    </body>
</html>
