<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Nếu cần thiết lập mối quan hệ ngược lại
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
