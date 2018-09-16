<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Laravel 5</div>
            </div>
        </div>
    </body>
</html>

<a href="{{  LaravelLocalization::getLocalizedURL("kz", "/")}}">На главную</a>
<br>
<a href="{{  LaravelLocalization::getLocalizedURL("kz", Request::url()) }}">Казахский</a>
<a href="{{  LaravelLocalization::getLocalizedURL("en", Request::url()) }}">Английский</a>
<a href="{{  LaravelLocalization::getLocalizedURL("ru", Request::url()) }}">Русский</a>
<br>
<a href="/admin/login">Вход</a>
<br>
<a href="{{  LaravelLocalization::getLocalizedURL(App::getLocale(), '/news/') }}">Новости</a>
<br>
<a href="{{  LaravelLocalization::getLocalizedURL(App::getLocale(), '/programma-peredach/') }}">Программа передач</a>
<br>
<a href="/rss">RSS</a>



<br>
@if(count($row) > 0)
@foreach($row as $key => $news_item)
<a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $news_item->news_url_name) }}">
{{ $news_item['news_title_' . App::getLocale()] }}
</a>
<br>
@endforeach
@endif


<br>
{{ $row['news_title_' . App::getLocale()] }}
<br>

<?php echo $row['news_text_' . App::getLocale()]; ?>













        <!--CONTENT-->
