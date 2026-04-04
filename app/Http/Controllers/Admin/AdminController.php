<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthAdminRequest;
use App\Models\User\Order;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index() {
        $todayOrders = Order::whereDay('created_at', Carbon::today())->get();
        $yesterDayOrders = Order::whereDay('created_at', Carbon::yesterday())->get();
        $monthOrders = Order::whereMonth('created_at', Carbon::now()->month)->get();
        $yearOrder = Order::whereYear('created_at', Carbon::now()->year)->get();
        
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $totalCustomers = \App\Models\User::count();
        $recentOrders = Order::with('user')->latest()->take(8)->get();

        return view('admin.index', compact(
            'todayOrders', 'yesterDayOrders', 'monthOrders', 'yearOrder',
            'totalRevenue', 'totalCustomers', 'recentOrders'
        ));
    }

    public function login() {
        if(!auth()->guard('admin')->check()) {
            return view('admin.login');
        }

        return redirect()->route('admin.index');
    }

    public function auth(AuthAdminRequest $request) {
        if (auth()->guard('admin')->attempt($request->only('email', 'password'))) {
            \Log::info('Login Success');
            $request->session()->regenerate();
            return redirect()->route('admin.index');
        }

        // This runs when attempt() returns false (bad credentials)
        \Log::info('Login failed for email: '.$request->email);
        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function logout() {
        auth()->guard('admin')->logout();
        return redirect()->route('login');
    }
}
