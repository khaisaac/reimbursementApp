<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Reports & Export</h2>
            <p class="text-sm text-gray-500 mt-0.5">Generate and export financial reports</p>
        </div>
    </x-slot>

    {{-- Filter Form --}}
    <div class="bg-white rounded-xl border border-gray-100 mb-6">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Report Filters</h3>
            <form method="GET" action="{{ route('reports.index') }}" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    {{-- Month --}}
                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
                        <select name="month" id="month"
                                class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ (request('month', now()->month) == $m) ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Year --}}
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                        <input type="number" name="year" id="year" value="{{ request('year', now()->year) }}"
                               min="2020" max="2099"
                               class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <div class="space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="type" value="all"
                                       {{ request('type', 'all') === 'all' ? 'checked' : '' }}
                                       class="rounded-full border-gray-300 text-blue-800 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">All</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="radio" name="type" value="cash_advance"
                                       {{ request('type') === 'cash_advance' ? 'checked' : '' }}
                                       class="rounded-full border-gray-300 text-blue-800 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Cash Advance</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="radio" name="type" value="allowance"
                                       {{ request('type') === 'allowance' ? 'checked' : '' }}
                                       class="rounded-full border-gray-300 text-blue-800 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Allowance</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="radio" name="type" value="reimbursement"
                                       {{ request('type') === 'reimbursement' ? 'checked' : '' }}
                                       class="rounded-full border-gray-300 text-blue-800 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Reimbursement</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-900 to-cyan-600 text-white text-sm font-semibold rounded-lg hover:from-blue-950 hover:to-cyan-700 shadow-blue-900/25 transition shadow-sm">
                        Generate Report
                    </button>
                    <a href="{{ route('reports.export', request()->query()) }}"
                       class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Table --}}
    @isset($data)
        <div class="bg-white rounded-xl border border-gray-100">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Report Summary — {{ DateTime::createFromFormat('!m', request('month', now()->month))->format('F') }} {{ request('year', now()->year) }}
                </h3>

                @if(request('type', 'all') === 'all' || request('type') === 'cash_advance')
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-700 mb-3">Cash Advances</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CA Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($data['cash_advances'] ?? [] as $ca)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ca->ca_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ca->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ca->project->project_no ?? '—' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucwords(str_replace('_', ' ', $ca->status)) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Rp {{ number_format($ca->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No cash advance records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if(!empty($data['cash_advances']) && count($data['cash_advances']))
                                    <tfoot class="bg-gray-50/50">
                                        <tr>
                                            <td colspan="4" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Total</td>
                                            <td class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Rp {{ number_format($data['total_cash_advances'] ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                @endif

                @if(request('type', 'all') === 'all' || request('type') === 'allowance')
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-700 mb-3">Allowances</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allowance Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($data['allowances'] ?? [] as $allowance)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $allowance->allowance_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $allowance->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $allowance->project->project_no ?? '—' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucwords(str_replace('_', ' ', $allowance->status)) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Rp {{ number_format($allowance->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No allowance records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if(!empty($data['allowances']) && count($data['allowances']))
                                    <tfoot class="bg-gray-50/50">
                                        <tr>
                                            <td colspan="4" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Total</td>
                                            <td class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Rp {{ number_format($data['total_allowances'] ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                @endif

                @if(request('type', 'all') === 'all' || request('type') === 'reimbursement')
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-700 mb-3">Reimbursements</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reimbursement Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($data['reimbursements'] ?? [] as $reimbursement)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $reimbursement->reimbursement_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $reimbursement->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $reimbursement->project->project_no ?? '—' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucwords(str_replace('_', ' ', $reimbursement->status)) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Rp {{ number_format($reimbursement->total_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No reimbursement records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if(!empty($data['reimbursements']) && count($data['reimbursements']))
                                    <tfoot class="bg-gray-50/50">
                                        <tr>
                                            <td colspan="4" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Total</td>
                                            <td class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Rp {{ number_format($data['total_reimbursements'] ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                @endif

                {{-- Grand Total --}}
                @if(request('type', 'all') === 'all')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-semibold text-gray-900">Grand Total</span>
                            <span class="text-lg font-bold text-gray-900">Rp {{ number_format(($data['total_cash_advances'] ?? 0) + ($data['total_allowances'] ?? 0) + ($data['total_reimbursements'] ?? 0), 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endisset
</x-app-layout>
