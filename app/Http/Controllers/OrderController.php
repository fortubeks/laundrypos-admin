<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $ordersQuery = Order::query()->latest()->with(['laundry.owner.appSetting']);

        if ($request->filled('user_id')) {
            $userId = (int) $request->input('user_id');
            $ordersQuery->whereHas('laundry', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
        }

        $orders = $ordersQuery->paginate(15)->appends($request->query());

        $title = 'All Orders';

        return view('material.orders.index', compact('orders', 'title'));
    }
}
