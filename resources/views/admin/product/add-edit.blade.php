@extends('admin.layouts.app')

@php $edit = isset($product) && $product; @endphp

@section('content')
    <section class="max-w-4xl p-6 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800">
        <div class="flex flex-row justify-between">
            <h2 class="text-lg font-semibold text-gray-700 capitalize dark:text-white">
                {{ $edit ? 'Edit Product' : 'Add Product' }}
            </h2>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">
                <i class="fa-solid fa-backward"></i> Back
            </a>
        </div>

        <form action="{{ $edit ? route('admin.products.update', $product) : route('admin.products.store') }}"
              method="POST"
              id="productForm"
              enctype="multipart/form-data">
            @csrf
            @if($edit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">

                <!-- Category -->
                <div class="sm:col-span-2">
                    <label class="text-gray-700 dark:text-gray-200" for="category_id">Category</label>
                    <select id="category_id" name="category_id"
                            class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $edit ? $product->category_id : '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $edit ? $product->name : '') }}"
                           class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="slug">Slug</label>
                    <input id="slug" name="slug" type="text" value="{{ old('slug', $edit ? $product->slug : '') }}"
                           class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="qty">Quantity</label>
                    <input id="qty" name="qty" type="number" value="{{ old('qty', $edit ? $product->qty : '') }}"
                           class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="price">Price</label>
                    <input id="price" name="price" type="number" step="0.01" value="{{ old('price', $edit ? number_format($product->price, 2, '.', '') : '') }}"
                           class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>

                <div class="sm:col-span-2">
                    <label class="text-gray-700 dark:text-gray-200" for="description">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">{{ old('description', $edit ? $product->description : '') }}</textarea>
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200">Colors</label>
                    <div class="mt-2 flex flex-wrap gap-4">
                        @foreach($colors as $color)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="colors[]" value="{{ $color->id }}"
                                       {{ $edit && $product->colors->contains($color->id) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-gray-700 dark:text-gray-200">{{ $color->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200">Sizes</label>
                    <div class="mt-2 flex flex-wrap gap-4">
                        @foreach($sizes as $size)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="sizes[]" value="{{ $size->id }}"
                                       {{ $edit && $product->sizes->contains($size->id) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-gray-700 dark:text-gray-200">{{ $size->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="status">Status</label>
                    <select id="status" name="status"
                            class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                        <option value="1" {{ old('status', $edit ? $product->status : '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $edit ? $product->status : '1') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label class="text-gray-700 dark:text-gray-200" for="first_image">First Image</label>
                        <input id="first_image" name="first_image" type="file"
                               class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                        @if($edit && $product->first_image)
                            <img src="{{ asset($product->first_image) }}" class="mt-2 w-20 h-20 object-cover rounded">
                        @endif
                    </div>
                    <div>
                        <label class="text-gray-700 dark:text-gray-200" for="second_image">Second Image</label>
                        <input id="second_image" name="second_image" type="file"
                               class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                        @if($edit && $product->second_image)
                            <img src="{{ asset($product->second_image) }}" class="mt-2 w-20 h-20 object-cover rounded">
                        @endif
                    </div>
                    <div>
                        <label class="text-gray-700 dark:text-gray-200" for="third_image">Third Image</label>
                        <input id="third_image" name="third_image" type="file"
                               class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                        @if($edit && $product->third_image)
                            <img src="{{ asset($product->third_image) }}" class="mt-2 w-20 h-20 object-cover rounded">
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="px-8 py-2.5 leading-5 text-white transition-colors duration-300 transform bg-blue-500 rounded-lg hover:bg-blue-600 dark:hover:bg-gray-700 dark:bg-gray-900 focus:outline-none">
                    Save
                </button>
            </div>
        </form>
    </section>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            // Slug generation
            $('#name').on('input', function() {
                let slug = $(this).val().toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
                $('#slug').val(slug);
            });

            $("#productForm").validate({
                rules: {
                    name: "required",
                    slug: "required",
                    qty: { required: true, number: true },
                    price: { required: true, number: true },
                    status: "required"
                },
                submitHandler: function(form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: $(form).attr('action'),
                        type: 'POST', // Always POST, handle _method separately
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success === true) {
                                Swal.fire({
                                    title: "Success",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    window.location.href = "{{ route('admin.products.index') }}";
                                });
                            }
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
