<?php
namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }

    public static function getPayments($order_id)
    {
        $payments = Payment::where('order_id', $order_id)->paginate(10);
        return $payments;
    }
}
