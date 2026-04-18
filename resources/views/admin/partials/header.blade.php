<header class="flex items-center justify-between px-8 py-6 bg-white/70 backdrop-blur-3xl border-b border-slate-200 sticky top-0 z-10 shadow-sm shadow-slate-100/50">
    <div class="flex items-center">
        <!-- Sidebar toggle -->
        <button id="openSidebar" class="p-2 text-slate-500 rounded-xl bg-slate-50 lg:hidden hover:bg-slate-100 transition-colors">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <!-- Dynamic Header Message -->
        <div class="ml-4 lg:ml-0">
            <h1 class="text-xl font-black text-slate-900 tracking-tight font-dm">aarot HQ</h1>
            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest">Management Control Center</p>
        </div>
    </div>

    <div class="flex items-center gap-6">
        <!-- Notifications -->
        <div class="relative">
            <button id="toggleNotifications" class="p-2.5 text-slate-500 bg-slate-50 hover:bg-slate-100 hover:text-emerald-500 rounded-2xl transition-all duration-300 relative group">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                    <path d="M15 17H20L18.5951 15.5951C18.2141 15.2141 18 14.6973 18 14.1585V11C18 8.38757 16.3304 6.16509 14 5.34142V5C14 3.89543 13.1046 3 12 3C10.8954 3 10 3.89543 10 5V5.34142C7.66962 6.16509 6 8.38757 6 11V14.1585C6 14.6973 5.78595 15.2141 5.40493 15.5951L4 17H9M15 17V18C15 19.6569 13.6569 21 12 21C10.3431 21 9 19.6569 9 18V17M15 17H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="absolute top-2 right-2 w-3 h-3 bg-red-500 border-2 border-white rounded-full group-hover:scale-110 transition-transform"></div>
            </button>

            <div id="notificationsPanel" class="absolute right-0 z-50 mt-4 overflow-hidden bg-white/95 backdrop-blur-3xl rounded-[2rem] border border-slate-200 shadow-2xl w-80 hidden animate-in slide-in-from-top-2 duration-300">
                <div class="p-5 border-b border-slate-100 flex justify-between items-center">
                    <p class="font-bold text-slate-900">Alerts</p>
                    <span class="px-2 py-0.5 bg-emerald-500 text-[10px] font-black text-white rounded-full">3 New</span>
                </div>
                <div class="max-h-[400px] overflow-y-auto">
                    <a href="#" class="flex items-center px-4 py-4 hover:bg-slate-50 transition-colors border-b border-slate-50">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 font-black">S</div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-slate-800">Shop System</p>
                            <span class="text-[11px] text-slate-400">Inventory sync completed successfully.</span>
                        </div>
                    </a>
                </div>
                <div class="p-4 text-center">
                    <button class="text-xs font-bold text-emerald-600">View All Protocols</button>
                </div>
            </div>
        </div>

        <!-- User profile -->
        <div class="relative">
            <button id="toggleDropdown" class="flex items-center hover:cursor-pointer gap-3 p-1 pl-4 pr-1 rounded-3xl bg-slate-900 group hover:bg-slate-800 transition-all duration-300 shadow-lg shadow-slate-900/10 active:scale-98">
                <span class="text-sm font-bold text-white tracking-tight">{{auth('admin')->user()->name ?? 'Administrator'}}</span>
                <div class="w-9 h-9 overflow-hidden rounded-2xl bg-white/10 flex items-center justify-center group-hover:scale-105 transition-transform border border-white/5">
                    <img class="object-cover w-full h-full" src="https://ui-avatars.com/api/?name=Admin&background=10b981&color=fff" alt="Avatar">
                </div>
            </button>

            <div id="dropdownMenu" class="absolute right-0 z-50 w-64 mt-4 overflow-hidden bg-white/95 backdrop-blur-3xl rounded-[2.5rem] border border-slate-200 shadow-2xl hidden animate-in slide-in-from-top-2 duration-300">
                <div class="p-6 bg-slate-900 text-white rounded-t-[2.5rem]">
                   <p class="text-[10px] font-black uppercase tracking-widest text-emerald-400 mb-1">Session active</p>
                   <p class="font-bold text-lg tracking-tight">{{auth('admin')->user()->name ?? 'Administrator'}}</p>
                </div>
                <div class="p-2">
                    <a href="#" class="flex items-center gap-3 px-6 py-4 text-sm font-bold text-slate-700 hover:bg-slate-50 hover:text-emerald-600 rounded-2xl transition-all">
                        <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Profile Protocol
                    </a>

                    <div class="h-px bg-slate-100 mx-4 my-2"></div>

                    <a href=""
                       class="flex items-center gap-3 px-6 py-4 text-sm font-black text-red-600 hover:bg-red-50 rounded-2xl transition-all"
                       onclick="event.preventDefault(); document.getElementById('adminLogout').submit();">
                        <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Secure Logout
                    </a>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST" id="adminLogout" class="hidden">
                    @csrf
                </form>

            </div>
        </div>
    </div>
</header>
