<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">New Reimbursement</h2>
            <p class="text-sm text-gray-500 mt-0.5">Submit a new reimbursement claim</p>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                        <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form --}}
        <div class="bg-white overflow-hidden rounded-xl border border-gray-100 p-6"
             x-data="{
                 type: '{{ old('type', 'direct_claim') }}',
                 items: [{ date: '', category: '', description: '', amount: 0 }],
                 get total() { return this.items.reduce((sum, item) => sum + Number(item.amount || 0), 0) },
                 addItem() { this.items.push({ date: '', category: '', description: '', amount: 0 }) },
                 removeItem(index) { if(this.items.length > 1) this.items.splice(index, 1) }
             }">
            <form method="POST" action="{{ route('reimbursements.store') }}" enctype="multipart/form-data">
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

                {{-- Type --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center">
                            <input type="radio" name="type" value="direct_claim" x-model="type"
                                   class="rounded-full border-gray-300 text-blue-800 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Direct Claim</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="type" value="ca_settlement" x-model="type"
                                   class="rounded-full border-gray-300 text-blue-800 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">CA Settlement</span>
                        </label>
                    </div>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Outstanding Cash Advances (shown only for ca_settlement) --}}
                <div class="mb-6" x-show="type === 'ca_settlement'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Cash Advances to Settle</label>
                    @if($outstandingCas->count() > 0)
                        <div class="space-y-2 rounded-lg border border-gray-200 p-4 bg-gray-50">
                            @foreach($outstandingCas as $ca)
                                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100">
                                    <input type="checkbox" name="cash_advance_ids[]" value="{{ $ca->id }}"
                                           {{ in_array($ca->id, old('cash_advance_ids', [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-800 shadow-sm focus:ring-blue-500">
                                    <span class="text-sm text-gray-900 font-medium">{{ $ca->ca_number }}</span>
                                    <span class="text-sm text-gray-500">— Outstanding: Rp {{ number_format($ca->outstanding_amount, 0, ',', '.') }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">No outstanding cash advances available.</p>
                    @endif
                    @error('cash_advance_ids')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('cash_advance_ids.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                              placeholder="Describe the reimbursement purpose...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dynamic Items --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-gray-700">Reimbursement Items</label>
                        <button type="button" @click="addItem()"
                                class="inline-flex items-center px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-lg text-xs font-semibold text-blue-800 uppercase tracking-widest hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            + Add Item
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="rounded-lg border border-gray-200 p-4 bg-gray-50">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 items-start">
                                    {{-- Date --}}
                                    <div class="lg:col-span-2">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Date</label>
                                        <input type="date" :name="'items[' + index + '][date]'" x-model="item.date"
                                               class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    {{-- Category --}}
                                    <div class="lg:col-span-2">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Category</label>
                                        <select :name="'items[' + index + '][category]'" x-model="item.category"
                                                class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="">— Select —</option>
                                            @foreach($categories as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Description --}}
                                    <div class="lg:col-span-3">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Description</label>
                                        <input type="text" :name="'items[' + index + '][description]'" x-model="item.description"
                                               placeholder="Item description"
                                               class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    {{-- Amount --}}
                                    <div class="lg:col-span-2">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Amount (Rp)</label>
                                        <input type="number" :name="'items[' + index + '][amount]'" x-model="item.amount"
                                               min="0" step="1" placeholder="0"
                                               class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    {{-- Receipt --}}
                                    <div class="lg:col-span-2">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Receipt</label>
                                        <input type="file" :name="'items[' + index + '][receipt]'" accept=".jpg,.jpeg,.png,.pdf"
                                               class="block w-full text-sm text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-800 hover:file:bg-blue-100">
                                    </div>

                                    {{-- Remove --}}
                                    <div class="lg:col-span-1 flex items-end">
                                        <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                                class="inline-flex items-center p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition"
                                                title="Remove item">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Total --}}
                <div class="mb-6 rounded-lg bg-gray-100 p-4 flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Total Amount</span>
                    <span class="text-lg font-bold text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(total)"></span>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('reimbursements.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-900 to-cyan-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-blue-950 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Create Reimbursement
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
