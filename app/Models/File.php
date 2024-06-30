<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $table = "file";
    protected $primaryKey = "id_file";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'name', 'path', 'size', 'mime_type', 'id_folder', 'id_user'
    ];
}