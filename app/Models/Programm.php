<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programm extends Model
{
    protected $table = 'programm_tab';
    protected $primaryKey = 'programm_id';

    public function scopeSelectMain($query, $locale)
    {
        $columns = [
            "programm_id as id",
            "programm_name_ru as title",
            "main_image",
            "main_description_{$locale} as description",
            "programm_logo as logo",
            "is_main",
            "programm_url_name as url",
            "time",
            "day_week_{$locale} as day_week",
            "main_order_num",
            "show_link",
            "link_videoarchive_{$locale} as link_videoarchive",
            "link_programm_{$locale} as link_programm"
        ];
        $status = "is_main";
        return $query->where($status, true)->select($columns);
    }
}
