<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">New Cash Advance</h2>
            <p class="text-sm text-gray-500 mt-0.5">Submit a new cash advance request</p>
        </div>
    </x-slot>

    <div class="max-w-3xl">

        {{-- Outstanding Limit Warning --}}
        @php
            $outstanding = auth()->user()->totalOutstandingCashAdvance();
            $limit = 15000000;
            $remaining = max(0, $limit - $outstanding);
        @endphp
        <div class="mb-6 rounded-xl border {{ $outstanding >= $limit ? 'border-red-200 bg-red-50' : 'border-blue-200 bg-blue-50' }} p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    @if($outstanding >= $limit)
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium {{ $outstanding >= $limit ? 'text-red-800' : 'text-blue-800' }}">
                        Cash Advance Limit Information
                    </h3>
                    <div class="mt-2 text-sm {{ $outstanding >= $limit ? 'text-red-700' : 'text-blue-700' }}">
                        <p>Current Outstanding: <strong>Rp {{ number_format($outstanding, 0, ',', '.') }}</strong></p>
                        <p>Maximum Limit: <strong>Rp {{ number_format($limit, 0, ',', '.') }}</strong></p>
                        <p>Remaining Allowance: <strong>Rp {{ number_format($remaining, 0, ',', '.') }}</strong></p>
                        @if($outstanding >= $limit)
                            <p class="mt-1 font-semibold">You have reached your cash advance limit. Please settle existing advances first.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <form method="POST" action="{{ route('cash-advances.store') }}">
                @csrf

                {{-- Project --}}
                <div class="mb-6">
                    <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
                    <select name="project_id" id="project_id"
                            class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">— Select Project —</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                              placeholder="Describe the purpose of this cash advance...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Amount --}}
                <div class="mb-6">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount (Rp)</label>
                    <div class="relative mt-1">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}"
                               min="0" step="1"
                               class="block w-full rounded-lg border-gray-200 pl-10 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                               placeholder="0">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($remaining < $limit)
                        <p class="mt-1 text-xs text-gray-500">Maximum amount you can request: Rp {{ number_format($remaining, 0, ',', '.') }}</p>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('cash-advances.index') }}"
                       class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-200 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-900 to-cyan-600 text-white text-sm font-semibold rounded-lg hover:from-blue-950 hover:to-cyan-700 shadow-blue-900/25 transition shadow-sm">
                        Create Cash Advance
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
