<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Cash Advance: {{ $cashAdvance->ca_number }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">View cash advance details and actions</p>
            </div>
            <a href="{{ route('cash-advances.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl space-y-6">

        {{-- Detail Card --}}
        <div class="bg-white rounded-xl border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Cash Advance Details</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $cashAdvance->status_badge }}">
                        {{ $cashAdvance->status_label }}
                    </span>
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">CA Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $cashAdvance->ca_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">User</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $cashAdvance->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Project</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $cashAdvance->project->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $cashAdvance->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($cashAdvance->amount, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Outstanding Amount</dt>
                        <dd class="mt-1 text-lg font-semibold {{ $cashAdvance->outstanding_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                            Rp {{ number_format($cashAdvance->outstanding_amount, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Settled Amount</dt>
                        <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($cashAdvance->settled_amount, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $cashAdvance->description ?? '-' }}</dd>
                    </div>
                </dl>

                {{-- Transfer Evidence --}}
                @if($cashAdvance->transfer_evidence)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Transfer Evidence</h4>
                        <div class="flex items-center gap-4">
                            <a href="{{ asset('storage/' . $cashAdvance->transfer_evidence) }}" target="_blank"
                               class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                View Transfer Evidence
                            </a>
                            @if($cashAdvance->transfer_date)
                                <span class="text-sm text-gray-500">Transfer Date: {{ $cashAdvance->transfer_date->format('d M Y') }}</span>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Rejection Reason --}}
                @if($cashAdvance->status === 'rejected' && $cashAdvance->rejection_reason)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                            <h4 class="text-sm font-medium text-red-800 mb-1">Rejection Reason</h4>
                            <p class="text-sm text-red-700">{{ $cashAdvance->rejection_reason }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Approval Timeline --}}
        <div class="bg-white rounded-xl border border-gray-100">
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
                        <p class="text-xs text-gray-500">{{ $cashAdvance->created_at->format('d M Y, H:i') }} by {{ $cashAdvance->user->name }}</p>
                    </li>

                    {{-- Approved by PIC --}}
                    @if($cashAdvance->approved_by_pic_at)
                        <li class="ml-6">
                            <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-indigo-100 ring-8 ring-white">
                                <svg class="h-3 w-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <h4 class="text-sm font-semibold text-gray-900">Approved by PIC</h4>
                            <p class="text-xs text-gray-500">{{ $cashAdvance->approved_by_pic_at->format('d M Y, H:i') }} by {{ $cashAdvance->approvedByPic->name ?? '-' }}</p>
                        </li>
                    @endif

                    {{-- Approved by Finance --}}
                    @if($cashAdvance->approved_by_finance_at)
                        <li class="ml-6">
                            <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-green-100 ring-8 ring-white">
                                <svg class="h-3 w-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <h4 class="text-sm font-semibold text-gray-900">Approved by Finance</h4>
                            <p class="text-xs text-gray-500">{{ $cashAdvance->approved_by_finance_at->format('d M Y, H:i') }} by {{ $cashAdvance->approvedByFinance->name ?? '-' }}</p>
                        </li>
                    @endif

                    {{-- Rejected --}}
                    @if($cashAdvance->status === 'rejected')
                        <li class="ml-6">
                            <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-red-100 ring-8 ring-white">
                                <svg class="h-3 w-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <h4 class="text-sm font-semibold text-red-600">Rejected</h4>
                            <p class="text-xs text-gray-500">{{ $cashAdvance->updated_at->format('d M Y, H:i') }}</p>
                        </li>
                    @endif
                </ol>
            </div>
        </div>

        {{-- Action Buttons --}}
        @if(!in_array($cashAdvance->status, ['fully_settled', 'rejected']))
            <div class="bg-white rounded-xl border border-gray-100">
                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>

                    {{-- Draft: Submit --}}
                    @if($cashAdvance->status === 'draft' && auth()->id() === $cashAdvance->user_id)
                        <form method="POST" action="{{ route('cash-advances.submit', $cashAdvance) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm"
                                    onclick="return confirm('Are you sure you want to submit this cash advance?')">
                                Submit for Approval
                            </button>
                        </form>
                    @endif

                    {{-- Submitted: PIC/Admin can approve --}}
                    @if($cashAdvance->status === 'submitted' && (auth()->user()->isPicProject() || auth()->user()->isAdmin()))
                        <form method="POST" action="{{ route('cash-advances.approve', $cashAdvance) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm"
                                    onclick="return confirm('Are you sure you want to approve this cash advance?')">
                                Approve as PIC
                            </button>
                        </form>
                    @endif

                    {{-- Approved by PIC: Finance can approve with transfer evidence --}}
                    @if($cashAdvance->status === 'approved_by_pic' && (auth()->user()->isFinance() || auth()->user()->isAdmin()))
                        <form method="POST" action="{{ route('cash-advances.approve', $cashAdvance) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="transfer_evidence" class="block text-sm font-medium text-gray-700">Transfer Evidence</label>
                                    <input type="file" name="transfer_evidence" id="transfer_evidence" accept=".jpg,.jpeg,.png,.pdf"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    @error('transfer_evidence')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="transfer_date" class="block text-sm font-medium text-gray-700">Transfer Date</label>
                                    <input type="date" name="transfer_date" id="transfer_date" value="{{ old('transfer_date', now()->format('Y-m-d')) }}"
                                           class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('transfer_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition shadow-sm"
                                    onclick="return confirm('Are you sure you want to approve and process the transfer?')">
                                Approve &amp; Process Transfer
                            </button>
                        </form>
                    @endif

                    {{-- Reject Button (for submitted or approved_by_pic, by authorized users) --}}
                    @if(in_array($cashAdvance->status, ['submitted', 'approved_by_pic']) &&
                        (auth()->user()->isPicProject() || auth()->user()->isFinance() || auth()->user()->isAdmin()))
                        <div class="pt-4 border-t border-gray-200">
                            <form method="POST" action="{{ route('cash-advances.reject', $cashAdvance) }}" class="space-y-3">
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
                                        class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition shadow-sm"
                                        onclick="return confirm('Are you sure you want to reject this cash advance?')">
                                    Reject
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Linked Reimbursements / Settlements --}}
        @if($cashAdvance->reimbursements->count() > 0)
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Linked Reimbursements / Settlements</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reimbursement #</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Settled Amount</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($cashAdvance->reimbursements as $reimbursement)
                                    <tr class="table-row-hover">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <a href="{{ route('reimbursements.show', $reimbursement) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                {{ $reimbursement->reimbursement_number }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right">
                                            Rp {{ number_format($reimbursement->pivot->settled_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reimbursement->status_badge ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $reimbursement->status_label ?? ucfirst($reimbursement->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $reimbursement->created_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
