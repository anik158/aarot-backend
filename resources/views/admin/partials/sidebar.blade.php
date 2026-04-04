<!-- Sidebar overlay (mobile only) -->
<div id="sidebarOverlay" class="fixed inset-0 z-20 transition-opacity bg-black/50 lg:hidden hidden"></div>

<!-- Sidebar -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-30 w-72 overflow-y-auto transition-all duration-300 transform bg-slate-950 -translate-x-full lg:translate-x-0 lg:static lg:inset-0 border-r border-white/5">
    <div class="flex flex-col h-full bg-gradient-to-b from-slate-900 to-slate-950">
        <div class="flex items-center justify-start px-8 py-10">
            <a href="{{route('admin.index')}}" class="flex items-center gap-3 group transition-transform hover:scale-105">
                <img src="https://www.svgrepo.com/show/499831/target.svg" alt="aarot" class="h-10 w-auto" />
                <span class="text-3xl font-black tracking-tighter text-white">aarot</span>
            </a>
        </div>

        <nav class="flex-1 px-4 space-y-2">
            <p class="px-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 mb-2">Main Menu</p>
            
            <a class="flex items-center gap-3 px-4 py-4 text-sm font-bold rounded-2xl transition-all duration-300 {{request()->routeIs('admin.index') ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 shadow-[0_0_20px_rgba(16,185,129,0.1)]' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white'}}" 
               href="{{route('admin.index')}}">
                <svg class="w-5 h-5 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <div class="pt-4">
                <p class="px-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 mb-2">Inventory Control</p>
                
                <div class="space-y-1">
                    <button onclick="this.nextElementSibling.classList.toggle('max-h-0'); this.nextElementSibling.classList.toggle('max-h-[1000px]'); this.querySelector('.arrow').classList.toggle('rotate-180')"
                            class="w-full flex items-center justify-between px-4 py-4 text-sm font-bold text-slate-400 rounded-2xl transition-all duration-300 hover:bg-slate-800/50 hover:text-white group">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            <span>E-Commerce Config</span>
                        </div>
                        <svg class="arrow w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    
                    <div class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out pl-10 space-y-1">
                        @php $subitems = [
                            ['route' => 'admin.categories.index', 'name' => 'Categories'],
                            ['route' => 'admin.products.index', 'name' => 'Products'],
                            ['route' => 'admin.colors.index', 'name' => 'Colors'],
                            ['route' => 'admin.sizes.index', 'name' => 'Sizes'],
                            ['route' => 'admin.coupons.index', 'name' => 'Coupons'],
                            ['route' => 'admin.reviews.index', 'name' => 'Customer Reviews'],
                        ]; @endphp

                        @foreach($subitems as $item)
                        <a href="{{route($item['route'])}}" 
                           class="flex items-center py-3 text-[13px] font-medium transition-colors {{request()->routeIs($item['route']) ? 'text-emerald-500' : 'text-slate-500 hover:text-white'}}">
                           <span class="w-1.5 h-1.5 rounded-full bg-slate-700 mr-3 {{request()->routeIs($item['route']) ? 'bg-emerald-500 ring-4 ring-emerald-500/20' : ''}}"></span>
                           {{$item['name']}}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </nav>

        <div class="p-6 mt-auto">
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-3xl p-5 shadow-2xl shadow-emerald-500/20">
                <p class="text-[10px] font-black tracking-widest text-emerald-100 uppercase mb-1">Status</p>
                <div class="flex items-center justify-between">
                    <span class="text-white font-bold text-sm">System Online</span>
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse shadow-[0_0_10px_#fff]"></div>
                </div>
            </div>
        </div>
    </div>
</div>