<div class="content">
    <div class="container-fluid">
        <div class="row columnsRow">
            <!--CONTENT_LEFT_COMP-->
            <div class="column col-md-7 main_news hidden-sm hidden-xs">
                @if(count($main_news_row) > 0)
                    <?php $i = 0; ?>
                    @foreach($main_news_row as $key => $main_news_item)
                        <?php $i++; ?>
                        @if($i == 1)
                            <div class="row news_box">
                                <div class="col-md-4" style="padding-left:30px;">
                                    @if(strlen($main_news_item['programm_name_' . App::getLocale()]) > 0)
                                        <h4>{{$main_news_item['programm_name_' . App::getLocale()]}}</h4>
                                    @endif

                                    <h5>
                                        <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">
                                            {{$main_news_item['news_title_' . App::getLocale()]}}
                                        </a>
                                    </h5>
                                    <br><br><br>
                                    <h5 class="data">
                                        <img src="/css/images/Calendar-.png" align="middle" alt="">
                                        {{$main_news_item['date']}}
                                    </h5>
                                </div>

                                <div class="col-md-4 news_box_img">
                                    <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">
                                        <img src="/news_photo/{{$main_news_item->image}}" alt="" class="img_new">
                                    </a>
                                </div>
                                <div class="col-md-4 news_box_img_reklam">
                                    <a href=""><img src="/css/images/new2.png" alt="" class="img_new"></a>
                                </div>
                            </div>

                        @elseif($i == 2)
                            <div class="row news_box">
                                <div class="col-md-4 news_box_img1">
                                    <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">
                                        <img src="/news_photo/{{$main_news_item->image}}" alt="" class="img_new">
                                    </a>
                                    <div class="blue_news">
                                        @if(strlen($main_news_item['programm_name_' . App::getLocale()]) > 0)
                                            <h4 style="color: #1d6ab0;">{{$main_news_item['programm_name_' . App::getLocale()]}}</h4>
                                        @endif
                                        <h5>
                                            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">
                                                {{$main_news_item['news_title_' . App::getLocale()]}}
                                            </a>
                                        </h5>
                                        <h5 class="data">
                                            <img src="/css/images/calendar_blue.png" align="middle" alt="">{{$main_news_item['date']}}
                                        </h5>
                                    </div>
                                </div>
                                @elseif($i == 3)
                                    <div class="col-md-4 news_box_img">
                                        <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">
                                            <img src="/news_photo/{{$main_news_item->image}}" alt="" class="img_new">
                                        </a>
                                    </div>
                                    <div class="col-md-4" style="padding-left:30px;">
                                        <h6 class="data">
                                            <img src="/css/images/Calendar-.png" align="middle" alt="">
                                            {{$main_news_item['date']}}
                                        </h6>
                                        @if(strlen($main_news_item['programm_name_' . App::getLocale()]) > 0)
                                            <h4>{{$main_news_item['programm_name_' . App::getLocale()]}}</h4>
                                        @endif
                                        <h5>
                                            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">
                                                {{$main_news_item['news_title_' . App::getLocale()]}}
                                            </a>
                                        </h5>
                                    </div>
                            </div>
                        @endif
                    @endforeach
            </div>
            @endif

                    <!--BOTTOM_NEWS-->
            <div class="row bg_grey ">
                <div class="khabar clearfix">
                    <div class="col-md-4 ">
                        <a href="" class="khabar_kz">www.khabar.kz</a>
                        <p><b><a href="">Глава государства подписал ряд документов   Любое использование</a></b></p>
                        <div class="ran_news">
                            <p class="data"><img src="/css/images/eye.png" align="middle" alt=""> 345</p>
                            <p><img src="/css/images/calendar_blue.png" align="middle" alt=""> 29/02/2016</p>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <a href="" class="khabar_kz">www.khabar.kz</a>
                        <p><b><a href="">Нурсултан Назарбаев проголосовал на избирательном участке №81 </a></b></p>
                        <div class="ran_news">
                            <p class="data"><img src="/css/images/eye.png" align="middle" alt=""> 345</p>
                            <p><img src="/css/images/calendar_blue.png" align="middle" alt=""> 29/02/2016</p>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <a href="" class="khabar_kz">www.khabar.kz</a>
                        <p><b><a href="">В Семее при пожаре двое студентов спасли детей  Источник: http:</a></b></p>
                        <div class="ran_news">
                            <p class="data"><img src="/css/images/eye.png" align="middle" alt=""> 345</p>
                            <p><img src="/css/images/calendar_blue.png" align="middle" alt=""> 29/02/2016</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--END_BOTTOM_NEWS-->
            <!--ABOUT_CINEMA-->
            <div class="row bg_footer columnFooter ">
                <div class="col-md-12">
                    <div class="cinema">
                        <p class="left_text">АМЕРИКАНСКАЯ
                            <br>ИСТОРИЯ УЖАСОВ</p>
                        <p class="right_text">ВСЕГО 5.99$
                            <br> В МЕСЯЦ</p>
                    </div>
                    <img src="/css/images/logo_netflix.png" class="img-responsive center-block" alt="">
                </div>
                <div class="col-md-offset-8 col-md-4 more_version ">
                    <p><a href="">ПРОБНАЯ ВЕРСИЯ</a></p>
                </div>
            </div>
            <!--END_ABOUT_CINEMA-->
        </div>
        <!--END_CONTENT_LEFT_COMP-->

        <!--CONTENT_VERCION_TAB-->
        <div class=" tab_vertion hidden-lg hidden-md hidden-xs">
            <div class=" col-sm-8 img_text column">

                @if(count($main_news_row) > 0)
                    <?php $i = 0; ?>
                    <div class="row news_box">
                        @foreach($main_news_row as $key => $main_news_item)
                            <?php $i++; ?>
                            @if($i == 1)
                                <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}"><img src="/news_photo/{{$main_news_item->image}}" class="center-block img_news" alt=""></a>
                                <div class=" news_text">
                                    @if(strlen($main_news_item['programm_name_' . App::getLocale()]) > 0)
                                        <h3>{{$main_news_item['programm_name_' . App::getLocale()]}}</h3>
                                    @endif
                                    <p><a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">{{$main_news_item['news_title_' . App::getLocale()]}}</a></p>
                                    <p class="data"><img src="/css/images/Calendar-.png" align="middle" alt=""> {{$main_news_item['date']}}</p>
                                </div>
                            @elseif($i == 2)
                                <div class="col-sm-6 img_text">
                                    <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}"><img src="/news_photo/{{$main_news_item->image}}" class="center-block img_news" alt=""></a>
                                    <div class=" news_text">
                                        @if(strlen($main_news_item['programm_name_' . App::getLocale()]) > 0)
                                            <h3>{{$main_news_item['programm_name_' . App::getLocale()]}}</h3>
                                        @endif
                                        <p><a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">{{$main_news_item['news_title_' . App::getLocale()]}}</a></p>
                                        <p class="data"><img src="/css/images/Calendar-.png" align="middle" alt=""> {{$main_news_item['date']}}</p>
                                    </div>
                                </div>
                            @elseif($i == 3)
                                <div class="col-sm-6 img_text">
                                    <div class=" news_text">
                                        @if(strlen($main_news_item['programm_name_' . App::getLocale()]) > 0)
                                            <h3>{{$main_news_item['programm_name_' . App::getLocale()]}}</h3>
                                        @endif
                                        <p><a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">{{$main_news_item['news_title_' . App::getLocale()]}}</a></p>
                                        <p class="data"><img src="/css/images/Calendar-.png" align="middle" alt=""> {{$main_news_item['date']}}</p>
                                    </div>
                                    <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}"><img src="/news_photo/{{$main_news_item->image}}" class="center-block img_news" alt=""></a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="col-sm-12 bg_footer">
                    <div class="cinema">
                        <p class="left_text">АМЕРИКАНСКАЯ
                            <br>ИСТОРИЯ УЖАСОВ</p>
                        <p class="right_text">ВСЕГО 5.99$
                            <br> В МЕСЯЦ</p>
                    </div>
                    <img src="/css/images/logo_netflix.png" class="img-responsive center-block" alt="">
                    <div class="col-sm-offset-8 sm-md-4 more_version">
                        <p><a href="">ПРОБНАЯ ВЕРСИЯ</a></p>
                    </div>
                </div>

            </div>
        </div>
        <!--END_CONTENT_VERCION_TAB-->
        <!--CONTENT_RIGHT_NEWS-->
        <div class="column col-md-2 col-sm-4 list_news">
            @include("index.new-news-right-block")
        </div>
        <!--END_CONTENT_RIGHT_NEWS-->

        <!--CONTENT_MOBAIL_GREY_NEWS-->
        <div class="col-xs-12 mob_version hidden-lg hidden-md hidden-sm ">
            @if(count($main_news_row) > 0)
                <?php $i = 0; ?>
                <div class="row news_box">
                    @foreach($main_news_row as $key => $main_news_item)
                        <?php $i++; ?>
                        @if($i == 1)
                            <div class="row news">
                                <div class="col-xs-6 img_text">
                                    <img src="/news_photo/{{$main_news_item->image}}" class="" alt="">
                                </div>
                                <div class="col-xs-6 news_text">
                                    @if(strlen($main_news_item['programm_name_' . App::getLocale()]) > 0)
                                        <h3>{{$main_news_item['programm_name_' . App::getLocale()]}}</h3>
                                    @endif
                                    <p><a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">{{$main_news_item['news_title_' . App::getLocale()]}}</a></p>
                                    <p class="data"><img src="/css/images/Calendar-.png" align="middle" alt="">{{$main_news_item['date']}}</p>
                                </div>
                            </div>
                        @elseif($i == 2)
                            <div class="row news">
                                <div class="col-xs-6 news_text">
                                    @if(strlen($main_news_item['programm_name_' . App::getLocale()]) > 0)
                                        <h3>{{$main_news_item['programm_name_' . App::getLocale()]}}</h3>
                                    @endif
                                    <p><a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">{{$main_news_item['news_title_' . App::getLocale()]}}</a></p>
                                    <p class="data"><img src="/css/images/Calendar-.png" align="middle" alt="">{{$main_news_item['date']}}</p>
                                </div>
                                <div class="col-xs-6 img_text">
                                    <img src="/news_photo/{{$main_news_item->image}}" class="" alt="">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

        </div>
        <!--CONTENT_MOBAIL_GREY_NEWS-->

        <!--CONTENT_RIGHT-->
        <div class="column col-md-3 abstract hidden-sm hidden-xs">
            <div class="row abstract1">
                <div class="col-md-12">
                    <h4>9:00 <span> Уже прошел</span></h4>
                    <h3><a href="">Место Под Солнцем</a></h3>
                    <h4>Социальные +16</h4>
                </div>
            </div>
            <div class="row abstract6 active">
                <div class="col-md-12">
                    <h4>В эфире</h4>
                    <h3><a href="">спорт</a></h3>
                    <h4>Социальные +16</h4>
                </div>
            </div>
            <div class="row abstract2">
                <div class="col-md-12">
                    <h4>15:00<span> Скоро</span></h4>
                    <h3><a href="">IV власть</a></h3>
                    <h4>Социальные +16</h4>
                </div>
            </div>
            <div class="row abstract3">
                <div class="col-md-12">
                    <h4>17:00 <span> Скоро</span></h4>
                    <h3><a href="">деловое время</a></h3>
                    <h4>Социальные +16</h4>
                </div>
            </div>
            <div class="row abstract4">
                <div class="col-md-12">
                    <h4>21:00 <span> Скоро</span></h4>
                    <h3><a href="">вечерний гость</a></h3>
                    <h4>Социальные +16</h4>
                </div>
            </div>
            <div class="row abstract5">
                <div class="col-md-12">
                    <h4>9:00 <span> Скоро</span></h4>
                    <h3><a href="">Алматинская неделя</a></h3>
                    <h4>Социальные +16</h4>
                </div>
            </div>
            <div class="row more_program columnFooter">
                <h5><a href=""><img src="/css/images/radio.png" align="middle" alt=""><b style="color:#000">Перейти ко всем новостям</b></a></h5>
            </div>
        </div>
        <!--END_CONTENT_RIGHT-->
    </div>
</div>
</div>
<!--END_CONTENT-->

<!--ABAOUT_CINEMA_MOBAIL_VERCION-->
<div class="footer hidden-lg hidden-md hidden-sm ">
    <div class="container-fluid">
        <div class="row bg_footer">
            <div class="col-xs-3 text_name_filme">
                <h6>АМЕРИКАНСКАЯ <br>ИСТОРИЯ УЖАСОВ</h6>
            </div>
            <div class="col-xs-6">
                <p>Ваши любимые фильмы, сериалы и телешоу всегда с вами</p>
                <img src="/css/images/logo_netflix.png" class="img-responsive center-block" alt="">
                <p>в любое время, где бы вы не находились. </p>
            </div>
            <div class="col-xs-3 text_summa">
                <h6>ВСЕГО 5.99$ <br>В МЕСЯЦ</h6>
            </div>
            <div class="col-xs-offset-8 col-xs-4 more_version">
                <h5><b><a href="">ПРОБНАЯ ВЕРСИЯ</a></b></h5>
            </div>
        </div>
    </div>
</div>
<!--END_ABAOUT_CINEMA_MOBAIL_VERCION-->
