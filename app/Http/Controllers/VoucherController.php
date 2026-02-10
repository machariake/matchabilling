<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::with(['plan', 'user'])->get();
        return response()->json($vouchers);
    }

    public function show($id)
    {
        $voucher = Voucher::with(['plan', 'user'])->findOrFail($id);
        return response()->json($voucher);
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'quantity' => 'required|integer|min:1',
            'expiry_days' => 'nullable|integer|min:1'
        ]);

        $vouchers = [];
        for ($i = 0; $i < $request->quantity; $i++) {
            $voucher = Voucher::create([
                'code' => $this->generateVoucherCode(),
                'plan_id' => $request->plan_id,
                'expires_at' => now()->addDays($request->expiry_days ?? 30)
            ]);
            $vouchers[] = $voucher;
        }

        return response()->json([
            'message' => 'Vouchers created successfully',
            'vouchers' => $vouchers
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'plan_id' => 'sometimes|exists:plans,id',
            'user_id' => 'sometimes|exists:users,id',
            'is_used' => 'sometimes|boolean',
            'expires_at' => 'sometimes|date'
        ]);

        $voucher->update($request->all());

        return response()->json([
            'message' => 'Voucher updated successfully',
            'voucher' => $voucher
        ]);
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return response()->json([
            'message' => 'Voucher deleted successfully'
        ]);
    }

    public function validateVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $voucher = Voucher::where('code', $request->code)
                         ->where('is_used', false)
                         ->where('expires_at', '>', now())
                         ->with('plan')
                         ->first();

        if (!$voucher) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired voucher'
            ]);
        }

        return response()->json([
            'valid' => true,
            'voucher' => $voucher,
            'plan' => $voucher->plan
        ]);
    }

    private function generateVoucherCode($length = 16)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }
}