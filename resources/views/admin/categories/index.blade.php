@extends('admin.layouts.app')

@section('content')
    <section class="container px-4 mx-auto">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white">Categories</h2>
                    <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400">
                    {{ $categories->total() }} categories
                </span>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">Manage your product categories.</p>
            </div>

            <div class="flex items-center mt-4 gap-x-3">
                <a href="{{ route('admin.categories.create') }}"
                   class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Add Category</span>
                </a>
            </div>
        </div>

        <!-- Search with proper debounce -->
        <div class="mt-6 md:flex md:items-center md:justify-between">
            <div class="relative flex items-center mt-4 md:mt-0">
            <span class="absolute">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mx-3 text-gray-400 dark:text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </span>
                <input type="text" id="search-input" value="{{ $search ?? '' }}"
                       placeholder="Search categories..."
                       class="block w-full py-1.5 pr-5 text-gray-700 bg-white border border-gray-200 rounded-lg md:w-80 placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
            </div>
        </div>

        <div class="flex flex-col mt-6">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="py-3.5 px-4 text-sm font-normal text-left text-gray-500 dark:text-gray-400">ID</th>
                                <th class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Name</th>
                                <th class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Slug</th>
                                <th class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Status</th>
                                <th class="relative py-3.5 px-4"><span class="sr-only">Actions</span></th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                            @forelse($categories as $category)
                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                        {{ $categories->firstItem() + $loop->index }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        <code>{{ $category->slug }}</code>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        @if($category->status)
                                            <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Active</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-x-4">
                                            <a href="{{ route('admin.categories.show', $category) }}"
                                               class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400">View</a>
                                            <a href="{{ route('admin.categories.edit', $category) }}"
                                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</a>
                                            <button type="button" data-id="{{ $category->id }}"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 delete-category-btn">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        No categories found.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 sm:flex sm:items-center sm:justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                @if($categories->total() > 0)
                    Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} results
                @else
                    No categories found.
                @endif
            </div>
            <div class="flex items-center mt-4 gap-x-4 sm:mt-0">
                {!! $categories->appends(request()->query())->links() !!}
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            // Proper debounced search
            let timeout;
            $('#search-input').on('input', function () {
                clearTimeout(timeout);
                const query = $(this).val().trim();

                timeout = setTimeout(() => {
                    const url = new URL(window.location.href);
                    if (query) {
                        url.searchParams.set('search', query);
                    } else {
                        url.searchParams.delete('search');
                    }
                    window.location.href = url.toString();
                }, 600); // 600ms debounce - feels responsive but not too fast
            });

            // Delete with SweetAlert
            $('.delete-category-btn').on('click', function () {
                const categoryId = $(this).data('id');
                const deleteUrl = "{{ route('admin.categories.destroy', ':id') }}".replace(':id', categoryId);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function () {
                                Swal.fire('Deleted!', 'Category has been deleted.', 'success')
                                    .then(() => window.location.reload());
                            },
                            error: function () {
                                Swal.fire('Error!', 'Failed to delete category.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
