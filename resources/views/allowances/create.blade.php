<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">New Allowance</h2>
            <p class="text-sm text-gray-500 mt-0.5">Submit a new allowance claim</p>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">

        {{-- Important Notice --}}
        <div class="mb-6 rounded-xl bg-yellow-50 border border-yellow-100 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 6a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                    <p class="mt-1 text-sm text-yellow-700">
                        Allowance claim is only valid if there is a matching attendance entry for the selected date and project.
                    </p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <form method="POST" action="{{ route('allowances.store') }}" enctype="multipart/form-data">
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

                {{-- Date --}}
                <div class="mb-6">
                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" id="date" value="{{ old('date') }}"
                           class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                              placeholder="Describe the allowance claim...">{{ old('description') }}</textarea>
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
                </div>

                {{-- Receipt Upload --}}
                <div class="mb-6">
                    <label for="receipt" class="block text-sm font-medium text-gray-700">Receipt</label>
                    <input type="file" name="receipt" id="receipt" accept=".jpg,.jpeg,.png,.pdf"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-800 hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-gray-500">Accepted formats: JPG, PNG, PDF. Max 2MB.</p>
                    @error('receipt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('allowances.index') }}"
                       class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-200 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-900 to-cyan-600 text-white text-sm font-semibold rounded-lg hover:from-blue-950 hover:to-cyan-700 shadow-blue-900/25 transition shadow-sm">
                        Create Allowance
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
