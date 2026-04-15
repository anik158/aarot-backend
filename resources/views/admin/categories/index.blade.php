@extends('admin.layouts.app')

@section('content')
    <section class="container px-4 mx-auto">
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <div class="flex items-center gap-x-3">
                    <h2 class="text-3xl font-black tracking-tight text-slate-900">Categories</h2>
                    <span class="px-3 py-1 text-xs font-bold text-emerald-700 bg-emerald-100 rounded-full">
                        {{ $categories->total() }} Total
                    </span>
                </div>
                <p class="mt-2 text-sm font-medium text-slate-500">Organize your products into departments and collections.</p>
            </div>

            <div class="flex items-center mt-4 gap-x-3">
                <a href="{{ route('admin.categories.create') }}"
                   class="flex items-center justify-center px-6 py-3 text-sm font-bold text-white transition-all duration-300 bg-emerald-500 rounded-2xl shadow-xl shadow-emerald-500/20 hover:bg-emerald-600 hover:scale-[1.02] active:scale-[0.98] gap-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>New Category</span>
                </a>
            </div>
        </div>

        <!-- Search with premium styling -->
        <div class="mt-8 flex items-center justify-between">
            <div class="relative flex items-center w-full max-w-md">
                <span class="absolute left-5 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </span>
                <input type="text" id="search-input" value="{{ $search ?? '' }}"
                       placeholder="Search collections..."
                       class="block w-full py-4 pl-14 pr-6 text-slate-700 bg-white border border-slate-200 rounded-2xl focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/10 focus:outline-none transition-all duration-300 shadow-sm border-slate-200 shadow-slate-200/50">
            </div>
        </div>

        <div class="flex flex-col mt-10">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden border border-slate-200 rounded-[2rem] bg-white shadow-2xl shadow-slate-200/50">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/50">
                            <tr>
                                <th class="py-5 px-6 text-xs font-black uppercase tracking-widest text-slate-400 text-left">ID</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-left">Name</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-left">Slug</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-left">Status</th>
                                <th class="relative py-5 px-6"><span class="sr-only">Actions</span></th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-50">
                            @forelse($categories as $category)
                                <tr>
                                    <td class="px-6 py-5 text-sm font-bold text-slate-700 whitespace-nowrap">
                                        {{ $categories->firstItem() + $loop->index }}
                                    </td>
                                    <td class="px-6 py-5 text-sm font-black text-slate-800 whitespace-nowrap">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-5 text-sm text-slate-500 font-medium whitespace-nowrap">
                                        <span class="bg-slate-100 px-3 py-1 rounded-lg border border-slate-200 font-mono text-xs uppercase tracking-tighter">{{ $category->slug }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-sm whitespace-nowrap">
                                        @if($category->status)
                                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest text-emerald-700 bg-emerald-100 rounded-full border border-emerald-200">Active</span>
                                        @else
                                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest text-red-700 bg-red-100 rounded-full border border-red-200">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-sm whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-x-4">
                                            <a href="{{ route('admin.categories.show', $category) }}"
                                               class="text-emerald-500 hover:text-emerald-700 font-black transition-colors">View</a>
                                            <a href="{{ route('admin.categories.edit', $category) }}"
                                               class="text-slate-900 hover:text-emerald-600 font-black transition-colors">Edit</a>
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
