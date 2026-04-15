@extends('admin.layouts.app')

@section('content')
    <section class="container px-4 mx-auto">
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <div class="flex items-center gap-x-3">
                    <h2 class="text-3xl font-black tracking-tight text-slate-900">Attributes</h2>
                    <span class="px-3 py-1 text-xs font-bold text-emerald-700 bg-emerald-100 rounded-full">
                        {{ $attributes->total() }} Total
                    </span>
                </div>
                <p class="mt-2 text-sm font-medium text-slate-500">Define and manage product specifications like Color, Size, and Technical Specs.</p>
            </div>

            <div class="flex items-center mt-4 gap-x-3">
                <a href="{{ route('admin.attributes.create') }}"
                   class="flex items-center justify-center px-6 py-3 text-sm font-bold text-white transition-all duration-300 bg-emerald-500 rounded-2xl shadow-xl shadow-emerald-500/20 hover:bg-emerald-600 hover:scale-[1.02] active:scale-[0.98] gap-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>New Attribute</span>
                </a>
            </div>
        </div>

        <div class="flex flex-col mt-6">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden border border-slate-200 rounded-[2rem] bg-white shadow-xl shadow-slate-200/50">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/50">
                            <tr>
                                <th class="py-5 px-6 text-xs font-black uppercase tracking-widest text-slate-400 text-left">ID</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-left">Name</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-slate-400 text-left">Values</th>
                                <th class="relative py-5 px-6"><span class="sr-only">Actions</span></th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-50">
                            @forelse($attributes as $attribute)
                                <tr>
                                    <td class="px-6 py-5 text-sm font-bold text-slate-700 whitespace-nowrap">
                                        {{ $attributes->firstItem() + $loop->index }}
                                    </td>
                                    <td class="px-6 py-5 text-sm font-black text-slate-800 whitespace-nowrap">
                                        {{ $attribute->name }}
                                    </td>
                                    <td class="px-6 py-5 text-sm text-slate-500 font-medium">
                                        @foreach($attribute->values as $value)
                                            <span class="inline-block px-3 py-1 text-[11px] font-bold mb-1 mr-1 text-emerald-600 bg-emerald-50 rounded-lg border border-emerald-100 uppercase tracking-wider">{{ $value->value }}</span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-5 text-sm whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-x-4">
                                            <a href="{{ route('admin.attributes.edit', $attribute) }}"
                                               class="text-emerald-500 hover:text-emerald-700 font-black transition-colors">Edit</a>
                                            <button type="button" data-id="{{ $attribute->id }}"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 delete-attribute-btn">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        No attributes found.
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
                @if($attributes->total() > 0)
                    Showing {{ $attributes->firstItem() }} to {{ $attributes->lastItem() }} of {{ $attributes->total() }} results
                @else
                    No attributes found.
                @endif
            </div>
            <div class="flex items-center mt-4 gap-x-4 sm:mt-0">
                {!! $attributes->appends(request()->query())->links() !!}
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            // Delete with SweetAlert
            $('.delete-attribute-btn').on('click', function () {
                const attributeId = $(this).data('id');
                const deleteUrl = "{{ route('admin.attributes.destroy', ':id') }}".replace(':id', attributeId);

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
                                Swal.fire('Deleted!', 'Attribute has been deleted.', 'success')
                                    .then(() => window.location.reload());
                            },
                            error: function () {
                                Swal.fire('Error!', 'Failed to delete attribute.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
