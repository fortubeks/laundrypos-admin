<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderServiceItem extends Model
{
    protected $guarded = ['id'];
    protected $table   = 'order_service_item';

    public function serviceItem()
    {
        return $this->belongsTo(ServiceItem::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
