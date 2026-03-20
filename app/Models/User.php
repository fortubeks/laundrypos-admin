<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'laundry_id',
        'role',
        'is_active',
        'user_account_id',
        'email_verified_at',
        'last_login_at',
    ];

    protected $appends = ['status', 'last_login'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'last_login_at'     => 'datetime',
            'is_active'         => 'boolean',
        ];
    }

    public function scopeBusinessAccounts($query)
    {
        $hasRoleColumn    = Schema::hasColumn($this->getTable(), 'role');
        $adminPortalEmail = env('ADMIN_PORTAL_EMAIL', 'admin@mylaundrypos.com');

        return $query->where(function ($q) use ($hasRoleColumn, $adminPortalEmail) {
            if ($hasRoleColumn) {
                $q->whereIn('role', ['Super Admin', 'Admin']);
            } else {
                $allowedEmails = array_values(array_filter(array_map('trim', explode(',', (string) $adminPortalEmail))));

                if (! empty($allowedEmails)) {
                    $q->whereIn('email', $allowedEmails);
                }
            }

            $q->orWhereHas('laundries');
        });
    }

    public function getStatusAttribute()
    {
        if ($this->email_verified_at === null) {
            return 'Unverified';
        }

        $isActive = Schema::hasColumn($this->getTable(), 'is_active') ? (bool) $this->is_active : true;

        if (! $isActive) {
            return 'Inactive';
        }

        $orderCount = $this->orders_count ?? $this->orders()->count();

        if ($orderCount > 5 && $this->orders()->where('orders.created_at', '>=', Carbon::now()->subMonth())->exists()) {
            return 'Active';
        }

        if ($orderCount === 0) {
            return 'Verified Without Orders';
        }

        if ($orderCount === 1) {
            return 'One Time Order Only';
        }

        if ($this->orders()->where('orders.created_at', '>=', Carbon::now()->subMonths(2))->doesntExist()) {
            return 'Dormant';
        }

        return 'Other';
    }

    public function getLastLoginAttribute()
    {
        return $this->last_login_at?->format('d M Y, h:i A');
    }

    public function laundries()
    {
        return $this->hasMany(Laundry::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(
            Order::class,
            Laundry::class,
            'user_id',
            'laundry_id',
            'id',
            'id'
        );
    }

    public function customers()
    {
        return $this->hasManyThrough(
            Customer::class,
            Laundry::class,
            'user_id',
            'laundry_id',
            'id',
            'id'
        );
    }

    public function appSetting()
    {
        return $this->hasOne(Setting::class);
    }

    public function user_account()
    {
        return $this->belongsTo(self::class, 'user_account_id');
    }

    public function getTotalRevenueFromUser(): float
    {
        return (float) $this->orders()->sum('total_amount');
    }

    public function whatsappNumber()
    {
        //if number has space remove it
        $whatsapp_number = removeSpaces($this->phone);
        //if number has 0 as first character remove it
        $whatsapp_number = ltrim($whatsapp_number, '0');
        //if number has + remove it
        if (substr($whatsapp_number, 0, 1) === '+') {
            return ltrim($whatsapp_number, '+');
        }
        return '+234' . $whatsapp_number;
    }
}
