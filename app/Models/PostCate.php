<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCate extends Model
{
    use HasFactory;
    protected $table='collections';
    protected $fillable=['id','title','slug','status','highlighted','created_at','updated_at'];
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
