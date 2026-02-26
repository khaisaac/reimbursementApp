<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Reimbursement: {{ $reimbursement->reimbursement_number }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">View reimbursement details</p>
            </div>
            <a href="{{ route('reimbursements.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Detail Card --}}
        <div class="bg-white overflow-hidden rounded-xl border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Reimbursement Details</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $reimbursement->status_badge }}">
                        {{ $reimbursement->status_label }}
                    </span>
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Reimbursement Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $reimbursement->reimbursement_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">User</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $reimbursement->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Project</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $reimbursement->project->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $reimbursement->type === 'ca_settlement' ? 'CA Settlement' : 'Direct Claim' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $reimbursement->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($reimbursement->total_amount, 0, ',', '.') }}</dd>
                    </div>
                    @if($reimbursement->description)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reimbursement->description }}</dd>
                        </div>
                    @endif
                </dl>

                {{-- Rejection Reason --}}
                @if($reimbursement->status === 'rejected' && $reimbursement->rejection_reason)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="rounded-lg bg-red-50 p-4">
                            <h4 class="text-sm font-medium text-red-800 mb-1">Rejection Reason</h4>
                            <p class="text-sm text-red-700">{{ $reimbursement->rejection_reason }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Linked Cash Advances (CA Settlement only) --}}
        @if($reimbursement->type === 'ca_settlement' && $reimbursement->cashAdvances->count() > 0)
            <div class="bg-white overflow-hidden rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Linked Cash Advances</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CA Number</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Settled Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reimbursement->cashAdvances as $ca)
                                    <tr class="table-row-hover">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <a href="{{ route('cash-advances.show', $ca) }}" class="text-blue-800 hover:text-blue-900 font-medium">
                                                {{ $ca->ca_number }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right">
                                            Rp {{ number_format($ca->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right">
                                            Rp {{ number_format($ca->pivot->settled_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Items Table --}}
        <div class="bg-white overflow-hidden rounded-xl border border-gray-100">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Reimbursement Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($reimbursement->items as $item)
                                <tr class="table-row-hover">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $item->category_label }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->description }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                        @if($item->receipt_path)
                                            <a href="{{ asset('storage/' . $item->receipt_path) }}" target="_blank"
                                               class="inline-flex items-center text-blue-800 hover:text-blue-900">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                View
                                            </a>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50/80">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">Total</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900 text-right">Rp {{ number_format($reimbursement->total_amount, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Approval Timeline --}}
        <div class="bg-white overflow-hidden rounded-xl border border-gray-100">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Approval Timeline</h3>
                <ol class="relative border-l border-gray-200 ml-3 space-y-6">
                    {{-- Created --}}
                    <li class="ml-6">
                        <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 ring-8 ring-white">
                            <svg class="h-3 w-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.59l-1.95 1.1a.75.75 0 10.74 1.3l2.25-1.25a.75.75 0 00.46-.69V6.75z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        <h4 class="text-sm font-semibold text-gray-900">Created</h4>
                        <p class="text-xs text-gray-500">{{ $reimbursement->created_at->format('d M Y, H:i') }} by {{ $reimbursement->user->name }}</p>
                    </li>

                    {{-- Approved by PIC --}}
                    @if($reimbursement->approved_by_pic_at)
                        <li class="ml-6">
                            <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 ring-8 ring-white">
                                <svg class="h-3 w-3 text-blue-800" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <h4 class="text-sm font-semibold text-gray-900">Approved by PIC</h4>
                            <p class="text-xs text-gray-500">{{ $reimbursement->approved_by_pic_at->format('d M Y, H:i') }} by {{ $reimbursement->approvedByPic->name ?? '-' }}</p>
                        </li>
                    @endif

                    {{-- Approved by Finance --}}
                    @if($reimbursement->approved_by_finance_at)
                        <li class="ml-6">
                            <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-green-100 ring-8 ring-white">
                                <svg class="h-3 w-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <h4 class="text-sm font-semibold text-gray-900">Approved by Finance</h4>
                            <p class="text-xs text-gray-500">{{ $reimbursement->approved_by_finance_at->format('d M Y, H:i') }} by {{ $reimbursement->approvedByFinance->name ?? '-' }}</p>
                        </li>
                    @endif

                    {{-- Paid --}}
                    @if($reimbursement->status === 'paid')
                        <li class="ml-6">
                            <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 ring-8 ring-white">
                                <svg class="h-3 w-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <h4 class="text-sm font-semibold text-emerald-600">Paid</h4>
                            <p class="text-xs text-gray-500">{{ $reimbursement->updated_at->format('d M Y, H:i') }}</p>
                        </li>
                    @endif

                    {{-- Rejected --}}
                    @if($reimbursement->status === 'rejected')
                        <li class="ml-6">
                            <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-red-100 ring-8 ring-white">
                                <svg class="h-3 w-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <h4 class="text-sm font-semibold text-red-600">Rejected</h4>
                            <p class="text-xs text-gray-500">{{ $reimbursement->updated_at->format('d M Y, H:i') }}</p>
                        </li>
                    @endif
                </ol>
            </div>
        </div>

        {{-- Action Buttons --}}
        @if(!in_array($reimbursement->status, ['paid', 'rejected']))
            <div class="bg-white overflow-hidden rounded-xl border border-gray-100">
                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>

                    {{-- Draft: Submit --}}
                    @if($reimbursement->status === 'draft' && auth()->id() === $reimbursement->user_id)
                        <form method="POST" action="{{ route('reimbursements.submit', $reimbursement) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="return confirm('Are you sure you want to submit this reimbursement?')">
                                Submit for Approval
                            </button>
                        </form>
                    @endif

                    {{-- Submitted: PIC/Admin can approve --}}
                    @if($reimbursement->status === 'submitted' && (auth()->user()->isPicProject() || auth()->user()->isAdmin()))
                        <form method="POST" action="{{ route('reimbursements.approve', $reimbursement) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-900 to-cyan-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-blue-950 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="return confirm('Are you sure you want to approve this reimbursement?')">
                                Approve as PIC
                            </button>
                        </form>
                    @endif

                    {{-- Approved by PIC: Finance can approve --}}
                    @if($reimbursement->status === 'approved_by_pic' && (auth()->user()->isFinance() || auth()->user()->isAdmin()))
                        <form method="POST" action="{{ route('reimbursements.approve', $reimbursement) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="return confirm('Are you sure you want to approve this reimbursement?')">
                                Approve as Finance
                            </button>
                        </form>
                    @endif

                    {{-- Reject Button (for submitted or approved_by_pic, by authorized users) --}}
                    @if(in_array($reimbursement->status, ['submitted', 'approved_by_pic']) &&
                        (auth()->user()->isPicProject() || auth()->user()->isFinance() || auth()->user()->isAdmin()))
                        <div class="pt-4 border-t border-gray-200">
                            <form method="POST" action="{{ route('reimbursements.reject', $reimbursement) }}" class="space-y-3">
                                @csrf
                                <div>
                                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                                    <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                                              class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                              placeholder="Please provide a reason for rejection...">{{ old('rejection_reason') }}</textarea>
                                    @error('rejection_reason')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                        onclick="return confirm('Are you sure you want to reject this reimbursement?')">
                                    Reject
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Payment Evidence --}}
        @if($reimbursement->payment_evidence)
            <div class="bg-white overflow-hidden rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Evidence</h3>
                    <a href="{{ asset('storage/' . $reimbursement->payment_evidence) }}" target="_blank"
                       class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        View Payment Evidence
                    </a>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
