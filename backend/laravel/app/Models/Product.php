<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable =  ['name','description', 'slug', 'creator', 'price'];
    protected $hidden = ['creator'];


    public function user()
    {
        return $this->belongsTo(User::class, 'creator');
    }
}
