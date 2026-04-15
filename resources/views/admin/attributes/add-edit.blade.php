@extends('admin.layouts.app')

@section('content')
    <div class="px-4 mx-auto max-w-7xl">
        <div class="mb-10">
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter">
                {{ isset($attribute) ? 'Edit Attribute' : 'New Attribute' }}
            </h2>
            <p class="mt-2 text-sm font-bold text-slate-500 uppercase tracking-widest">
                {{ isset($attribute) ? 'Modify specifications for your store.' : 'Establish a new dynamic product specification.' }}
            </p>
        </div>

        <form action="{{ isset($attribute) ? route('admin.attributes.update', $attribute) : route('admin.attributes.store') }}" method="POST">
            @csrf
            @if(isset($attribute))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <!-- General Info -->
                <div class="p-8 bg-white border border-slate-200 rounded-[2rem] shadow-2xl shadow-slate-200/50">
                    <h3 class="mb-6 text-xl font-black text-slate-900 tracking-tight">Configuration</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">
                                Attribute Name
                            </label>
                            <x-admin.input type="text" id="name" name="name" 
                                           value="{{ old('name', $attribute->name ?? '') }}"
                                           placeholder="e.g. Material, Storage, Fabric" :error="$errors->has('name')" required />
                            @error('name')
                                <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Attribute Values -->
                <div class="p-8 bg-white border border-slate-200 rounded-[2rem] shadow-2xl shadow-slate-200/50">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Available Options</h3>
                        <button type="button" id="add-value-btn" class="px-5 py-2 text-[10px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-xl hover:bg-emerald-100 transition-all duration-300">
                            + Add Option
                        </button>
                    </div>
                    
                    <div id="values-container" class="space-y-4">
                        @php $values = isset($attribute) ? $attribute->values : [null]; @endphp
                        @foreach($values as $value)
                            <div class="flex items-center gap-3 value-row group">
                                <x-admin.input type="text" name="values[]" value="{{ $value->value ?? '' }}" class="flex-1 !py-3" placeholder="e.g. Red, XL, 128GB" required />
                                <button type="button" class="p-3 text-red-500 bg-red-50 rounded-2xl hover:bg-red-100 transition-all duration-300 remove-value-btn opacity-40 group-hover:opacity-100">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-12 gap-x-4">
                <a href="{{ route('admin.attributes.index') }}" 
                   class="px-8 py-4 text-xs font-black uppercase tracking-widest text-slate-500 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 transition-all duration-300">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-10 py-5 text-sm font-black uppercase tracking-widest text-white bg-emerald-500 rounded-3xl shadow-2xl shadow-emerald-500/30 hover:bg-emerald-600 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                    {{ isset($attribute) ? 'Synchronize Protocol' : 'Initialize Protocol' }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        const rowClass = "block w-full px-5 py-3 text-slate-800 bg-slate-50 border border-slate-200 rounded-2xl focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/10 focus:outline-none transition-all duration-300";
        
        $('#add-value-btn').click(function() {
            var newRow = `
            <div class="flex items-center gap-3 value-row group">
                <input type="text" name="values[]" class="${rowClass} flex-1" placeholder="Enter option..." required>
                <button type="button" class="p-3 text-red-500 bg-red-50 rounded-2xl hover:bg-red-100 transition-all duration-300 remove-value-btn opacity-40 group-hover:opacity-100">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>`;
            $('#values-container').append($(newRow).hide().fadeIn(300));
        });

        $(document).on('click', '.remove-value-btn', function() {
            if ($('.value-row').length > 1) {
                $(this).closest('.value-row').fadeOut(200, function() { $(this).remove(); });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Integrity Warning',
                    text: 'A protocol must contain at least one valid specification value.'
                });
            }
        });
    });
</script>
@endpush
