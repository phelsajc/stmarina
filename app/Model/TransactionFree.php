<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TransactionFree extends Model
{
    protected $table = "transaction_free";

    protected $primaryKey = "id";

    public $timestamps = false;

    protected $fillable = [
        'id',
        'referenceno',
        'invoiceno',
        'companyid',
        'created_by',
        'created_dt',
        'status',
        'total',
        'transactiondate',
    ];
}
