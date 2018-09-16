@extends('index.layout')

@section('content')
    <? use App\Models\View;
    use App\Models\NewsArchiveKz;
    use App\Models\NewsArchiveRu;
    if (App::getLocale() == "kz") {
        $row_next = NewsArchiveKz::select('*', DB::raw('DATE_FORMAT(date,"%d.%m.%Y") as date'))->orderBy("news_id", "desc")->get();
    } else {
        $row_next = NewsArchiveRu::select('*', DB::raw('DATE_FORMAT(date,"%d.%m.%Y") as date'))->orderBy("news_id", "desc")->get();
    }

    $bool = false;
    $next_news_url_name = "";

    if (count($row_next) > 0) {
        foreach ($row_next as $key => $row_next_item) {
            if (strlen(trim($row_next_item['news_title_' . App::getLocale()])) > 0) {
                if ($bool == true) {
                    $next_news_url_name = $row_next_item['news_url_name'];
                    $bool = false;
                }
                if ($row_next_item['news_id'] == $row['news_id']) {
                    $bool = true;
                }
            }
        }
    }
    ?>

    <div class="article-header">
        <div class="col-md-3 col-sm-4 col-np">
            <div class="back link-block">
                <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i
                            class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
            </div>
            <div class="article-text-block">
                <div class="article-publish-date">
                    <i class="icon icon-calendar-green"></i>
                    <small>{{$row['date']}}</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-np">
            @if(strlen($row['youtube_link']) > 0)
                <div class="article-header-img op-darked">
                    <iframe src="{{$row['youtube_link']}}" frameborder="0" allowfullscreen="" width="100%" height="100%"></iframe>
                    @else
                        @if(App::getLocale() == "kz")
                            <div class="article-header-img op-darked" style="background-image: url('/archive_news_kz/{{$row['image']}}'">
                        @else
                            <div class="article-header-img op-darked" style="background-image: url('/archive_news_ru/{{$row['image']}}'">
                        @endif
                    @endif

                            <div class="img-item-content">
                                <h1 class="article-title">
                                    {{$row['news_title_' . App::getLocale()]}}
                                </h1>
                            </div>
                        </div>
                    </div>

                    @if(strlen($next_news_url_name) > 0)
                        <div class="col-md-3 col-np hidden-sm hidden-xs">
                            <div class="forward link-block">
                                <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news-archive/news/" . $next_news_url_name)}}">
                                    <i class="icon icon-arrow-right-g"></i>
                                    <span><?php echo trans("messages.Вперед<br> к следующей новости"); ?></span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="article-content">
                            <?php echo $row['news_text_' . App::getLocale()]; ?>

                            <div class="article-content-bottom">
                                <div class="row">
                                    <div class="article-date col-md-6">
                                        <ul class="list-inline">
                                            <li class="mr-20"><i class="icon icon-calendar-blue mr-5"></i><span>{{$row['date']}}</span></li>

                                        </ul>
                                    </div>

                                    @if(strlen($next_news_url_name) > 0)
                                        <div class="article-bottom-back-link col-md-6">
                                            <a class="text-gray" href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news-archive/news/" . $next_news_url_name)}}">
                                                {{trans("messages.Следующая новость")}}
                                                <i class="icon icon-arrow-right-gg ml-10"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <div class="article-share-buttons">
                                    <style>
                                        .fb-share-button {
                                            margin-top: 0px !important;
                                            float: left !important;
                                        }
                                    </style>
                                    <div>
                                        <div class="fb-share-button"
                                             data-href="{{Request::url()}}"
                                             data-layout="button_count">
                                        </div>

                                        <div style="float: left; margin-left: 10px; margin-top: 0px;">
                                            <a href="https://twitter.com/share" class="twitter-share-button"
                                               data-via="almaty.tv" data-count="none" style="margin-top: -10px;">Tweet</a>
                                            <script>!function (d, s, id) {
                                                    var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                                                    if (!d.getElementById(id)) {
                                                        js = d.createElement(s);
                                                        js.id = id;
                                                        js.src = p + '://platform.twitter.com/widgets.js';
                                                        fjs.parentNode.insertBefore(js, fjs);
                                                    }
                                                }(document, 'script', 'twitter-wjs');</script>
                                        </div>
                                        <div style="clear: both;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            setNewsView();

//            $('.news-text-new').find("p").each(function() {
//                if($(this).find("strong").html() != null){
//                    $(this).addClass("list_paragraph");
//                }
//            });
        });

        function setNewsView() {
            $.ajax({
                type: 'POST',
                url: "/index/set-news-view",
                data: {_token: CSRF_TOKEN, news_id: <?php echo $row['news_id']?>},
                success: function (data) {

                }
            });
        }
    </script>
@endsection