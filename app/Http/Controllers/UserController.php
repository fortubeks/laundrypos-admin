<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserKPIService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');

        $usersQuery = User::query()
            ->businessAccounts()
            ->with(['appSetting', 'laundries'])
            ->withCount(['orders', 'customers', 'laundries'])
            ->latest();

        $title = 'All Users';

        if ($filter === 'active') {
            $title = 'Active Users';
            $usersQuery->whereNotNull('email_verified_at')
                ->whereHas('orders', function ($query) {
                    $query->where('orders.created_at', '>=', Carbon::now()->subMonth());
                }, '>', 5);
        } elseif ($filter === 'churned') {
            $title = 'Churned Users';
            $usersQuery->whereDoesntHave('orders', function ($query) {
                $query->where('orders.created_at', '>=', Carbon::now()->subMonths(2));
            })->whereHas('orders', null, '>', 1);
        } elseif ($filter === 'highly-dormant') {
            $title = 'One Time Users';
            $usersQuery->whereHas('orders', null, '=', 1);
        } elseif ($filter === 'inactive') {
            $title = 'Inactive Users';
            $usersQuery->whereNotNull('email_verified_at')->whereDoesntHave('orders');
        } elseif ($filter === 'unverified') {
            $title = 'Unverified Users';
            $usersQuery->whereNull('email_verified_at');
        }

        $users = $usersQuery->paginate(15)->appends($request->query());

        return view('material.users.index', compact('users', 'title'));
    }

    public function dashboard()
    {
        $userKPIService = new UserKPIService();

        $activeUserCount = $userKPIService->getActiveUsersCount(); // active users

        $churnedDormantUsersCount  = $userKPIService->getChurnedDormantUsers();  //
        $inactiveDormantUsersCount = $userKPIService->getInactiveDormantUsers(); // dormant users
        $totalDormantUsersCount    = $churnedDormantUsersCount + $inactiveDormantUsersCount;

        $unverifiedUsersCount = $userKPIService->getUnverifiedUsersCount(); // unverified users

        $inactiveUsersCount = $userKPIService->getVerifiedUsersWithoutOrdersCount(); // inactive users

        $totalUsersCount = $userKPIService->getAllUsersCount();

        return view('material.users.dashboard', [
            'allUsersCount'             => $userKPIService->getAllUsersCount(), //all users
            'unverifiedUsersCount'      => $userKPIService->getUnverifiedUsersCount(),
            'churnedDormantUsersCount'  => $churnedDormantUsersCount,
            'inactiveDormantUsersCount' => $inactiveDormantUsersCount,
            'totalDormantUsersCount'    => $totalDormantUsersCount,
            'activeUsersCount'          => $activeUserCount,
            'inactiveUsersCount'        => $inactiveUsersCount,
            'totalUsersCount'           => $totalUsersCount,
            'mrr'                       => $userKPIService->getMrr(),
        ]);
    }

    public function search(Request $request)
    {
        $users = User::businessAccounts()
            ->where('email', 'like', '%' . request('email') . '%')
            ->with(['appSetting', 'laundries'])
            ->withCount(['orders', 'customers', 'laundries'])
            ->paginate(15)
            ->appends($request->query());

        $title = 'Search Results';

        return view('material.users.index', compact('users', 'title'));
    }

    public function show(User $user)
    {
        $user = User::businessAccounts()
            ->with(['appSetting', 'laundries'])
            ->withCount(['orders', 'customers', 'laundries'])
            ->findOrFail($user->id);

        $ltv = $this->getUserLTV($user);

        $averageOrdersPerMonth = $this->getUserAverageOrdersPerMonth($user);

        $totalOrdersAmount = (float) $user->orders()->sum('total_amount');

        $latestOrder = $user->orders()->latest('orders.created_at')->first();

        $lastFiveOrders = $user->orders()->latest('orders.created_at')->take(5)->get();

        $orderStatusBreakdown = $user->orders()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $recentLaundries = $user->laundries()->latest()->take(5)->get();

        return view('material.users.show', compact(
            'user',
            'latestOrder',
            'lastFiveOrders',
            'ltv',
            'orderStatusBreakdown',
            'recentLaundries',
            'averageOrdersPerMonth',
            'totalOrdersAmount'
        ));
    }

    private function getUserLTV(User $user): float
    {
        return (float) $user->orders()->sum('total_amount');
    }

    private function getUserAverageOrdersPerMonth(User $user): int
    {
        $firstOrderDate = $user->orders()->oldest('orders.created_at')->value('orders.created_at');

        if (! $firstOrderDate) {
            return 0;
        }

        $monthsSinceFirstOrder = Carbon::parse($firstOrderDate)->diffInMonths(now()) ?: 1;

        $ordersCount = $user->orders_count ?? $user->orders()->count();

        return (int) round($ordersCount / $monthsSinceFirstOrder);
    }
}
