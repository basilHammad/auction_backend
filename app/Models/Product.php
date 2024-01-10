<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\College;


class Product extends Model
{
    use HasFactory;

      protected $fillable = [
        'name',
        'slug',
        'description',
        'start_price',
        'current_price',
        'final_price',
        'img',
        'college_id',
        'start_date',
        'end_date',
        'status',
        
    ];

      public function college()
    {
        return $this->belongsTo(College::class);
    }
}
