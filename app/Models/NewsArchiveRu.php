<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsArchiveRu extends Model
{
    protected $table = 'news_archive_ru_tab';
    protected $primaryKey = 'news_id';

    protected $appends = ['view_count'];

    public function getViewCountAttribute(){
        return 0;
    }
}
