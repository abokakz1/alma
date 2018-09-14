<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $table = 'job_position_tab';
    protected $primaryKey = 'id';

    public function scopeSelectLocaled($query, $locale)
    {
        $columns = [
            "id",
            "name_{$locale} as name"
        ];
        return $query->select($columns);
    }

    public function employers()
    {
        return $this->hasMany(Employer::class, 'job_position_id', 'id');
    }
}
