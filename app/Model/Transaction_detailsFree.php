<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Transaction_detailsFree extends Model
{
    protected $table = "transaction_details_free";

    protected $primaryKey = "id";

    public $timestamps = false;

    protected $fillable = [
        'id',
        'product_id',
        'product',
        'transaction_details',
        'qty',
        'total',
        'price',
    ];
}
