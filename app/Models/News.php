<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class News extends Model
{
    protected $table = 'news_tab';
    protected $primaryKey = 'news_id';

    protected $appends = ['is_kz_has', 'is_ru_has'];

    protected $casts = [
        'view_count' => 'int',
    ];


    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function getIsKzHasAttribute(){
    	return $this->news_text_kz ? 1 : 0;
    }

    public function getIsRuHasAttribute(){
    	return $this->news_text_ru ? 1 : 0;
    }

    // public function getNewsTextKzAttribute(){
    //     return $this->news_text_kz ? $this->news_text_kz : $this->news_text_ru;
    // }

    // public function getNewsTextRuAttribute(){
    //     return $this->news_text_ru ? $this->news_text_ru : $this->news_text_kz;
    // }
}
