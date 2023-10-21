<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function productVariant()
    {
        return $this->hasMany('App\Models\ProductVariant');
    }
    public function productImageGallery()
    {
        return $this->hasMany('App\Models\ProductImageGallery');
    }
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id', 'id');
    }
}
