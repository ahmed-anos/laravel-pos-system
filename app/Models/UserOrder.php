<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOrder extends Model
{

    protected $fillable=[
        'user_id',
        'order_number',
        'table_number',
        'products',
        'total_price',
        'user_name'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts=[
        'products'=>'array'
    ];
}
