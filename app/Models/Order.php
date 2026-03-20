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

    public function getUserAttribute()
    {
        return $this->laundry?->owner;
    }
}
