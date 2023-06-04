<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Collectibles extends Model
{
    protected $table = "collectibles";

    protected $primaryKey = "id";

    public $timestamps = false;

    protected $fillable = [
        'id',
        'collection_id',
        'transaction_id',
        'companyid',
    ];
}
