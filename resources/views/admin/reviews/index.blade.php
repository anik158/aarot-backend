@extends('admin.layouts.app')

@section('content')
    <section class="container px-4 mx-auto">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white">Product Reviews</h2>
                    <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400">
                    {{ $reviews->total() }} reviews
                </span>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">Manage and moderate product reviews from your customers.</p>
            </div>
        </div>

        <div class="flex flex-col mt-6">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="py-3.5 px-4 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Date</th>
                                <th class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Customer</th>
                                <th class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Product</th>
                                <th class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Review</th>
                                <th class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Rating</th>
                                <th class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Status</th>
                                <th class="relative py-3.5 px-4"><span class="sr-only">Actions</span></th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                            @forelse($reviews as $review)
                                <tr>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        {{ $review->created_at }}
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                        <div class="inline-flex items-center gap-x-3">
                                            <span>{{ $review->user->name ?? 'Deleted User' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                        <div class="flex items-center gap-x-2">
                                            @if($review->product)
                                                <img class="object-cover w-8 h-8 rounded-full shadow-sm" src="{{ asset($review->product->first_image) }}" alt="{{ $review->product->name }}">
                                                <span class="max-w-[150px] truncate font-medium text-white">{{ $review->product->name }}</span>
                                            @else
                                                <span class="text-gray-400 italic">Deleted Product</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <div class="max-w-xs overflow-hidden">
                                            <p class="font-semibold text-gray-800 dark:text-white truncate">{{ $review->title }}</p>
                                            <p class="truncate text-xs">{{ $review->body }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap text-center">
                                        <div class="flex items-center gap-x-1 text-yellow-400">
                                            @for($i = 0; $i < $review->rating; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        @if($review->approved == \App\Models\Admin\Review::APPROVED)
                                            <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Approved</span>
                                        @elseif($review->approved == \App\Models\Admin\Review::REJECTED)
                                            <span class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">Rejected</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-x-2">
                                            @if($review->approved != \App\Models\Admin\Review::APPROVED)
                                                <form action="{{ route('admin.reviews.status', $review->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ \App\Models\Admin\Review::APPROVED }}">
                                                    <button type="submit" class="text-blue-600 hover:cursor-pointer hover:text-blue-900 dark:text-blue-400">Approve</button>
                                                </form>
                                            @endif

                                            @if($review->approved != \App\Models\Admin\Review::REJECTED)
                                                <form action="{{ route('admin.reviews.status', $review->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ \App\Models\Admin\Review::REJECTED }}">
                                                    <button type="submit" class="text-orange-600 hover:cursor-pointer hover:text-orange-900 dark:text-orange-400">Reject</button>
                                                </form>
                                            @endif

                                            <button type="button" data-id="{{ $review->id }}" class="text-red-600 hover:text-red-900 dark:text-red-400 delete-review-btn">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        No reviews found.
                                    </td>
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
