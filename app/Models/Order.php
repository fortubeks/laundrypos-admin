<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    public function items()
    {
        return $this->hasMany(OrderServiceItem::class, 'order_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getUserAttribute()
    {
        return $this->laundry?->owner;
    }

    public function getOutstandingBalance()
    {
        return $this->total_amount - ($this->payments ? $this->payments->sum('amount') : 0);
    }

    public function getAmountPaid()
    {
        return $this->payments ? $this->payments->sum('amount') : 0;
    }
}
