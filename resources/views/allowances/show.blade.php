<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Allowance: {{ $allowance->allowance_number }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">View allowance claim details</p>
            </div>
            <a href="{{ route('allowances.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Detail Card --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Allowance Details</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $allowance->status_badge }}">
                        {{ $allowance->status_label }}
                    </span>
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Allowance Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $allowance->allowance_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">User</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $allowance->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Project</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $allowance->project->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $allowance->date->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($allowance->amount, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $allowance->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $allowance->description ?? '-' }}</dd>
                    </div>
                </dl>

                {{-- Receipt --}}
                @if($allowance->receipt)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Receipt</h4>
                        <div class="flex items-center gap-4">
                            @if(in_array(pathinfo($allowance->receipt, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                <a href="{{ asset('storage/' . $allowance->receipt) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $allowance->receipt) }}" alt="Receipt" class="max-w-xs rounded-lg border border-gray-200 shadow-sm">
                                </a>
                            @else
                                <a href="{{ asset('storage/' . $allowance->receipt) }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 text-sm font-semibold text-gray-700 rounded-lg bg-white hover:bg-gray-50 transition">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    View Receipt
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Rejection Reason --}}
                @if($allowance->status === 'rejected' && $allowance->rejection_reason)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="rounded-lg bg-red-50 border border-red-100 p-4">
                            <h4 class="text-sm font-medium text-red-800 mb-1">Rejection Reason</h4>
                            <p class="text-sm text-red-700">{{ $allowance->rejection_reason }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Approval Timeline --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Approval Flow (Admin &rarr; PIC &rarr; Finance)</h3>
                <ol class="relative border-l border-gray-200 ml-3 space-y-6">
                    {{-- Created --}}
                    <li class="ml-6">
                        <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 ring-8 ring-white">
                            <svg class="h-3 w-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.59l-1.95 1.1a.75.75 0 10.74 1.3l2.25-1.25a.75.75 0 00.46-.69V6.75z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        <h4 class="text-sm font-semibold text-gray-900">Created</h4>
                        <p class="text-xs text-gray-500">{{ $allowance->created_at->format('d M Y, H:i') }} by {{ $allowance->user->name }}</p>
                    </li>

                    {{-- Approved by Admin --}}
                    <li class="ml-6">
                        <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full {{ $allowance->approved_by_admin_at ? 'bg-blue-100' : 'bg-gray-50' }} ring-8 ring-white">
                            @if($allowance->approved_by_admin_at)
                                <svg class="h-3 w-3 text-blue-800" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="h-3 w-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </span>
                        <h4 class="text-sm font-semibold {{ $allowance->approved_by_admin_at ? 'text-gray-900' : 'text-gray-400' }}">Approved by Admin</h4>
                        @if($allowance->approved_by_admin_at)
                            <p class="text-xs text-gray-500">{{ $allowance->approved_by_admin_at->format('d M Y, H:i') }} by {{ $allowance->approvedByAdmin->name ?? '-' }}</p>
                        @else
                            <p class="text-xs text-gray-400">Pending</p>
                        @endif
                    </li>

                    {{-- Approved by PIC --}}
                    <li class="ml-6">
                        <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full {{ $allowance->approved_by_pic_at ? 'bg-cyan-100' : 'bg-gray-50' }} ring-8 ring-white">
                            @if($allowance->approved_by_pic_at)
                                <svg class="h-3 w-3 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="h-3 w-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </span>
                        <h4 class="text-sm font-semibold {{ $allowance->approved_by_pic_at ? 'text-gray-900' : 'text-gray-400' }}">Approved by PIC</h4>
                        @if($allowance->approved_by_pic_at)
                            <p class="text-xs text-gray-500">{{ $allowance->approved_by_pic_at->format('d M Y, H:i') }} by {{ $allowance->approvedByPic->name ?? '-' }}</p>
                        @else
                            <p class="text-xs text-gray-400">Pending</p>
                        @endif
                    </li>

                    {{-- Approved by Finance --}}
                    <li class="ml-6">
                        <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full {{ $allowance->approved_by_finance_at ? 'bg-green-100' : 'bg-gray-50' }} ring-8 ring-white">
                            @if($allowance->approved_by_finance_at)
                                <svg class="h-3 w-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="h-3 w-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </span>
                        <h4 class="text-sm font-semibold {{ $allowance->approved_by_finance_at ? 'text-gray-900' : 'text-gray-400' }}">Approved by Finance</h4>
                        @if($allowance->approved_by_finance_at)
                            <p class="text-xs text-gray-500">{{ $allowance->approved_by_finance_at->format('d M Y, H:i') }} by {{ $allowance->approvedByFinance->name ?? '-' }}</p>
                        @else
                            <p class="text-xs text-gray-400">Pending</p>
                        @endif
                    </li>

                    {{-- Rejected --}}
                    @if($allowance->status === 'rejected')
                        <li class="ml-6">
                            <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-red-100 ring-8 ring-white">
                                <svg class="h-3 w-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <h4 class="text-sm font-semibold text-red-600">Rejected</h4>
                            <p class="text-xs text-gray-500">{{ $allowance->updated_at->format('d M Y, H:i') }}</p>
                        </li>
                    @endif
                </ol>
            </div>
        </div>

        {{-- Action Buttons --}}
        @if(!in_array($allowance->status, ['approved_by_finance', 'rejected']))
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>

                    {{-- Draft: Owner can submit --}}
                    @if($allowance->status === 'draft' && auth()->id() === $allowance->user_id)
                        <form method="POST" action="{{ route('allowances.submit', $allowance) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm"
                                    onclick="return confirm('Are you sure you want to submit this allowance?')">
                                Submit for Approval
                            </button>
                        </form>
                    @endif

                    {{-- Submitted: Admin can approve --}}
                    @if($allowance->status === 'submitted' && auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('allowances.approve', $allowance) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-900 to-cyan-600 text-white text-sm font-semibold rounded-lg hover:from-blue-950 hover:to-cyan-700 shadow-blue-900/25 transition shadow-sm"
                                    onclick="return confirm('Are you sure you want to approve this allowance as Admin?')">
                                Approve as Admin
                            </button>
                        </form>
                    @endif

                    {{-- Approved by Admin: PIC can approve --}}
                    @if($allowance->status === 'approved_by_admin' && auth()->user()->isPicProject())
                        <form method="POST" action="{{ route('allowances.approve', $allowance) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2.5 bg-cyan-600 text-white text-sm font-semibold rounded-lg hover:bg-cyan-700 transition shadow-sm"
                                    onclick="return confirm('Are you sure you want to approve this allowance as PIC?')">
                                Approve as PIC
                            </button>
                        </form>
                    @endif

                    {{-- Approved by PIC: Finance can approve --}}
                    @if($allowance->status === 'approved_by_pic' && auth()->user()->isFinance())
                        <form method="POST" action="{{ route('allowances.approve', $allowance) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition shadow-sm"
                                    onclick="return confirm('Are you sure you want to approve this allowance as Finance?')">
                                Approve as Finance
                            </button>
                        </form>
                    @endif

                    {{-- Reject Button (for submitted, approved_by_admin, approved_by_pic) --}}
                    @if(in_array($allowance->status, ['submitted', 'approved_by_admin', 'approved_by_pic']) &&
                        (auth()->user()->isAdmin() || auth()->user()->isPicProject() || auth()->user()->isFinance()))
                        <div class="pt-4 border-t border-gray-200">
                            <form method="POST" action="{{ route('allowances.reject', $allowance) }}" class="space-y-3">
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
                                        onclick="return confirm('Are you sure you want to reject this allowance?')">
                                    Reject
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
