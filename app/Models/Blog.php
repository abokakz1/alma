<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;

class Blog extends Model
{
    protected $table = 'blog_tab';
    protected $primaryKey = 'blog_id';
    protected $appends = ['images', 'datetime'];


    public function scopeSelectLocaled($query, $locale)
    {

        $columns = [
            "blog_id as id",
            "author_id",
            "date",
            "view_count",
            "blog_title_{$locale} as title",
            "blog_text_{$locale} as text",
            "blog_url_name as url",
            "image",
            "video_url as video",
            "is_active_{$locale}",
            "blog_meta_desc"
        ];
        $status = "is_active_{$locale}";
        return $query->where($status, true)->select($columns);
    }

    public function author()
    {
        return $this->belongsTo(Users::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tag_tab', 'blog_id', 'tag_id');
    }

    public function getImagesAttribute() {

        if ($this->image) {
            $imageName = $this->getOriginal('image');

            $firstPart = substr( $imageName, 0, strrpos( $imageName, '.' ) );

            $secondPart = substr($imageName, strrpos($imageName, '.') + 1);

            return collect([
                'cropped' =>  '/blog_photo/' . $firstPart.'-300x300.'.$secondPart
            ]);
        }

        return null;
    }

    public function getImageAttribute($value)
    {
        return '/blog_photo/' . $value;
    }

    public function getUrlAttribute($value)
    {
        return '/blogs/' . $value;
    }

    public function getDateAttribute($value)
    {
        return Date::parse($value)->toFormattedDateString();
    }

    public function getDatetimeAttribute()
    {
        return Date::parse($this->getOriginal('date'))->format('d.m.Y H:i:s');
    }

    // 
}
