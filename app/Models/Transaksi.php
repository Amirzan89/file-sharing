<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = "transaksi";
    protected $primaryKey = "id_transaksi";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'order_id', 'price', 'status', 'start_end', 'end_date', 'id_user', 'id_pricing'
    ];
}