<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;

class Event extends Model
{
    protected $table = 'event_tab';
    protected $primaryKey = 'event_id';
    protected $appends=['time', 'date', 'image', 'url'];


    public function scopeSelectLocaled($query, $locale)
    {

        $columns = [
            "event_id",
            "date_start",
            "date_end",
            "event_title as title",
            "event_text as text",
            "event_url_name",
            "event_meta_desc",
            "image_id",
            "price",
            "address",
            "organizer_name",
            "organizer_email",
            "organizer_number",
            "lat","lng"
        ];
        // $status = "is_active_{$locale}";
        // return $query->where($status, true)->select($columns);
        return $query->select($columns);
    }

    public function categories()
    {
        return $this->belongsToMany(EvCategory::class, 'event_category_tab', 'event_id', 'category_id');
    }

    public function blogers()
    {
        return $this->belongsToMany(Users::class, 'Bloger_event_tab', 'event_id', 'bloger_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'event_id', 'event_id');
    }

    public function getTimeAttribute() {
        return Date::parse($this->date_start)->format('H:m');
    }

    public function getDateAttribute() {
        return Date::parse($this->date_start)->format('j F');
    }

    public function getImageAttribute() {
        return ($this->media->count()>0 ? '/event_photo/'.$this->media[0]->link : '/event_photo/event_def.jpg');
    }

    public function getUrlAttribute() {
        return '/events/' . $this->event_url_name;
    }
}
