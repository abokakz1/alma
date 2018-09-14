<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    protected $table = 'employer_tab';
    protected $primaryKey = 'id';

    public function scopeSelectLocaled($query, $locale)
    {

        $columns = [
            "id",
            "name",
            "position_{$locale} as position",
            "description_{$locale} as description",
            "mail",
            "number",
            "image",
            "order",
            "menu_item_id",
            "fb", "insta", "telegram", "vk", "youtube"
        ];
        return $query->select($columns);
    }

    public function job_position()
    {
        return $this->belongsTo(JobPosition::class, 'job_position_id');
    }
}
