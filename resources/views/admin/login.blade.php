<!doctype html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>aarot - Admin Login</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM+Sans', sans-serif; }
    </style>
</head>
<body class="h-full">
<div class="flex min-h-screen flex-col justify-center px-6 py-12 lg:px-8 relative overflow-hidden">
    <!-- Background Patterns -->
    <div class="absolute top-0 left-0 w-full h-full -z-10 bg-[radial-gradient(circle_at_30%_20%,rgba(16,185,129,0.08)_0%,transparent_50%)]"></div>
    <div class="absolute bottom-0 right-0 w-full h-full -z-10 bg-[radial-gradient(circle_at_70%_80%,rgba(16,185,129,0.05)_0%,transparent_50%)]"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex items-center justify-center gap-3 mb-10">
            <img src="https://www.svgrepo.com/show/499831/target.svg" alt="aarot" class="h-12 w-auto" />
            <span class="text-4xl font-black tracking-tighter text-slate-900">aarot</span>
        </div>
        
        <div class="bg-white/70 backdrop-blur-2xl border border-slate-200 p-10 rounded-[2.5rem] shadow-2xl shadow-slate-200/50">
            <h2 class="text-3xl font-black tracking-tight text-slate-900 mb-8 text-center font-dm">Admin Portal</h2>
            
            <form action="{{route('auth')}}" method="POST" class="space-y-6">
                @csrf
                
                @if(session('error'))
                    <div class="p-4 rounded-2xl bg-red-50 border border-red-100 text-red-600 text-sm font-semibold animate-in slide-in-from-top-2 duration-300">
                        {{ session('error') }}
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 ml-1 mb-2">Login Email</label>
                    <div class="mt-1">
                        <input id="email" type="email" name="email" required autocomplete="email" value="{{old('email')}}"
                               class="block w-full rounded-2xl bg-white border-2 border-slate-100 px-4 py-3.5 text-slate-900 shadow-sm transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none text-base font-medium placeholder:text-slate-400" 
                               placeholder="admin@aarot.com" />
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-2 ml-1 font-bold">{{$message}}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between ml-1 mb-2">
                        <label for="password" class="block text-sm font-bold text-slate-700">Access Key</label>
                        <div class="text-xs">
                            <a href="#" class="font-bold text-emerald-600 hover:text-emerald-700 transition-colors">Emergency Reset?</a>
                        </div>
                    </div>
                    <div class="mt-1">
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="block w-full rounded-2xl bg-white border-2 border-slate-100 px-4 py-3.5 text-slate-900 shadow-sm transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none text-base font-medium placeholder:text-••••••••" />
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-2 ml-1 font-bold">{{$message}}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" 
                            class="flex w-full justify-center rounded-2xl bg-slate-900 px-4 py-4 text-sm font-black text-white shadow-xl shadow-slate-900/10 transition-all hover:bg-slate-800 hover:scale-[1.02] active:scale-[0.98] focus-visible:outline-none tracking-tight">
                        Authenticate Securely
                    </button>
                </div>
            </form>
        </div>

        <p class="mt-10 text-center text-sm text-slate-400 font-medium tracking-tight">
            Protected by aarot security protocols.<br/>
            &copy; {{date('Y')}} aarot. All rights reserved.
        </p>
    </div>
</div>
</body>
</html>
