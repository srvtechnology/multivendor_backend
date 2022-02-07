<?php

namespace App\Models;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    protected $guarded = [];

    public function category()
    {
    	return $this->hasOne(Category::class,'id','category_id');
    }

    public function subcategory()
    {
    	return $this->hasOne('App\Models\Subcategory','id','subcategory_id');
    }
}
