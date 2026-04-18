@extends('admin.layouts.app')

@section('content')
    <div class="px-4 mx-auto max-w-5xl">
        <div class="sm:flex sm:items-center sm:justify-between mb-10">
            <div>
                <div class="flex items-center gap-x-3">
                    <h2 class="text-4xl font-black text-slate-900 tracking-tighter">Market Sentiment</h2>
                    <span class="px-3 py-1 text-xs font-bold text-emerald-700 bg-emerald-100 rounded-full border border-emerald-200">
                        {{ $reviews->total() }} LOGS
                    </span>
                </div>
                <p class="mt-2 text-sm font-bold text-slate-500 uppercase tracking-widest">Customer Feedback & Product Authentication</p>
            </div>
        </div>

        <div class="flex flex-col mt-6">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden border border-slate-200 rounded-[2rem] bg-white shadow-2xl shadow-slate-200/50">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/50">
                            <tr>
                                <th class="py-5 px-6 text-xs font-black uppercase tracking-widest text-slate-400 text-left">Timestamp</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-left">User Profile</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-left">Target SKU</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-left">Narrative</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-center">Score</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-center">Metric</th>
                                <th class="relative py-5 px-6"><span class="sr-only">Actions</span></th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-50">
                            @forelse($reviews as $review)
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    <td class="px-6 py-5 text-[11px] font-bold text-slate-400 whitespace-nowrap uppercase tracking-tighter">
                                        {{ $review->created_at->format('M d, Y') }}<br>
                                        <span class="text-[9px] opacity-70">{{ $review->created_at->format('H:i') }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-sm whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-black text-xs border border-slate-200 shadow-sm">
                                                {{ substr($review->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <span class="font-black text-slate-900 tracking-tight">{{ $review->user->name ?? 'System Ghost' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-sm whitespace-nowrap">
                                        <div class="flex items-center gap-x-3">
                                            @if($review->product)
                                                <div class="p-1 bg-slate-50 rounded-lg border border-slate-200 shadow-sm shrink-0">
                                                    <img class="object-cover w-10 h-10 rounded-md" src="{{ asset($review->product->first_image) }}" alt="{{ $review->product->name }}">
                                                </div>
                                                <span class="max-w-[120px] truncate font-black text-slate-700 text-xs">{{ $review->product->name }}</span>
                                            @else
                                                <span class="text-slate-300 italic text-xs font-bold">REDACTED SKU</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-sm">
                                        <div class="max-w-xs">
                                            <p class="font-black text-slate-900 truncate tracking-tight">{{ $review->title }}</p>
                                            <p class="truncate text-[11px] text-slate-500 font-medium mt-0.5">{{ $review->body }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-sm whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-0.5">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="fa-solid fa-star text-[10px] {{ $i < $review->rating ? 'text-emerald-400' : 'text-slate-200' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-sm whitespace-nowrap text-center">
                                        @if($review->approved == \App\Models\Admin\Review::APPROVED)
                                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest text-emerald-700 bg-emerald-100 rounded-full border border-emerald-200 shadow-sm">Verified</span>
                                        @elseif($review->approved == \App\Models\Admin\Review::REJECTED)
                                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest text-red-700 bg-red-100 rounded-full border border-red-200 shadow-sm">Blocked</span>
                                        @else
                                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest text-slate-600 bg-slate-100 rounded-full border border-slate-200 shadow-sm">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-sm whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-x-3">
                                            @if($review->approved != \App\Models\Admin\Review::APPROVED)
                                                <form action="{{ route('admin.reviews.status', $review->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ \App\Models\Admin\Review::APPROVED }}">
                                                    <button type="submit" class="p-2 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-500 hover:text-white hover:cursor-pointer transition-all border border-emerald-100 shadow-sm shadow-emerald-100">
                                                        <i class="fa-solid fa-check text-xs"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($review->approved != \App\Models\Admin\Review::REJECTED)
                                                <form action="{{ route('admin.reviews.status', $review->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ \App\Models\Admin\Review::REJECTED }}">
                                                    <button type="submit" class="p-2 bg-slate-900 text-white rounded-xl hover:bg-red-600 hover:cursor-pointer transition-all shadow-lg shadow-slate-900/10">
                                                        <i class="fa-solid fa-ban text-xs"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <button type="button" data-id="{{ $review->id }}" class="p-2 text-slate-300 hover:text-red-500 hover:cursor-pointer transition-colors delete-review-btn">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-black uppercase tracking-widest text-xs">No feedback data recorded</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 sm:flex sm:items-center sm:justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                @if($reviews->total() > 0)
                    Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} results
                @else
                    No reviews found.
                @endif
            </div>
            <div class="flex items-center mt-4 gap-x-4 sm:mt-0">
                {!! $reviews->appends(request()->query())->links() !!}
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('.delete-review-btn').on('click', function () {
                const reviewId = $(this).data('id');
                const deleteUrl = "{{ route('admin.reviews.destroy', ':id') }}".replace(':id', reviewId);

                Swal.fire({
                    title: 'Delete Review?',
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
                                Swal.fire('Deleted!', 'Review has been removed.', 'success')
                                    .then(() => window.location.reload());
                            },
                            error: function () {
                                Swal.fire('Error!', 'Failed to delete review.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
