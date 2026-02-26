<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">New Project</h2>
            <p class="text-sm text-gray-500 mt-0.5">Create a new project</p>
        </div>
    </x-slot>

    <div class="max-w-3xl">

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
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
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <form method="POST" action="{{ route('projects.store') }}">
                @csrf

                {{-- Project No --}}
                <div class="mb-6">
                    <label for="project_no" class="block text-sm font-medium text-gray-700">Project No</label>
                    <input type="text" name="project_no" id="project_no" value="{{ old('project_no') }}"
                           placeholder="e.g. PRJ-001"
                           class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('project_no')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Project Name --}}
                <div class="mb-6">
                    <label for="project_name" class="block text-sm font-medium text-gray-700">Project Name</label>
                    <input type="text" name="project_name" id="project_name" value="{{ old('project_name') }}"
                           placeholder="Enter project name"
                           class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('project_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PIC Name --}}
                <div class="mb-6">
                    <label for="pic_name" class="block text-sm font-medium text-gray-700">PIC Name</label>
                    <input type="text" name="pic_name" id="pic_name" value="{{ old('pic_name') }}"
                           placeholder="Person in charge"
                           class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('pic_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alt PIC Name --}}
                <div class="mb-6">
                    <label for="alt_pic_name" class="block text-sm font-medium text-gray-700">Alt PIC Name <span class="text-gray-400">(Optional)</span></label>
                    <input type="text" name="alt_pic_name" id="alt_pic_name" value="{{ old('alt_pic_name') }}"
                           placeholder="Alternative person in charge"
                           class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('alt_pic_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('projects.index') }}"
                       class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-200 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                        Create Project
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
