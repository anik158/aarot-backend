@extends('admin.layouts.app')

@section('content')
    <section class="container px-4 mx-auto">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white">Coupons</h2>
                    <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400">
                    {{ $coupons->total() }} coupons
                </span>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">Manage your discount coupons.</p>
            </div>

            <div class="flex items-center mt-4 gap-x-3 ">
                <a href="{{ route('admin.coupons.create') }}"
                   class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-gray-700 dark:bg-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-gray-500 ">Add Coupon</span>
                </a>
            </div>
        </div>

        <!-- Live Search -->
        <div class="mt-6 md:flex md:items-center md:justify-between">
            <div class="relative flex items-center mt-4 md:mt-0">
            <span class="absolute">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-5 h-5 mx-3 text-gray-400 dark:text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </span>

                <input type="text"
                       id="search-input"
                       value="{{ $search }}"
                       placeholder="Search coupons..."
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
                                <th scope="col"
                                    class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    ID
                                </th>
                                <th scope="col"
                                    class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Code
                                </th>
                                <th scope="col"
                                    class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Type
                                </th>
                                <th scope="col"
                                    class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Value
                                </th>
                                <th scope="col"
                                    class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Expires At
                                </th>
                                <th scope="col" class="relative py-3.5 px-4">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                            @forelse($coupons as $item)
                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                        {{ $coupons->firstItem() + $loop->index }}
                                    </td>
                                    <td class="px-4 py-4 text-sm font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                        {{ $item->code }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-200 whitespace-nowrap capitalize">
                                        {{ $item->type }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                        {{ $item->type == 'percentage' ? $item->value.'%' : '$'.$item->value }}
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        @if($item->is_active == '1')
                                            <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Active</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                        {{ $item->expires_at ? \Carbon\Carbon::parse($item->expires_at)->format('M d, Y') : 'Never' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-x-4">
                                            <a href="{{ route('admin.coupons.edit', $item) }}"
                                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                                Edit
                                            </a>
                                            <button type="button"
                                                    data-id="{{ $item->id }}"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 delete-coupon-btn">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        No coupons found.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination & Results Info -->
        <div class="mt-6 sm:flex sm:items-center sm:justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                @if($coupons->total() > 0)
                    Showing <span class="font-medium text-gray-700 dark:text-gray-100">{{ $coupons->firstItem() }}</span>
                    to <span class="font-medium text-gray-700 dark:text-gray-100">{{ $coupons->lastItem() }}</span>
                    of <span class="font-medium text-gray-700 dark:text-gray-100">{{ $coupons->total() }}</span> results
                @else
                    No coupons found.
                @endif
            </div>

            <div class="flex items-center mt-4 gap-x-4 sm:mt-0">
                {!! $coupons->appends(request()->query())->links() !!}
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            // Live Search with debounce
            let timeout;
            $('#search-input').on('input', function () {
                clearTimeout(timeout);
                const query = $(this).val();

                timeout = setTimeout(() => {
                    const url = new URL(window.location);
                    if (query.trim()) {
                        url.searchParams.set('search', query.trim());
                    } else {
                        url.searchParams.delete('search');
                    }
                    window.location = url;
                }, 400);
            });

            // Delete with SweetAlert2 + AJAX
            $('.delete-coupon-btn').on('click', function () {
                const couponId = $(this).data('id');
                const deleteUrl = "{{ route('admin.coupons.destroy', ':id') }}".replace(':id', couponId);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        $.ajax({
                            url: deleteUrl,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: response.message || 'Coupon deleted successfully',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            },
                            error: function (xhr) {
                                let errorMsg = 'Something went wrong!';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                Swal.fire('Error!', errorMsg, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
