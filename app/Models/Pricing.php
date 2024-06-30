<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    use HasFactory;
    protected $table = "pricing";
    protected $primaryKey = "id_pricing";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'name', 'description', 'limit', 'price'
    ];
}