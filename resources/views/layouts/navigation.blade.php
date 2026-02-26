{{-- Sidebar Navigation --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 shadow-sm transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:z-30 flex flex-col">

    {{-- Logo area --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <img src="/image/logo.webp" alt="Logo" class="h-10 w-auto object-contain">
            {{-- <span class="text-gray-900 font-bold text-lg tracking-tight">HDS Finance</span> --}}
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 sidebar-scroll">
        {{-- Main --}}
        <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Main</p>

        <a href="{{ route('dashboard') }}"
           class="sidebar-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-900 to-cyan-600 text-white shadow-md shadow-blue-900/30' : 'text-gray-600 hover:bg-gray-200/70 hover:text-gray-900' }}">
            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- Finance --}}
        <p class="px-3 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Finance</p>

        <a href="{{ route('cash-advances.index') }}"
           class="sidebar-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('cash-advances.*') ? 'bg-gradient-to-r from-blue-900 to-cyan-600 text-white shadow-md shadow-blue-900/30' : 'text-gray-600 hover:bg-gray-200/70 hover:text-gray-900' }}">
            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('cash-advances.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Cash Advance
        </a>

        <a href="{{ route('allowances.index') }}"
           class="sidebar-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('allowances.*') ? 'bg-gradient-to-r from-blue-900 to-cyan-600 text-white shadow-md shadow-blue-900/30' : 'text-gray-600 hover:bg-gray-200/70 hover:text-gray-900' }}">
            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('allowances.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Allowance
        </a>

        <a href="{{ route('reimbursements.index') }}"
           class="sidebar-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('reimbursements.*') ? 'bg-gradient-to-r from-blue-900 to-cyan-600 text-white shadow-md shadow-blue-900/30' : 'text-gray-600 hover:bg-gray-200/70 hover:text-gray-900' }}">
            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('reimbursements.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
            </svg>
            Reimbursement
        </a>

        {{-- Operations --}}
        <p class="px-3 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Operations</p>

        <a href="{{ route('attendances.index') }}"
           class="sidebar-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('attendances.*') ? 'bg-gradient-to-r from-blue-900 to-cyan-600 text-white shadow-md shadow-blue-900/30' : 'text-gray-600 hover:bg-gray-200/70 hover:text-gray-900' }}">
            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('attendances.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Attendance
        </a>

        @if(auth()->user()->isAdmin() || auth()->user()->isPicProject())
        <a href="{{ route('projects.index') }}"
           class="sidebar-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('projects.*') ? 'bg-gradient-to-r from-blue-900 to-cyan-600 text-white shadow-md shadow-blue-900/30' : 'text-gray-600 hover:bg-gray-200/70 hover:text-gray-900' }}">
            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('projects.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Projects
        </a>
        @endif

        @if(auth()->user()->isAdmin() || auth()->user()->isFinance())
        <a href="{{ route('reports.index') }}"
           class="sidebar-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('reports.*') ? 'bg-gradient-to-r from-blue-900 to-cyan-600 text-white shadow-md shadow-blue-900/30' : 'text-gray-600 hover:bg-gray-200/70 hover:text-gray-900' }}">
            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('reports.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Reports & Export
        </a>
        @endif
    </nav>

    {{-- Sidebar footer --}}
    <div class="p-3 border-t border-gray-200">
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200/70 hover:text-gray-900 transition-all duration-150">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Settings
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-150">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Log Out
            </button>
        </form>
    </div>
</aside>
