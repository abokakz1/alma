<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsArchiveKz extends Model
{
    protected $table = 'news_archive_kz_tab';
    protected $primaryKey = 'news_id';

    protected $appends = ['view_count'];

    public function getViewCountAttribute(){
        return 0;
    }
}
