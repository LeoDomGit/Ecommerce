<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    protected $table='collections';
    protected $fillable=['id','title','slug','status','highlighted','created_at','updated_at'];
}
