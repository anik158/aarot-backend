@extends('admin.layouts.app')

@php
    $edit = isset($category) && $category;
@endphp

@section('content')
    <div class="px-4 mx-auto max-w-5xl">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-4xl font-black text-slate-900 tracking-tighter">
                    {{ $edit ? 'Edit Category' : 'New Category' }}
                </h2>
                <p class="mt-2 text-sm font-bold text-slate-500 uppercase tracking-widest">
                    {{ $edit ? 'Modify your department details and specifications.' : 'Initialize a new product department.' }}
                </p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-6 py-3 text-sm font-black text-slate-600 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 hover:text-slate-900 transition-all duration-300 shadow-sm shadow-slate-100">
                <i class="fa-solid fa-arrow-left"></i> Collections
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden">
            <form action="{{ $edit ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
                  method="POST"
                  id="categoryForm"
                  class="p-10"
                  enctype="multipart/form-data">

            @csrf
            @if($edit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-8 mt-6">
                <div>
                    <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Category Full Name</label>
                    <x-admin.input id="name" name="name" type="text" placeholder="Department title..." 
                                   value="{{ old('name', $edit ? $category->name : '') }}" required />
                </div>

                <div>
                    <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">URL Slug / Identifier</label>
                    <x-admin.input id="slug" name="slug" type="text" placeholder="url-slug" 
                                   value="{{ old('slug', $edit ? $category->slug : '') }}" required />
                </div>

                <div>
                    <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Exposition / Description</label>
                    <x-admin.textarea id="description" name="description" rows="4" placeholder="Brief department overview...">{{ old('description', $edit ? $category->description : '') }}</x-admin.textarea>
                </div>

                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-500">Master Visual / Icon</label>
                    <input id="image" name="image" type="file"
                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition-all cursor-pointer">

                    @if($edit && $category->image)
                        <div class="mt-6 p-2 bg-white rounded-2xl border border-slate-200 w-fit shadow-lg shadow-slate-200/50">
                            <img src="{{ asset($category->image) }}" alt="Current Image" class="w-32 h-32 object-cover rounded-xl">
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block mb-2 text-xs font-black uppercase tracking-widest text-slate-400">Collection Status</label>
                    <x-admin.custom-select 
                        name="status" 
                        :selected="old('status', $edit ? $category->status : '1')"
                        :options="['1' => 'Operational / Active', '0' => 'Disabled / Hidden']" />
                </div>

                <div class="mt-8 space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Specification Protocols</h3>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-black uppercase tracking-widest rounded-full">Linked Specs</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Select which attributes are relevant for products in this category (e.g. Size for Clothes, RAM for Laptops).</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                        @foreach($attributes as $attr)
                            <label class="flex items-center gap-4 p-5 bg-white border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-400 hover:bg-slate-50/50 transition-all group">
                                <input type="checkbox" name="attributes[]" value="{{ $attr->id }}"
                                       @if($edit && $category->attributes->contains($attr->id)) checked @endif
                                       class="w-5 h-5 text-emerald-500 border-slate-300 rounded-lg focus:ring-emerald-500/20 focus:ring-4">
                                <span class="text-sm font-bold text-slate-700 group-hover:text-emerald-600 transition-colors">{{ $attr->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-12 pt-8 border-t border-slate-100">
                <button type="submit"
                        class="px-12 py-5 font-black uppercase tracking-[0.2em] text-white transition-all duration-300 transform bg-emerald-500 rounded-3xl shadow-2xl shadow-emerald-500/30 hover:bg-emerald-600 hover:scale-[1.02] active:scale-[0.98] outline-none">
                    {{ $edit ? 'Commit Sync' : 'Initialize Protocol' }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            // Auto generate slug from name
            $('#name').on('input', function() {
                let slug = $(this).val().toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
                $('#slug').val(slug);
            });

            // Form validation with jQuery Validate
            $("#categoryForm").validate({
                rules: {
                    name: "required",
                    slug: "required",
                    status: "required"
                },
                submitHandler: function(form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: $(form).attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({
                                title: "Success",
                                text: "{{ $edit ? 'Category updated' : 'Category created' }} successfully",
                                icon: "success"
                            }).then(() => {
                                window.location.href = "{{ route('admin.categories.index') }}";
                            });
                        },
                        error: function(xhr) {
                            let errorMsg = "Something went wrong!";
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                errorMsg = Object.values(errors).flat().join('<br>');
                            } else if (xhr.status === 413) {
                                errorMsg = "The uploaded file is too large for the server to process.";
                            }
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                html: errorMsg,
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
