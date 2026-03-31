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
    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order                 = Order::with(['laundry.owner.appSetting', 'customer'])->findOrFail($id);
        $user                  = $order->laundry ? $order->laundry->owner : null;
        $ltv                   = $user ? $user->getTotalRevenueFromUser() : 0;
        $averageOrdersPerMonth = $user ? $user->getAverageOrdersPerMonth() : 0;
        $totalOrdersAmount     = $user ? $user->orders()->sum('total_amount') : 0;
        $latestOrder           = $user ? $user->orders()->latest('orders.created_at')->first() : null;
        $lastFiveOrders        = $user ? $user->orders()->latest('orders.created_at')->take(5)->get() : collect();
        return view('material.orders.show', compact('order', 'user', 'ltv', 'averageOrdersPerMonth', 'totalOrdersAmount', 'latestOrder', 'lastFiveOrders'));
    }
}
