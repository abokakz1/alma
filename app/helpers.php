<?php
use App\Models\News;
use App\Models\Blog;
use App\Models\Event;
use Jenssegers\Date\Date;
use DB;

public function correctFav($row){
    foreach ($row as $item) {
        if($item->type=='news'){
            $source = News::select(DB::raw('news_title_ru as title_ru'), DB::raw('news_title_kz as title_kz'), DB::raw('news_url_name as url'), 'date')->where('news_id', $item->source_id)->first();
            $item->url = '/news/news/'.$source->url;
            $item->type_ru = 'Новости';
        } else if($item->type=='blogs'){
            $source = Blog::select(DB::raw('blog_title_kz as title_kz'),DB::raw('blog_title_ru as title_ru'), DB::raw('blog_url_name as url'), 'date')->where('blog_id', $item->source_id)->first();
            $item->url = '/blogs/'.$source->url;
            $item->type_ru = 'Блог';
        } else {
            $source = Event::select(DB::raw('event_title as title_kz'), DB::raw('event_url_name as url'),DB::raw('date_start as date'))->where('event_id', $item->source_id)->first();
            $item->url = '/events/'.$source->url;
            $item->type_ru = 'События';
        }
        $item->title_kz = $source->title_kz;
        $item->title_ru = $source->title_ru;
        $item->date = Date::parse($source->date)->format('d.m.Y');
    }
    return $row;
}