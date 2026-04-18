@extends('admin.layouts.app')

@section('content')
    <div x-data class="px-4 mx-auto max-w-6xl">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-4xl font-black text-slate-900 tracking-tighter">
                    Inventory Analytics
                </h2>
                <p class="mt-2 text-sm font-bold text-slate-500 uppercase tracking-widest">
                    Product / {{ $product->name }}
                </p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 px-6 py-3 text-sm font-black text-slate-600 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 hover:text-slate-900 transition-all duration-300 shadow-sm shadow-slate-100">
                    <i class="fa-solid fa-arrow-left"></i> Registry
                </a>
                <a href="{{ route('admin.products.edit', $product) }}" class="flex items-center gap-2 px-6 py-3 text-sm font-black text-white bg-slate-900 rounded-2xl hover:bg-slate-800 transition-all duration-300 shadow-xl shadow-slate-900/20">
                    <i class="fa-solid fa-pen-nib"></i> Modify Record
                </a>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden">
            <div class="p-10 grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Visual Gallery -->
                <div class="space-y-6">
                    <div class="p-3 bg-slate-50 rounded-[2rem] border border-slate-100 shadow-inner">
                        <img id="main-product-image" 
                             src="{{ asset($product->first_image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-96 object-contain rounded-[1.5rem] shadow-2xl shadow-slate-900/10">
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4">
                        @if($product->first_image)
                            <button @click="document.getElementById('main-product-image').src='{{ asset($product->first_image) }}'" class="p-2 bg-white border border-slate-200 rounded-2xl hover:border-emerald-500 transition-all shadow-sm active:scale-95 group">
                                <img src="{{ asset($product->first_image) }}" class="w-full h-20 object-cover rounded-xl group-hover:opacity-80">
                            </button>
                        @endif
                        @if($product->second_image)
                            <button @click="document.getElementById('main-product-image').src='{{ asset($product->second_image) }}'" class="p-2 bg-white border border-slate-200 rounded-2xl hover:border-emerald-500 transition-all shadow-sm active:scale-95 group">
                                <img src="{{ asset($product->second_image) }}" class="w-full h-20 object-cover rounded-xl group-hover:opacity-80">
                            </button>
                        @endif
                        @if($product->third_image)
                            <button @click="document.getElementById('main-product-image').src='{{ asset($product->third_image) }}'" class="p-2 bg-white border border-slate-200 rounded-2xl hover:border-emerald-500 transition-all shadow-sm active:scale-95 group">
                                <img src="{{ asset($product->third_image) }}" class="w-full h-20 object-cover rounded-xl group-hover:opacity-80">
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Strategic Details -->
                <div class="space-y-12">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-8">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Market Name</p>
                            <h3 class="text-4xl font-black text-slate-900 tracking-tighter">{{ $product->name }}</h3>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Protocol Status</p>
                            @if($product->status == 1)
                                <span class="px-5 py-2 text-[10px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 rounded-full border border-emerald-100 shadow-sm shadow-emerald-500/10">Synchronized</span>
                            @else
                                <span class="px-5 py-2 text-[10px] font-black uppercase tracking-widest text-red-600 bg-red-50 rounded-full border border-red-100 shadow-sm shadow-red-500/10">Suspended</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3">Unit Valuation</p>
                            <p class="text-3xl font-black text-slate-900 tracking-tight">${{ number_format($product->price, 2) }}</p>
                        </div>
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3">Stock Allocation</p>
                            <div class="flex items-center gap-3">
                                <p class="text-3xl font-black {{ $product->qty > 0 ? 'text-emerald-600' : 'text-red-600' }} tracking-tight">{{ $product->qty }}</p>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 bg-slate-200 px-2 py-0.5 rounded">Units</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Department Classification</p>
                        <div class="inline-flex items-center gap-3 px-6 py-3 bg-white border border-slate-200 rounded-2xl shadow-md">
                            <i class="fa-solid fa-layer-group text-emerald-500"></i>
                            <span class="text-sm font-black text-slate-700 uppercase tracking-widest">{{ $product->category ? $product->category->name : 'Unassigned' }}</span>
                        </div>
                    </div>

                    @php
                        // Group attributes for a modern spec sheet
                        $groupedAttributes = $product->attributeValues->groupBy(function($item) {
                            return $item->attribute->name;
                        });
                    @endphp

                    @if($groupedAttributes->count() > 0)
                        <div class="pt-10 border-t border-slate-100">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-6">Technical Specifications</h4>
                            <div class="space-y-6">
                                @foreach($groupedAttributes as $name => $values)
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-300 mb-2">{{ $name }}</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($values as $v)
                                                <span class="px-4 py-1.5 bg-slate-900 shadow-lg shadow-slate-900/20 text-white text-[10px] font-black rounded-lg uppercase tracking-widest">{{ $v->value }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($product->description)
                        <div class="pt-10 border-t border-slate-100">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Strategic Narrative / Exposition</p>
                            <div class="text-slate-600 leading-relaxed font-medium">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>
                    @endif

                    <div class="pt-10 border-t border-slate-100 flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-slate-400">
                        <span>Registry ID: {{ $product->slug }}</span>
                        <span>Captured: {{ $product->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

