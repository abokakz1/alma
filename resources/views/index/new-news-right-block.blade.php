<?php
    use App\Models\View;
    use App\Models\News;
    $row_new = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                    ->select('news_tab.*','programm_tab.programm_name_ru',
                            DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                    ->where("news_tab.is_active","=","1")
                    ->orderByRaw("news_tab.date desc")
                    ->take(5)
                    ->get();
?>
<div class="row first_news">
    <div class="col-md-12">
        <h3><b>{{trans("messages.Последние новости")}}</b></h3>
        @if(count($row_new) > 0)
            @foreach($row_new as $key => $row_new_item)
                <p class="points">...</p>
                <p>
                    <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $row_new_item->news_url_name) }}">
                        <b>{{ $row_new_item['news_title_' . App::getLocale()] }}</b>
                    </a>
                </p>
                <div class="ran_news">
                    <p>
                        <img src="/css/images/eye.png" align="middle" alt="">
                        <?php $view_row = View::where("news_id","=",$row_new_item['news_id'])->get(); ?>
                        {{ count($view_row) }}
                    </p>
                    <p><img src="/css/images/calendar_blue.png" align="middle" alt=""> {{$row_new_item['date']}}</p>
                </div>
            @endforeach
        @endif
    </div>
</div>

<!--MORE_NEWS--->
<div class="row more_news columnFooter">
    <h5><img src="/css/images/radio.png" align="middle" alt=""><b><a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/") }}">{{trans("messages.Перейти ко всем новостям")}}</a></b></h5>
</div>
<div class="row">
    <a href=""><img src="images/reklam.png" class="img-responsive" alt=""></a>s
</div>
<!--END_MORE_NEWS--->