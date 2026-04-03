@extends('admin.layouts.app')

@section('content')
    <section class="max-w-6xl p-6 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800">
        <div class="flex flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white capitalize">
                Product Details: {{ $product->name }}
            </h2>
            <div class="flex gap-x-2">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <i class="fa-solid fa-backward mr-1"></i> Back
                </a>
                <a href="{{ route('admin.products.edit', $product) }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Product Images Gallery -->
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-gray-50 dark:bg-gray-900 p-2 rounded-lg border dark:border-gray-700">
                    <img id="main-product-image" src="{{ asset($product->first_image) }}" alt="{{ $product->name }}" class="w-full h-80 object-contain rounded-md">
                </div>
                <div class="grid grid-cols-3 gap-2">
                    @if($product->first_image)
                        <img src="{{ asset($product->first_image) }}" onclick="document.getElementById('main-product-image').src=this.src" class="h-20 w-full object-cover rounded-md cursor-pointer hover:opacity-75 transition-opacity border dark:border-gray-700">
                    @endif
                    @if($product->second_image)
                        <img src="{{ asset($product->second_image) }}" onclick="document.getElementById('main-product-image').src=this.src" class="h-20 w-full object-cover rounded-md cursor-pointer hover:opacity-75 transition-opacity border dark:border-gray-700">
                    @endif
                    @if($product->third_image)
                        <img src="{{ asset($product->third_image) }}" onclick="document.getElementById('main-product-image').src=this.src" class="h-20 w-full object-cover rounded-md cursor-pointer hover:opacity-75 transition-opacity border dark:border-gray-700">
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Category</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">
                        {{ $product->category ? $product->category->name : 'No Category' }}
                    </p>
                </div>
                <!-- Status and Price -->
                <div class="flex flex-wrap items-center justify-between gap-4 p-4 rounded-lg bg-blue-50 dark:bg-gray-700 border border-blue-100 dark:border-gray-600">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Current Price</p>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">${{ number_format($product->price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Stock Availability</p>
                        <p class="text-lg font-semibold {{ $product->qty > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->qty > 0 ? $product->qty . ' in stock' : 'Out of stock' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Product Status</p>
                        @if($product->status == 1)
                            <span class="px-3 py-1 text-sm font-medium text-green-700 bg-green-100 rounded-full">Active</span>
                        @else
                            <span class="px-3 py-1 text-sm font-medium text-red-700 bg-red-100 rounded-full">Inactive</span>
                        @endif
                    </div>
                </div>

                <!-- Attributes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Available Colors</h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse($product->colors as $color)
                                <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 rounded-md border dark:border-gray-700 flex items-center">
                                    <span class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $color->name }}"></span>
                                    {{ $color->name }}
                                </span>
                            @empty
                                <span class="text-gray-500 italic">No colors specified</span>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Available Sizes</h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse($product->sizes as $size)
                                <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 rounded-md border dark:border-gray-700">
                                    {{ $size->name }}
                                </span>
                            @empty
                                <span class="text-gray-500 italic">No sizes specified</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Description</h3>
                    <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <div class="pt-4 border-t dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Product Slug: <code class="bg-gray-100 dark:bg-gray-900 px-1 rounded">{{ $product->slug }}</code>
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Created At: {{ $product->created_at->format('M d, Y h:i A') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
