@extends('admin.layouts.app')

@php
    $edit = isset($category) && $category;
@endphp

@section('content')
    <section class="max-w-4xl p-6 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800">
        <div class="flex flex-row justify-between">
            <h2 class="text-lg font-semibold text-gray-700 capitalize dark:text-white">
                {{ $edit ? 'Edit Category' : 'Add New Category' }}
            </h2>
            <a href="{{ route('admin.categories.index') }}"
               class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">
                <i class="fa-solid fa-backward"></i> Back
            </a>
        </div>

        <form action="{{ $edit ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
              method="POST"
              id="categoryForm"
              enctype="multipart/form-data">

            @csrf
            @if($edit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-6 mt-6">
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="name">Category Name</label>
                    <input id="name" name="name" type="text"
                           value="{{ old('name', $edit ? $category->name : '') }}"
                           class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="slug">Slug</label>
                    <input id="slug" name="slug" type="text"
                           value="{{ old('slug', $edit ? $category->slug : '') }}"
                           class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="description">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">{{ old('description', $edit ? $category->description : '') }}</textarea>
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="image">Category Image</label>
                    <input id="image" name="image" type="file"
                           class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">

                    @if($edit && $category->image)
                        <div class="mt-3">
                            <img src="{{ asset('storage/' .$category->image) }}" alt="Current Image" class="w-32 h-32 object-cover rounded-md border">
                        </div>
                    @endif
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="status">Status</label>
                    <select id="status" name="status"
                            class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                        <option value="1" {{ old('status', $edit ? $category->status : '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $edit ? $category->status : '1') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <button type="submit"
                        class="px-8 py-2.5 leading-5 text-white hover:cursor-pointer transition-colors duration-300 transform bg-blue-500 rounded-lg hover:bg-blue-600 dark:hover:bg-gray-700 dark:bg-gray-900 focus:outline-none">
                    {{ $edit ? 'Update Category' : 'Create Category' }}
                </button>
            </div>
        </form>
    </section>
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
