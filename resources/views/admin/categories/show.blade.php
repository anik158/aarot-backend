@extends('admin.layouts.app')

@section('content')
    <section class="max-w-4xl p-6 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800">
        <div class="flex flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Category Details: {{ $category->name }}
            </h2>
            <div class="flex gap-x-3">
                <a href="{{ route('admin.categories.index') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <i class="fa-solid fa-backward"></i> Back
                </a>
                <a href="{{ route('admin.categories.edit', $category) }}"
                   class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Image -->
            <div class="lg:col-span-1">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}"
                         alt="{{ $category->name }}"
                         class="w-full rounded-lg shadow-md object-cover">
                @else
                    <div class="w-full h-64 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center border border-dashed border-gray-300 dark:border-gray-600">
                        <span class="text-gray-400 text-sm">No Image Available</span>
                    </div>
                @endif
            </div>

            <!-- Details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                        @if($category->status)
                            <span class="px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Active</span>
                        @else
                            <span class="px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">Inactive</span>
                        @endif
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Slug</p>
                    <p class="font-mono text-gray-700 dark:text-gray-300">{{ $category->slug }}</p>
                </div>

                @if($category->description)
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Description</p>
                        <div class="prose dark:prose-invert mt-2">
                            {!! nl2br(e($category->description)) !!}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>
@endsection
