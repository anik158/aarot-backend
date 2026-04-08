<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\Admin\Coupon;
use App\Services\CouponService;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    use ResponseStatus;
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $coupons = Coupon::select(['id', 'code', 'type', 'value', 'expires_at', 'is_active'])
            ->when($search, function ($query, $search) {
                return $query->where('code', 'like', "%{$search}%");
            })
            ->paginate(20);

        $coupons->appends(['search' => $search]);

        return view('admin.coupon.index', compact('coupons', 'search'));
    }

    public function create()
    {
        return view('admin.coupon.add-edit', ['coupon' => null]);
    }

    public function store(CouponRequest $request)
    {
        try {
            $data = $this->couponService->store($request);
            return $this->success($data, 'Coupon created successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Failed to create new coupon', 500);
        }
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupon.add-edit', compact('coupon'));
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        try {
            $data = $this->couponService->update($request, $coupon);
            return $this->success($data, 'Coupon updated successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->error('Failed to update coupon', 500);
        }
    }

    public function destroy(Coupon $coupon)
    {
        try {
            $this->couponService->destroy($coupon);
            return $this->success(null, 'Coupon deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->error('Failed to delete coupon', 500);
        }
    }
}
