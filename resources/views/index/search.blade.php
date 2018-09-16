@extends('index.layout')

@section('content')
    <?php
    function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    use App\Models\View;
    use App\Models\News;
    use App\Models\Advertisement;
    $row_new = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
            ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_en', 'programm_tab.programm_name_kz',
                    DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
            ->where("news_tab.is_active","=","1")
            ->orderByRaw("news_tab.date desc")
            ->get();
    $i = 0;
    $footer_advertisement_list = Advertisement::where("is_main_advertisement","=","2")->where("is_active","=","1")->first();
    ?>

    <div class="page-header">
        <div class="back-link-block">
            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}">
                <i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
        </div>

        <div class="page-title-block">
            <h1 class="page-title">{{trans("messages.Результаты поиска")}}</h1>

            <form action="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/search") }}" class="search-form" method="get">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-search" style="line-height: 0"></i>
                    </span>
                    <input type="hidden" value="1" name="search_type">
                    <input type="hidden" value="{{App::getLocale()}}" name="lang">

                    <input class="form-control" placeholder="{{trans("messages.Поиск")}}..." type="text" name="search_text" value="{{$search_text}}">
                </div>
            </form>
        </div>
    </div>

    <div class="search-results v-tabs">
        <aside>
            <ul class="v-tab-list">
                <li class="news-li">
                    <a style="cursor: pointer;" onclick="showNewsBlock()">{{trans("messages.Новости")}}</a>
                </li>

                <li class="video-li">
                    <a style="cursor: pointer;" onclick="showVideoBlock()">{{trans("messages.Видео")}}</a>
                </li>
            </ul>
        </aside>

        @if(count($row) > 0)
            <script>
                $(document).ready(function(){
                    showNewsBlock();
                });

            </script>
        @else
            <script>
                $(document).ready(function(){
                    showVideoBlock();
                });
            </script>
        @endif
        <script>

            function showNewsBlock(){
                $(".video-search-block").css("display","none");
                $(".video-li").removeClass("active");
                $(".news-li").addClass("active");
                $(".news-search-block").css("display","block");
            }

            function showVideoBlock(){
                $(".news-search-block").css("display","none");
                $(".news-li").removeClass("active");
                $(".video-li").addClass("active");
                $(".video-search-block").css("display","block");
            }
        </script>


        <article class="article-news">
            <form action="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/search") }}" class="search-form" method="get">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-search icon-search"></i>
                    </span>

                    <input type="hidden" value="1" name="search_type">
                    <input type="hidden" value="{{App::getLocale()}}" name="lang">
                    <input class="form-control" placeholder="Поиск..." type="text" name="search_text" value="{{$search_text}}">
                </div>
            </form>

            <div class="news-search-block">
                <div class="search-results-top">
                    <span class="search-text">{{trans("messages.Результаты поиска")}}: {{$search_text}}</span><span>{{trans("messages.Найдено результатов")}}: {{count($row_all)}}</span>
                </div>


                <div class="row">

                    @if(count($row) > 0)
                        @foreach($row as $key => $row_news_item)

                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="news-item">
                                    <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $row_news_item->news_url_name) }}">
                                        @if(strlen($row_news_item->image) > 0)
                                            <div class="news-item-img" style="background-image: url('/news_photo/{{$row_news_item->image}}')">
                                        @else
                                            <div class="news-item-img">
                                        @endif
                                            <?php $view_row = View::where("news_id","=",$row_news_item['news_id'])->get(); ?>
                                            <div class="img-item-content">
                                                <span class="news-meta news-views">
                                                    <i class="icon icon-eye-white"></i>
                                                    <span>{{ count($view_row) }}</span>
                                                </span>
                                                <span class="news-meta news-date"><i class="icon icon-calendar-white"></i><span>{{$row_news_item['date']}}</span></span>
                                            </div>
                                        </div>
                                    </a>

                                    <div class="news-item-title">
                                        <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $row_news_item->news_url_name) }}">{{$row_news_item['news_title_' . App::getLocale()]}}</a>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    @endif

                </div>

                <nav aria-label="Page navigation" class="text-center">
                    @if(count($row) > 0)
                        {!! $row->appends(request()->input())->links() !!}
                    @endif
                </nav>
            </div>

            <div class="video-search-block" style="display: none;">
                <div class="search-results-top">
                    <span class="search-text">{{trans("messages.Результаты поиска")}}: {{$search_text}}</span><span>{{trans("messages.Найдено результатов")}}: {{count($row_archive_all)}}</span>
                </div>

                <div class="row">

                    @if(count($row_archive) > 0)
                        @foreach($row_archive as $key => $row_archive_item)
                            @if(strlen(trim($row_archive_item['video_archive_title_' . App::getLocale()])) > 0)
                                @if(strlen($row_archive_item['programm_url_name']) < 1)
                                    <? $row_archive_item['programm_url_name'] = "default"; ?>
                                @endif

                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="news-item">
                                        <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/archive/" . $row_archive_item['programm_url_name'] . "/" . $row_archive_item['video_archive_url_name']) }}">
                                            @if(strlen($row_archive_item->image) > 0)
                                                <div class="news-item-img" style="background-image: url('/video_archive_photo/<?php echo $row_archive_item->image; ?>')">
                                            @else
                                                <div class="news-item-img" style="background-image: url('/css/images/videoarchive.jpg')">
                                            @endif
                                                <span class="news-meta news-views">
                                                    <i class="icon icon-eye-white"></i>
                                                    <span>
                                                        <?php
                                                        if(strlen($row_archive_item['youtube_video_code']) > 0){
                                                            if(get_http_response_code("https://www.googleapis.com/youtube/v3/videos?part=statistics&id=" . $row_archive_item['youtube_video_code'] . "&key=AIzaSyA_bs_UFM6LK955j93P9pjoPfECoHMMwx0") != "200"){
                                                            }
                                                            else{
                                                                $json_youtube = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics&id=" . $row_archive_item['youtube_video_code'] . "&key=AIzaSyA_bs_UFM6LK955j93P9pjoPfECoHMMwx0");
                                                                $json_youtube_data = json_decode($json_youtube, true);
                                                                if(count($json_youtube_data['items']) > 0){
                                                                    echo $json_youtube_data['items'][0]['statistics']['viewCount'];
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </span>
                                                </span>
                                                <span class="news-meta news-date"><i class="icon icon-calendar-white"></i><span>{{$row_archive_item['video_archive_date']}}</span></span>
                                            </div>
                                        </a>

                                        <div class="news-item-title">
                                            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/archive/" . $row_archive_item['programm_url_name'] . "/" . $row_archive_item['video_archive_url_name']) }}">{{$row_archive_item['video_archive_title_' . App::getLocale()]}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        @endforeach
                    @endif

                </div>

                <nav aria-label="Page navigation" class="text-center">
                    @if(count($row_archive) > 0)
                        {!! $row_archive->appends(request()->input())->links() !!}
                    @endif
                </nav>
            </div>
        </article>
    </div>

@endsection