@extends('admin.layouts.app')

@php $edit = isset($coupon) && $coupon; @endphp

@section('content')
    <section class="max-w-4xl p-6 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800">
        <div class="flex flex-row justify-between">
            <h2 class="text-lg font-semibold text-gray-700 capitalize dark:text-white">
                {{ $edit ? 'Edit Coupon' : 'Add Coupon' }}
            </h2>
            <a href="{{ route('admin.coupons.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">
                <i class="fa-solid fa-backward"></i> Back
            </a>
        </div>

        <form action="{{ $edit ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
              method="POST"
              id="couponForm">
            @csrf
            @if($edit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="code">Coupon Code</label>
                    <input
                        id="code"
                        name="code"
                        type="text"
                        placeholder="E.g., SAVE20"
                        value="{{ old('code', $edit ? $coupon->code : '') }}"
                        class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring"
                    >
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="type">Coupon Type</label>
                    <select
                        id="type"
                        name="type"
                        class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring"
                    >
                        <option value="fixed" {{ old('type', $edit ? $coupon->type : '') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="percentage" {{ old('type', $edit ? $coupon->type : '') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    </select>
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="value">Discount Value</label>
                    <input
                        id="value"
                        name="value"
                        type="number"
                        step="0.01"
                        value="{{ old('value', $edit ? $coupon->value : '') }}"
                        class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring"
                    >
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="max_usage">Max Usage Limit</label>
                    <input
                        id="max_usage"
                        name="max_usage"
                        type="number"
                        value="{{ old('max_usage', $edit ? $coupon->max_usage : '') }}"
                        class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring"
                    >
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="expires_at">Expires At</label>
                    <input
                        id="expires_at"
                        name="expires_at"
                        type="date"
                        value="{{ old('expires_at', $edit ? ($coupon->expires_at ? \Carbon\Carbon::parse($coupon->expires_at)->format('Y-m-d') : '') : '') }}"
                        class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring"
                    >
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="is_active">Status</label>
                    <select
                        id="is_active"
                        name="is_active"
                        class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring"
                    >
                        <option value="1" {{ old('is_active', $edit ? $coupon->is_active : '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $edit ? $coupon->is_active : '1') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="px-8 py-2.5 leading-5 text-white transition-colors duration-300 transform bg-blue-500 rounded-lg hover:bg-blue-600 dark:hover:bg-gray-700 dark:bg-gray-900 focus:outline-none">
                    Save Coupon
                </button>
            </div>
        </form>
    </section>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $("#couponForm").validate({
                rules: {
                    code: {
                        required: true,
                        minlength: 2
                    },
                    type: {
                        required: true
                    },
                    value: {
                        required: true,
                        number: true,
                        min: 0.01
                    },
                    max_usage: {
                        required: true,
                        number: true,
                        min: 1
                    },
                    is_active: {
                        required: true
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: $(form).attr('action'),
                        type: $(form).attr('method'),
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.success === true)
                            {
                                Swal.fire({
                                    title: "Success",
                                    text: response.message,
                                    icon: "success"
                                }).
                                then(() => {
                                    window.location.href = "{{ route('admin.coupons.index') }}";
                                });
                            }
                        },
                        error: function(xhr) {
                            let msg = "Something went wrong!";
                            if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: msg,
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
