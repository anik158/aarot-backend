@extends('admin.layouts.app')

@section('content')
    <div class="px-4 mx-auto max-w-5xl">
        <div class="sm:flex sm:items-center sm:justify-between mb-10">
            <div>
                <div class="flex items-center gap-x-3">
                    <h2 class="text-4xl font-black text-slate-900 tracking-tighter">Coupons</h2>
                    <span class="px-3 py-1 text-xs font-bold text-emerald-700 bg-emerald-100 rounded-full border border-emerald-200">
                        {{ $coupons->total() }} ACTIVE
                    </span>
                </div>
                <p class="mt-2 text-sm font-bold text-slate-500 uppercase tracking-widest">Promotion & Discount Strategy Center</p>
            </div>

            <div class="flex items-center mt-6 gap-x-3 sm:mt-0">
                <a href="{{ route('admin.coupons.create') }}"
                   class="flex items-center justify-center px-6 py-3 text-sm font-black text-white transition-all duration-300 bg-emerald-500 rounded-2xl shadow-xl shadow-emerald-500/20 hover:bg-emerald-600 hover:scale-[1.02] active:scale-[0.98] gap-x-2 uppercase tracking-widest">
                    <i class="fa-solid fa-ticket"></i> New Coupon
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
                <input type="text" id="search-input" value="{{ $search }}"
                       placeholder="Find campaign..."
                       class="block w-full py-4 pl-14 pr-6 text-slate-800 bg-white border border-slate-200 rounded-2xl focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/10 focus:outline-none transition-all duration-300 shadow-sm shadow-slate-200/50">
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
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-left">Secret Code</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-left">Type</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-left">Yield</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-left">Status</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-left">Expiration</th>
                                <th class="relative py-5 px-6"><span class="sr-only">Actions</span></th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-50">
                            @forelse($coupons as $item)
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    <td class="px-6 py-5 text-sm font-bold text-slate-700 whitespace-nowrap">
                                        {{ $coupons->firstItem() + $loop->index }}
                                    </td>
                                    <td class="px-6 py-5 text-sm whitespace-nowrap">
                                        <span class="px-3 py-1 bg-slate-900 text-emerald-400 font-mono text-xs font-black rounded-lg border border-slate-800 shadow-inner transition-transform inline-block uppercase tracking-wider">
                                            {{ $item->code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-sm font-bold text-slate-600 whitespace-nowrap capitalize">
                                        {{ $item->type }}
                                    </td>
                                    <td class="px-6 py-5 text-sm font-black text-slate-900 whitespace-nowrap">
                                        {{ $item->type == 'percentage' ? $item->value.'%' : '$'.number_format($item->value, 2) }}
                                    </td>
                                    <td class="px-6 py-5 text-sm whitespace-nowrap text-left">
                                        @if($item->is_active == '1')
                                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest text-emerald-700 bg-emerald-100 rounded-full border border-emerald-200">Operational</span>
                                        @else
                                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest text-red-700 bg-red-100 rounded-full border border-red-200">Void</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-sm font-bold text-slate-500 whitespace-nowrap">
                                        {{ $item->expires_at ? \Carbon\Carbon::parse($item->expires_at)->format('M d, Y') : 'UNLIMITED' }}
                                    </td>
                                    <td class="px-6 py-5 text-sm whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-x-4">
                                            <a href="{{ route('admin.coupons.edit', $item) }}"
                                               class="text-slate-900 hover:text-emerald-600 font-black transition-colors">Edit</a>
                                            <button type="button"
                                                    data-id="{{ $item->id }}"
                                                    class="text-red-500 hover:text-red-700 hover:cursor-pointer font-black delete-coupon-btn transition-colors">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-slate-400 font-black uppercase tracking-widest text-xs">No active campaigns</div>
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
