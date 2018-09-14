<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvCategory extends Model
{
    protected $table = 'ev_category_tab';
    protected $primaryKey = 'category_id';

    public function scopeSelectLocaled($query)
    {
        $columns = [
            "category_id",
            "category_name_kz",
            "category_name_ru",
            "image"
        ];
        return $query->select($columns);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_category_tab', 'category_id', 'event_id');
    }
}
