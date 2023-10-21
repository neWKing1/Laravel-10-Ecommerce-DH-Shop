<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }
    public function subCategory()
    {
        return $this->belongsTo('App\Models\SubCategory', 'sub_category_id', 'id');
    }
}
