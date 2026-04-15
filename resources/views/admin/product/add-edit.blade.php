@extends('admin.layouts.app')

@php $edit = isset($product) && $product; @endphp

@section('content')
    <div class="px-4 mx-auto max-w-5xl">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-4xl font-black text-slate-900 tracking-tighter">
                    {{ $edit ? 'Edit Product' : 'New Product' }}
                </h2>
                <p class="mt-2 text-sm font-bold text-slate-500 uppercase tracking-widest">
                    {{ $edit ? 'Update your product listing and inventory.' : 'Register a new item in your digital catalog.' }}
                </p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 px-6 py-3 text-sm font-black text-slate-600 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 hover:text-slate-900 transition-all duration-300 shadow-sm shadow-slate-100">
                <i class="fa-solid fa-arrow-left"></i> Catalog
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden">
            <form action="{{ $edit ? route('admin.products.update', $product) : route('admin.products.store') }}"
                  method="POST"
                  id="productForm"
                  class="p-10"
                  enctype="multipart/form-data">
            @csrf
            @if($edit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                <!-- Category Selection -->
                <div class="col-span-1">
                    <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Department / Category</label>
                    <x-admin.custom-select 
                        name="category_id" 
                        id="category_id"
                        placeholder="Select Department..."
                        :selected="old('category_id', $edit ? $product->category_id : '')"
                        :options="$categories->pluck('name', 'id')->toArray()" />
                </div>

                <!-- Basic Details -->
                <div class="col-span-1">
                    <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Product Title</label>
                    <x-admin.input id="name" name="name" type="text" placeholder="Global Identifier..." 
                                   value="{{ old('name', $edit ? $product->name : '') }}" required />
                </div>

                <div class="col-span-1">
                    <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Inventory SKU / Slug</label>
                    <x-admin.input id="slug" name="slug" type="text" placeholder="url-identifier" 
                                   value="{{ old('slug', $edit ? $product->slug : '') }}" required />
                </div>

                <div class="col-span-1 md:col-span-1">
                    <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Yield Value / Price ($)</label>
                    <x-admin.input name="price" type="number" step="0.01" placeholder="0.00" 
                                   value="{{ old('price', $edit ? $product->price : '') }}" required />
                </div>

                <div class="col-span-1 md:col-span-1">
                    <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Batch Qty / Stock</label>
                    <x-admin.input name="qty" type="number" placeholder="Quantifiable amount..." 
                                   value="{{ old('qty', $edit ? $product->qty : '') }}" required />
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Content / Description</label>
                    <x-admin.textarea id="description" name="description" rows="4" placeholder="Detailed product exposition...">{{ old('description', $edit ? $product->description : '') }}</x-admin.textarea>
                </div>

                <div class="col-span-1 md:col-span-2 text-left">
                    <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Master Visual / Image</label>
                    <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl">
                        <input type="file" name="image" 
                               class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition-all cursor-pointer">
                        
                        @if($edit && $product->image)
                            <div class="mt-6 p-2 bg-white rounded-2xl border border-slate-200 w-fit shadow-lg shadow-slate-200/50">
                                <img src="{{ asset($product->image) }}" class="w-32 h-32 object-cover rounded-xl" alt="Current visual">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-span-1">
                    <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Metric Status</label>
                    <x-admin.custom-select 
                        name="status" 
                        :selected="old('status', $edit ? $product->status : '1')"
                        :options="['1' => 'Operational / Active', '0' => 'Suspended / Hidden']" />
                </div>
            </div>

            <!-- Attributes / Specifications -->
            <div class="mt-16 p-10 bg-slate-50/50 rounded-[2.5rem] border border-slate-100 shadow-inner">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Technical Specifications</h3>
                        <p class="mt-1 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Define dynamic attributes based on category protocols.</p>
                    </div>
                    <button type="button" id="add-attr-btn" class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white bg-slate-900 rounded-2xl hover:bg-emerald-500 shadow-xl shadow-slate-900/10 hover:shadow-emerald-500/20 transition-all active:scale-[0.98]">
                        <i class="fa-solid fa-plus-circle mr-2"></i> Add Specification
                    </button>
                </div>

                <div id="attributes-rows-container" class="space-y-6">
                    @if($edit)
                        @foreach($product->attributeValues->groupBy('attribute_id') as $attrId => $values)
                            @php $attr = $values->first()->attribute; @endphp
                            <div class="attribute-row p-8 bg-white rounded-[2rem] border border-slate-100 shadow-sm transition-all" data-attr-id="{{ $attrId }}">
                                <div class="flex justify-between items-center mb-6">
                                    <div class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em]">
                                        {{ $attr->name }}
                                    </div>
                                    <button type="button" class="remove-attr-btn text-red-500 hover:text-red-700 transition-colors">
                                        <i class="fa-solid fa-circle-xmark text-2xl"></i>
                                    </button>
                                </div>
                                <div class="flex flex-wrap gap-4">
                                    @foreach($attr->values as $v)
                                        <label class="inline-flex items-center cursor-pointer group bg-slate-50 px-5 py-2.5 rounded-xl border border-slate-100 hover:border-emerald-400 transition-all">
                                            <input type="checkbox" name="attribute_values[]" value="{{ $v->id }}"
                                                   @if($values->contains('id', $v->id)) checked @endif
                                                   class="w-4 h-4 rounded-lg border-slate-300 text-emerald-500 shadow-sm focus:ring-emerald-500/20 focus:ring-4">
                                            <span class="ml-3 text-sm font-bold text-slate-700 group-hover:text-emerald-600 transition-colors">{{ $v->value }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="flex justify-end mt-16 pt-10 border-t border-slate-100">
                <button type="submit" 
                        class="px-16 py-6 font-black uppercase tracking-[0.2em] text-white transition-all duration-300 transform bg-emerald-500 rounded-3xl shadow-2xl shadow-emerald-500/30 hover:bg-emerald-600 hover:scale-[1.02] active:scale-[0.98] outline-none">
                    {{ $edit ? 'Synchronize Record' : 'Commit to Ledger' }}
                </button>
            </div>
            </form>
        </div>
    </div>

    <!-- Premium Attribute Modal -->
    <div id="attribute-modal" class="hidden fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
            <!-- Background Overlay -->
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" id="modal-overlay"></div>
            
            <!-- Centering Trick -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <!-- Modal Content Wrapper -->
            <div class="relative z-50 inline-block w-full max-w-lg px-8 py-10 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[3rem] border border-slate-200 opacity-100">
                <div class="mb-8">
                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter mb-2">Protocol Selection</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Select relevant product specification</p>
                </div>
                
                <div class="space-y-8">
                    <div>
                        <label class="block mb-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Available Attributes</label>
                        <x-admin.custom-select 
                            name="modal-attribute-select" 
                            id="modal-attribute-select"
                            placeholder="Choose Specification..." />
                    </div>

                    <div class="flex items-center gap-4 pt-4 text-left">
                        <button type="button" id="modal-confirm-btn" class="flex-1 py-4 font-black uppercase tracking-widest text-white bg-emerald-500 rounded-2xl shadow-xl shadow-emerald-500/20 hover:bg-emerald-600 transition-all active:scale-[0.98]">
                            Link Spec
                        </button>
                        <button type="button" id="modal-close-btn" class="px-8 py-4 font-black uppercase tracking-widest text-slate-500 bg-slate-50 rounded-2xl hover:bg-slate-100 transition-all active:scale-[0.98]">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            let currentCategoryAttributes = [];
            
            // Listen for category selection change
            $('#category_id').on('change', function() {
                const categoryId = $(this).val();
                if (!categoryId) {
                    currentCategoryAttributes = [];
                    updateModalOptions();
                    return;
                }

                $.get(`/admin/categories/${categoryId}/attributes`, function(data) {
                    currentCategoryAttributes = data;
                    updateModalOptions();
                }).fail(function() {
                    console.error("Failed to load category attributes");
                });
            }).trigger('change');

            function updateModalOptions() {
                const options = {};
                currentCategoryAttributes.forEach(attr => {
                    options[attr.id] = attr.name;
                });
                
                // Dispatch event to Alpine component
                window.dispatchEvent(new CustomEvent('update-options-modal-attribute-select', { 
                    detail: options 
                }));
            }

            // Add Attribute Flow (Modal)
            $('#add-attr-btn').on('click', function() {
                if (currentCategoryAttributes.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Attributes Linked',
                        text: 'Please select a Category first, or ensure the category has linked attributes in Category Management.'
                    });
                    return;
                }
                $('#attribute-modal').removeClass('hidden');
            });

            $('#modal-close-btn, #modal-overlay').on('click', function() {
                $('#attribute-modal').addClass('hidden');
            });

            $('#modal-confirm-btn').on('click', function() {
                const attrId = $('#modal-attribute-select').val();
                if (!attrId) {
                    Swal.fire({ icon: 'error', title: 'Selection Required', text: 'Please pick an attribute.' });
                    return;
                }
                addAttributeRow(attrId);
                $('#attribute-modal').addClass('hidden');
                $('#modal-attribute-select').val(''); // Reset
            });

            function addAttributeRow(attrId) {
                const attr = currentCategoryAttributes.find(a => a.id == attrId);
                if (!attr) return;

                if ($(`.attribute-row[data-attr-id="${attr.id}"]`).length > 0) {
                    Swal.fire({ icon: 'info', title: 'Scope Conflict', text: `The "${attr.name}" protocol is already active for this product.` });
                    return;
                }

                let valuesHtml = '';
                attr.values.forEach(val => {
                    valuesHtml += `
                        <label class="inline-flex items-center cursor-pointer group bg-slate-50 px-5 py-2.5 rounded-xl border border-slate-100 hover:border-emerald-400 transition-all">
                            <input type="checkbox" name="attribute_values[]" value="${val.id}"
                                   class="w-4 h-4 rounded-lg border-slate-300 text-emerald-500 shadow-sm focus:ring-emerald-500/20 focus:ring-4">
                            <span class="ml-3 text-sm font-bold text-slate-700 group-hover:text-emerald-600 transition-colors">${val.value}</span>
                        </label>
                    `;
                });

                const html = `
                    <div class="attribute-row p-8 bg-white rounded-[2rem] border border-slate-100 shadow-sm animate-in fade-in transition-all" data-attr-id="${attr.id}">
                        <div class="flex justify-between items-center mb-6">
                            <div class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em]">
                                ${attr.name}
                            </div>
                            <button type="button" class="remove-attr-btn text-red-500 hover:text-red-700 transition-colors">
                                <i class="fa-solid fa-circle-xmark text-2xl"></i>
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-4">
                            ${valuesHtml}
                        </div>
                    </div>
                `;

                $('#attributes-rows-container').prepend($(html).hide().fadeIn(400));
            }

            $(document).on('click', '.remove-attr-btn', function() {
                $(this).closest('.attribute-row').fadeOut(300, function() { $(this).remove(); });
            });

            // Slug Sync
            $('#name').on('input', function() {
                let slug = $(this).val().toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
                $('#slug').val(slug);
            });

            $("#productForm").validate({
                rules: { name: "required", slug: "required", qty: { required: true, number: true }, price: { required: true, number: true }, status: "required" },
                submitHandler: function(form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: $(form).attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success === true) {
                                Swal.fire({ title: "Authorized", text: response.message, icon: "success" }).then(() => {
                                    window.location.href = "{{ route('admin.products.index') }}";
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = "Ledger rejection: Unexpected protocol error.";
                            if (xhr.status === 422) {
                                errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                            } else if (xhr.status === 413) {
                                errorMsg = "Payload exceeded: The visual asset is too massive for the buffer.";
                            }
                            Swal.fire({ icon: "error", title: "Oops...", html: errorMsg });
                        }
                    });
                }
            });
        });
    </script>
@endpush
