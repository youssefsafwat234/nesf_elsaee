<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Livewire\Attributes\Rule;

class OrderController extends Controller
{
    function index()
    {
        $orders = auth()->user()->orders;
        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    function destroy(Request $request)
    {
        $request->validate(
            [
                'order_id' => ['required', \Illuminate\Validation\Rule::exists('orders', 'id')->where('user_id', auth()->id())],
            ]
        );
        $order = Order::findOrFail($request->order_id);
        if ($order) {

            // return the advertisement to be approved
            $order->advertisement()->update([
                'status' => 'مفعل',
                'pending_by' => null,
            ]);
            $order->delete();
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الطلب بنجاح',
            ]);

        }
        return response()->json([
            'success' => false,
            'message' => 'الطلب غير موجود'
        ], 404);
    }

    function finishOrder(Request $request)
    {

        $request->validate(
            [
                'order_id' => ['required', \Illuminate\Validation\Rule::exists('orders', 'id')->where('user_id', auth()->id())],
            ]
        );


        $order = Order::findOrFail($request->order_id);
        if ($order) {
            $order->advertisement()->update([
                'status' => 'مكتمل',
                'pending_by' => null,
            ]);
            $order->delete();
            return response()->json([
                'success' => true,
                'message' => 'تم تسليم الطلب بنجاح',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'الطلب غير موجود'
        ], 404);
    }

}
