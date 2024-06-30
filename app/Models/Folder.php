<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;
    protected $table = "folder";
    protected $primaryKey = "id_folder";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'name', 'parent_id', 'id_user'
    ];
}