@extends('admin.layouts.app')

@section('content')
    <div class="mb-10 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter">Command Center</h1>
            <p class="text-slate-500 font-medium tracking-tight">Real-time performance metrics for <span class="text-emerald-600 font-black italic">aarot</span> ecosystem.</p>
        </div>
        <div class="flex gap-3">
             <div class="px-5 py-2 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-2">
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">System Live</span>
             </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Revenue Stats -->
        <div class="relative group">
            <div class="absolute inset-0 bg-emerald-500 rounded-[2rem] blur-xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
            <div class="relative flex items-center p-8 bg-white border border-slate-100 rounded-[2rem] shadow-xl shadow-slate-200/50 transition-transform group-hover:-translate-y-1">
                <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
                <div class="ml-6">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Total Revenue</p>
                    <h4 class="text-3xl font-black text-slate-900 tracking-tighter">${{number_format($totalRevenue, 2)}}</h4>
                    <span class="text-[10px] font-bold text-emerald-600">Net Profit</span>
                </div>
            </div>
        </div>

        <!-- Orders today -->
        <div class="relative group">
            <div class="absolute inset-0 bg-slate-900 rounded-[2rem] blur-xl opacity-5 group-hover:opacity-10 transition-opacity"></div>
            <div class="relative flex items-center p-8 bg-white border border-slate-100 rounded-[2rem] shadow-xl shadow-slate-200/50 transition-transform group-hover:-translate-y-1">
                <div class="w-16 h-16 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-slate-900/30">
                    <i class="fas fa-shopping-basket text-2xl"></i>
                </div>
                <div class="ml-6">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Today's Transactions</p>
                    <h4 class="text-3xl font-black text-slate-900 tracking-tighter">{{$todayOrders->count()}}</h4>
                    <span class="text-[10px] font-bold text-slate-400">Items Processed</span>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="relative group">
            <div class="absolute inset-0 bg-emerald-600 rounded-[2rem] blur-xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
            <div class="relative flex items-center p-8 bg-white border border-slate-100 rounded-[2rem] shadow-xl shadow-slate-200/50 transition-transform group-hover:-translate-y-1">
                <div class="w-16 h-16 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-600/30">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-6">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Customer Base</p>
                    <h4 class="text-3xl font-black text-slate-900 tracking-tighter">{{$totalCustomers}}</h4>
                    <span class="text-[10px] font-bold text-emerald-600">Active Registrations</span>
                </div>
            </div>
        </div>

        <!-- Annual Projections -->
        <div class="relative group">
            <div class="absolute inset-0 bg-slate-800 rounded-[2rem] blur-xl opacity-5 group-hover:opacity-10 transition-opacity"></div>
            <div class="relative flex items-center p-8 bg-white border border-slate-100 rounded-[2rem] shadow-xl shadow-slate-200/50 transition-transform group-hover:-translate-y-1">
                <div class="w-16 h-16 bg-slate-800 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-slate-800/30">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div class="ml-6">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Annual Volume</p>
                    <h4 class="text-3xl font-black text-slate-900 tracking-tighter">{{$yearOrder->count()}}</h4>
                    <span class="text-[10px] font-bold text-slate-400">Total Lifecycle</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <div class="bg-white border border-slate-100 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden">
            <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Recent Order Manifests</h3>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">Live Transaction Stream</p>
                </div>
                <button class="px-6 py-2.5 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10">Archive All</button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Order Hash</th>
                            <th class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Customer Protocol</th>
                            <th class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Value</th>
                            <th class="px-10 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($recentOrders as $order)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-10 py-6 whitespace-nowrap">
                                <span class="px-4 py-2 bg-slate-100 rounded-xl text-xs font-black text-slate-900 tracking-tighter border border-slate-200 group-hover:bg-white group-hover:border-emerald-500/20 transition-all">{{$order->order_number}}</span>
                            </td>
                            <td class="px-10 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center text-white text-xs font-black">
                                        {{substr($order->user->name ?? 'G', 0, 1)}}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-slate-800">{{$order->user->name ?? 'Guest User'}}</div>
                                        <div class="text-[10px] font-medium text-slate-400 tracking-tight">{{$order->user->email ?? 'no-auth-protocol'}}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-6 whitespace-nowrap">
                                <span class="px-4 py-1.5 inline-flex text-[10px] font-black leading-5 rounded-full uppercase tracking-widest 
                                    @if($order->status == 'delivered') bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 
                                    @elseif($order->status == 'pending') bg-yellow-500/10 text-yellow-600 border border-yellow-500/20
                                    @else bg-slate-500/10 text-slate-600 border border-slate-500/20 @endif">
                                    {{$order->status}}
                                </span>
                            </td>
                            <td class="px-10 py-6 whitespace-nowrap text-sm font-black text-slate-900">${{number_format($order->total, 2)}}</td>
                            <td class="px-10 py-6 whitespace-nowrap text-right">
                                <span class="text-[11px] font-bold text-slate-400">{{$order->created_at->diffForHumans()}}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-10 py-6 bg-slate-50/50 border-t border-slate-50 text-center">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Protocol end. Secure monitoring active.</p>
            </div>
        </div>
    </div>
@endsection
