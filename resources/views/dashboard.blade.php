<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Dashboard</h2>
            <p class="text-sm text-gray-500 mt-0.5">Welcome back, {{ Auth::user()->name }}</p>
        </div>
    </x-slot>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        {{-- Total Cash Advances --}}
        <div class="card-hover bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-5 sm:p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-500">Cash Advances</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalCa }}</p>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-amber-50 text-amber-700">
                        {{ $pendingCa }} pending
                    </span>
                </div>
            </div>
        </div>

        {{-- Total Reimbursements --}}
        <div class="card-hover bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-5 sm:p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-500">Reimbursements</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalReimbursements }}</p>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-amber-50 text-amber-700">
                        {{ $pendingReimbursements }} pending
                    </span>
                </div>
            </div>
        </div>

        {{-- Total Allowances --}}
        <div class="card-hover bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-5 sm:p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-cyan-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-500">Allowances</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalAllowances }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Outstanding Amount --}}
        <div class="card-hover bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-5 sm:p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-500">Outstanding CA</p>
                        <p class="text-xl font-bold text-red-600">Rp {{ number_format($outstandingAmount, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="mt-3">
                    @php $percent = $outstandingAmount > 0 ? min(100, ($outstandingAmount / 15000000) * 100) : 0; @endphp
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full {{ $percent > 80 ? 'bg-red-500' : ($percent > 50 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ $percent }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Limit Rp 15,000,000</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6 sm:mb-8">
        <a href="{{ route('cash-advances.create') }}" class="card-hover group flex items-center gap-4 bg-white border border-gray-100 rounded-xl p-4 sm:p-5 transition-all">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-900 to-cyan-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-gray-900">New Cash Advance</p>
                <p class="text-xs text-gray-500">Request CA for project expenses</p>
            </div>
        </a>
        <a href="{{ route('allowances.create') }}" class="card-hover group flex items-center gap-4 bg-white border border-gray-100 rounded-xl p-4 sm:p-5 transition-all">
            <div class="w-10 h-10 bg-gradient-to-br from-cyan-600 to-blue-900 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-gray-900">New Allowance</p>
                <p class="text-xs text-gray-500">Claim daily allowance</p>
            </div>
        </a>
        <a href="{{ route('reimbursements.create') }}" class="card-hover group flex items-center gap-4 bg-white border border-gray-100 rounded-xl p-4 sm:p-5 transition-all">
            <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-gray-900">New Reimbursement</p>
                <p class="text-xs text-gray-500">Submit claim or CA settlement</p>
            </div>
        </a>
    </div>

    {{-- Tables Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">
        {{-- Recent Cash Advances --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Recent Cash Advances</h3>
                <a href="{{ route('cash-advances.index') }}" class="text-sm text-blue-800 hover:text-blue-900 font-medium">View all →</a>
            </div>
            @if($recentCa->isEmpty())
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-sm text-gray-500">No cash advances found</p>
                </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/80">
                            <th class="px-4 sm:px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">CA Number</th>
                            <th class="px-4 sm:px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-4 sm:px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentCa as $ca)
                        <tr class="table-row-hover">
                            <td class="px-4 sm:px-5 py-3">
                                <a href="{{ route('cash-advances.show', $ca) }}" class="text-sm font-medium text-blue-800 hover:text-blue-900">{{ $ca->ca_number }}</a>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $ca->user->name }} · {{ $ca->project->project_no }}</p>
                            </td>
                            <td class="px-4 sm:px-5 py-3 text-right">
                                <p class="text-sm font-medium text-gray-900">Rp {{ number_format($ca->amount, 0, ',', '.') }}</p>
                                <p class="text-xs text-red-500 mt-0.5">Out: Rp {{ number_format($ca->outstanding_amount, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 sm:px-5 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $ca->status_badge }}">
                                    {{ $ca->status_label }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Recent Reimbursements --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Recent Reimbursements</h3>
                <a href="{{ route('reimbursements.index') }}" class="text-sm text-blue-800 hover:text-blue-900 font-medium">View all →</a>
            </div>
            @if($recentReimbursements->isEmpty())
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                    <p class="text-sm text-gray-500">No reimbursements found</p>
                </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/80">
                            <th class="px-4 sm:px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Number</th>
                            <th class="px-4 sm:px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 sm:px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentReimbursements as $rmb)
                        <tr class="table-row-hover">
                            <td class="px-4 sm:px-5 py-3">
                                <a href="{{ route('reimbursements.show', $rmb) }}" class="text-sm font-medium text-blue-800 hover:text-blue-900">{{ $rmb->reimbursement_number }}</a>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $rmb->user->name }} · {{ $rmb->type === 'ca_settlement' ? 'CA Settlement' : 'Direct Claim' }}</p>
                            </td>
                            <td class="px-4 sm:px-5 py-3 text-right">
                                <p class="text-sm font-medium text-gray-900">Rp {{ number_format($rmb->total_amount, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 sm:px-5 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $rmb->status_badge }}">
                                    {{ $rmb->status_label }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
