@extends('admin.layouts.app')

@section('content')
    <div class="px-4 mx-auto max-w-5xl">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-4xl font-black text-slate-900 tracking-tighter">
                    Category Specs
                </h2>
                <p class="mt-2 text-sm font-bold text-slate-500 uppercase tracking-widest">
                    Collection / {{ $category->name }}
                </p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-6 py-3 text-sm font-black text-slate-600 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 hover:text-slate-900 transition-all duration-300 shadow-sm shadow-slate-100">
                    <i class="fa-solid fa-arrow-left"></i> List
                </a>
                <a href="{{ route('admin.categories.edit', $category) }}" class="flex items-center gap-2 px-6 py-3 text-sm font-black text-white bg-slate-900 rounded-2xl hover:bg-slate-800 transition-all duration-300 shadow-xl shadow-slate-900/20">
                    <i class="fa-solid fa-pen-nib"></i> Modify
                </a>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden">
            <div class="p-10 grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Image -->
                <div class="lg:col-span-1">
                    @if($category->image)
                        <div class="p-3 bg-slate-50 rounded-[2rem] border border-slate-100 shadow-inner">
                            <img src="{{ asset('storage/' . $category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="w-full h-80 object-cover rounded-[1.5rem] shadow-2xl shadow-slate-900/10">
                        </div>
                    @else
                        <div class="w-full h-80 bg-slate-50 rounded-[2rem] flex flex-col items-center justify-center border-2 border-dashed border-slate-200">
                            <i class="fa-solid fa-image text-4xl text-slate-200 mb-4"></i>
                            <span class="text-slate-400 text-xs font-black uppercase tracking-widest">No Visual Attached</span>
                        </div>
                    @endif
                </div>

                <!-- Details -->
                <div class="lg:col-span-2 space-y-10">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-6">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Internal Title</p>
                            <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $category->name }}</h3>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Metric Status</p>
                            @if($category->status)
                                <span class="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 rounded-full border border-emerald-100">Functional</span>
                            @else
                                <span class="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest text-red-600 bg-red-50 rounded-full border border-red-100">Suspended</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">URL Identifier</p>
                            <p class="px-4 py-2 bg-slate-50 text-slate-600 rounded-xl font-mono text-sm border border-slate-100 w-fit">{{ $category->slug }}</p>
                        </div>
                    </div>

                    @if($category->description)
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3">Narrative / Description</p>
                            <div class="text-slate-600 leading-relaxed font-medium">
                                {!! nl2br(e($category->description)) !!}
                            </div>
                        </div>
                    @endif

                    @if($category->attributes->count() > 0)
                        <div class="pt-8 border-t border-slate-100">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Linked Specifications</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($category->attributes as $attr)
                                    <span class="px-3 py-1 bg-white border border-slate-200 text-slate-600 text-[11px] font-bold rounded-lg shadow-sm">{{ $attr->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
