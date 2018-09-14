<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    protected $table = 'news_category_tab';
    protected $primaryKey = 'news_category_id';

    public function scopeSelectLocaled($query, $locale)
    {

        $columns = [
            "news_category_id as id",
            "news_category_name_{$locale} as name",
        ];
        return $query->select($columns);
    }
}
