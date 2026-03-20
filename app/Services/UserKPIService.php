<?php
namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class UserKPIService
{
    private $now;
    private $oneWeekAgo;
    private $oneMonthAgo;
    private $threeMonthsAgo;

    public function __construct()
    {
        $this->now            = Carbon::now();
        $this->oneWeekAgo     = Carbon::now()->subWeek();
        $this->oneMonthAgo    = Carbon::now()->subMonth();
        $this->threeMonthsAgo = Carbon::now()->subMonths(3);
    }

    public function getAllUsersCount()
    {
        return User::businessAccounts()->count();
    }

    public function getUnverifiedUsersCount()
    {
        return User::businessAccounts()->whereNull('email_verified_at')->count();
    }

    public function getVerifiedUsersCount()
    {
        return User::businessAccounts()->whereNotNull('email_verified_at')->count();
    }

    public function getActiveUsersCount()
    {
        return User::businessAccounts()
            ->whereNotNull('email_verified_at')
            ->whereHas('orders', function ($query) {
                $query->where('orders.created_at', '>=', Carbon::now()->subMonth());
            }, '>', 5)
            ->count();
    }

    public function getMrr()
    {
        return (float) User::businessAccounts()
            ->whereHas('orders', function ($query) {
                $query->whereBetween('orders.created_at', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ]);
            })
            ->withSum(['orders as monthly_orders_total' => function ($query) {
                $query->whereBetween('orders.created_at', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ]);
            }], 'total_amount')
            ->get()
            ->sum('monthly_orders_total');
    }

    public function getVerifiedUsersWithoutOrdersCount()
    {
        return User::businessAccounts()
            ->whereNotNull('email_verified_at')
            ->whereDoesntHave('orders')
            ->count();
    }

    public function getOneTimeUsersCount()
    {
        return User::businessAccounts()
            ->whereNotNull('email_verified_at')
            ->whereHas('orders', null, '=', 1)
            ->count();
    }

    public function getSlightlyDormantUsersCount()
    {
        $_oneWeekAgo = $this->oneWeekAgo;

        return User::businessAccounts()
            ->whereDoesntHave('orders', function ($query) use ($_oneWeekAgo) {
                $query->where('orders.created_at', '>=', $_oneWeekAgo);
            })
            ->whereHas('orders', null, '>', 1)
            ->count();
    }

    public function getModeratelyDormantUsersCount()
    {
        $_oneMonthAgo = $this->oneMonthAgo;

        return User::businessAccounts()
            ->whereDoesntHave('orders', function ($query) use ($_oneMonthAgo) {
                $query->where('orders.created_at', '>=', $_oneMonthAgo);
            })
            ->whereHas('orders', null, '>', 1)
            ->count();
    }

    public function getHighlyDormantUsersCount()
    {
        $_threeMonthsAgo = $this->threeMonthsAgo;

        return User::businessAccounts()
            ->whereDoesntHave('orders', function ($query) use ($_threeMonthsAgo) {
                $query->where('orders.created_at', '>=', $_threeMonthsAgo);
            })
            ->whereHas('orders', null, '>', 1)
            ->count();
    }

    public function getChurnedDormantUsers()
    {
        return User::businessAccounts()
            ->whereDoesntHave('orders', function ($query) {
                $query->where('orders.created_at', '>=', Carbon::now()->subMonths(2));
            })
            ->whereHas('orders', null, '>', 1)
            ->count();
    }

    public function getInactiveDormantUsers()
    {
        return User::businessAccounts()
            ->whereHas('orders', null, '=', 1)
            ->count();
    }
}
