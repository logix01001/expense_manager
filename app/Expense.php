<?php

namespace App;

use App\Category;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];

    protected $appends = ['category_name'];

    public function getCategoryNameAttribute(){
        $category = Category::find($this->category_id_fk);

        return $category->name;
    }
}
