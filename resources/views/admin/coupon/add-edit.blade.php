@extends('admin.layouts.app')

@php $edit = isset($coupon) && $coupon; @endphp

@section('content')
    <div class="px-4 mx-auto max-w-5xl">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-4xl font-black text-slate-900 tracking-tighter">
                    {{ $edit ? 'Edit Coupon' : 'Generate Coupon' }}
                </h2>
                <p class="mt-2 text-sm font-bold text-slate-500 uppercase tracking-widest">
                    {{ $edit ? 'Synchronize promotional logic and metrics.' : 'Configure a new market discount protocol.' }}
                </p>
            </div>
            <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-2 px-6 py-3 text-sm font-black text-slate-600 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 hover:text-slate-900 transition-all duration-300 shadow-sm shadow-slate-100">
                <i class="fa-solid fa-arrow-left"></i> Registry
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden">
            <form action="{{ $edit ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
                  method="POST"
                  id="couponForm"
                  class="p-10">
                @csrf
                @if($edit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Coupon Secret Code</label>
                        <x-admin.input id="code" name="code" type="text" placeholder="E.G. SAVE20"
                               class="font-mono font-black uppercase"
                               value="{{ old('code', $edit ? $coupon->code : '') }}" required />
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Coupon Logic Type</label>
                        <x-admin.custom-select 
                            name="type" 
                            id="type"
                            :selected="old('type', $edit ? $coupon->type : 'fixed')"
                            :options="['fixed' => 'Monetary / Fixed Amount', 'percentage' => 'Ratio / Percentage (%)']" />
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Yield / Discount Value</label>
                        <x-admin.input id="value" name="value" type="number" step="0.01" placeholder="0.00"
                               value="{{ old('value', $edit ? $coupon->value : '') }}" required />
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Protocol Usage Limit</label>
                        <x-admin.input id="max_usage" name="max_usage" type="number" placeholder="Total allocations..."
                               value="{{ old('max_usage', $edit ? $coupon->max_usage : '') }}" required />
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Expiration Date</label>
                        <x-admin.input id="expires_at" name="expires_at" type="date"
                               value="{{ old('expires_at', $edit ? ($coupon->expires_at ? \Carbon\Carbon::parse($coupon->expires_at)->format('Y-m-d') : '') : '') }}" />
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Status Protocol</label>
                        <x-admin.custom-select 
                            name="is_active" 
                            :selected="old('is_active', $edit ? $coupon->is_active : '1')"
                            :options="['1' => 'Operational / Active', '0' => 'Suspended / Inactive']" />
                    </div>
                </div>

                <div class="flex justify-end mt-12 pt-8 border-t border-slate-100">
                    <button type="submit"
                            class="px-12 py-5 font-black uppercase tracking-[0.2em] text-white transition-all duration-300 transform bg-emerald-500 rounded-3xl shadow-2xl shadow-emerald-500/30 hover:bg-emerald-600 hover:scale-[1.02] active:scale-[0.98] outline-none">
                        {{ $edit ? 'Commit Sync' : 'Initialize Yield' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
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
