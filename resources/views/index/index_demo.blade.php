@extends('index.layout')

@section('content')
    <link href="{{ asset('css/events_style.css') }}" rel="stylesheet">
    <?php 
    use App\Models\Advertisement;
    ?>
    <!-- Facebook Popup Widget START --><!-- Brought to you by www.JasperRoberts.com - www.TheBlogWidgets.com -->
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js' type='text/javascript'></script>
    <style>
        #fanback {
            display:none;
            background:rgba(0,0,0,0.8);
            width:100%;
            height:100%;
            position:fixed;
            top:0;
            left:0;
            z-index:99999;
        }
        #fan-exit {
            width:100%;
            height:100%;
        }
        #JasperRoberts {
            background:white;
            width:420px;
            height:270px;
            position:absolute;
            top:58%;
            left:63%;
            margin:-220px 0 0 -375px;
            -webkit-box-shadow: inset 0 0 50px 0 #939393;
            -moz-box-shadow: inset 0 0 50px 0 #939393;
            box-shadow: inset 0 0 50px 0 #939393;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            margin: -220px 0 0 -375px;
        }
        #TheBlogWidgets {
            float:right;
            cursor:pointer;
            background:url(http://3.bp.blogspot.com/-NRmqfyLwBHY/T4nwHOrPSzI/AAAAAAAAAdQ/8b9O7O1q3c8/s1600/TheBlogWidgets.png) repeat;
            height:15px;
            padding-top: 20px;
            padding-right: 40px;
            padding-bottom: 35px;
            padding-left: 20px;
            position:relative;
            margin-top:-20px;
            margin-right:-22px;
        }
        .remove-borda {
            height:0px;
            width:366px;
            margin:0 auto;
            background:#F3F3F3;
            margin-top:16px;
            position:relative;
            margin-left:20px;
        }
        #linkit,#linkit a.visited,#linkit a,#linkit a:hover {
            color:#80808B;
            font-size:10px;
            margin: 0 auto 5px auto;
            float:center;
        }
    </style>
    <script type='text/javascript'>
        //<![CDATA[
        jQuery.cookie = function (key, value, options) {
// key and at least value given, set cookie...
            if (arguments.length > 1 && String(value) !== "[object Object]") {
                options = jQuery.extend({}, options);
                if (value === null || value === undefined) {
                    options.expires = -1;
                }
                if (typeof options.expires === 'number') {
                    var days = options.expires, t = options.expires = new Date();
                    t.setDate(t.getDate() + days);
                }
                value = String(value);
                return (document.cookie = [
                    encodeURIComponent(key), '=',
                    options.raw ? value : encodeURIComponent(value),
                    options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                    options.path ? '; path=' + options.path : '',
                    options.domain ? '; domain=' + options.domain : '',
                    options.secure ? '; secure' : ''
                ].join(''));
            }
// key and possibly options given, get cookie...
            options = value || {};
            var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
            return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
        };
        //]]>
    </script>
    <script type='text/javascript'>
        jQuery(document).ready(function($){
            if($.cookie('popup_user_login') != 'yes'){
                $('#fanback').delay(10000).fadeIn('medium');
                $('#TheBlogWidgets, #fan-exit').click(function(){
                    $('#fanback').stop().fadeOut('medium');
                });
            }
            $.cookie('popup_user_login', 'yes', { path: '/', expires: 7 });
        });
    </script>
    <div id='fanback'>
        <div id='fan-exit'>
        </div>
        <div id='JasperRoberts'>
            <div id='TheBlogWidgets'>
            </div>
            <div class='remove-borda'>
            </div>
            @if(App::getLocale()=="ru")<iframe allowtransparency='true' frameborder='0' scrolling='no' src='//www.facebook.com/plugins/likebox.php?
href=http://www.facebook.com/almaty.tv&width=402&height=255&colorscheme=light&show_faces=true&show_border=false&stream=false&header=false'
                                               style='border: none; overflow: hidden; margin-top: -19px; margin-left: 9px; width: 402px; height: 230px;'></iframe><center>
                @else<iframe allowtransparency='true' frameborder='0' scrolling='no' src='//www.facebook.com/plugins/likebox.php?
href=http://www.facebook.com/almaty.tv.kaz&width=402&height=255&colorscheme=light&show_faces=true&show_border=false&stream=false&header=false'
                             style='border: none; overflow: hidden; margin-top: -19px; margin-left: 9px; width: 402px; height: 230px;'></iframe><center>@endif
                <span style="color:#a8a8a8;font-size:8px;" id="linkit">Facebook popup widget</span></center>
        </div>
    </div>
    <!-- Facebook Popup Widget END. Brought to you by www.JasperRoberts.com - www.TheBlogWidgets.com -->
    <div class="container-fluid bg-gray-light">
        <div class="row">
            <div class="col-md-9 col-sm-12 col-np">
                <div class="homepage-blocks">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="carousel slide" data-interval="6000" data-ride="carousel" id="homepage-news-carousel">
                                <ol class="carousel-indicators">
                                    @if(count($kz_news_row) > 0)
                                        <?php $i = 0; ?>
                                        @foreach($kz_news_row as $key => $main_news_item)
                                            <?php $i++; ?>
                                            <li @if($i == 1) class="active" @endif data-slide-to="{{$i-1}}" data-target="#homepage-news-carousel"></li>                
                                        @endforeach
                                    @endif
                                </ol>
                                <div class="carousel-inner" role="listbox">
                                    @if(count($kz_news_row) > 0)
                                        <?php $i = 0; ?>
                                        @foreach($kz_news_row as $key => $main_news_item)
                                            
                                                    <?php $i++; ?>
                                                    

                                                    <div class="item @if($i == 1) active @endif">
                                                        <div class="card card-lg card-fixed">
                                                            @if(strlen($main_news_item->image) > 0)
                                                                <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">
                                                                    <div class="card-img" style="background-image: url(/news_photo/{{$main_news_item->image}})"></div>
                                                                </a>
                                                            @endif

                                                            <div class="card-body">
                                                                <div class="dv">
                                                                    <span class="date mr-20" style="color: #1d1f1f;">{{$main_news_item['date']}}</span>
                                                                    <span class="views mr-20" style="color: #1d1f1f;">
                                                                        <i class="icon icon-eye-gray mr-5"></i>{{$main_news_item['view_count']}}
                                                                        @if($main_news_item['is_has_foto'] > 0)
                                                                            <i class="icon icon-photo"></i>
                                                                        @endif

                                                                        @if($main_news_item['is_has_video'] > 0)
                                                                            <i class="icon icon-video"></i>
                                                                        @endif
                                                                    </span>
                                                                    @if($main_news_item['is_whatsapp'] > 0)
                                                                        <span class="icon icon-whatsapp-gray"></span>
                                                                    @endif
                                                                </div>
                                                                <ul class="article-meta">
                                                                    <li>
                                                                        <a class="label label-red mr-20">{{$main_news_item['news_category_name_' . App::getLocale()]}}</a>
                                                                    </li>
                                                                </ul>
                                                                <h3 class="card-title">
                                                                    <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">
                                                                        {{$main_news_item['news_title_' . App::getLocale()]}}
                                                                    </a>
                                                                </h3>
                                                                <div class="card-text">
                                                                    {{mb_substr( strip_tags($main_news_item['news_text_' . App::getLocale()]),0,300)}}...
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                            
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="row">
                            @if(isset($two))
                            @foreach($two as $t)
                                @if(isset($t))
                                    <div class="col-md-6 col-sm-6">
                                        <div class="card card-md card-fixed">
                                            <div class="newscard" style="margin-bottom: 0">
                                                        @if(property_exists($t, 'url'))
                                                            <div class="cardbackimg" style="height: 180px;background-image: url(@if($t->image)'{{ asset($t->image) }}'@else'/css/images/blog_default.jpg'@endif)">
                                                            <div class="imgdarkabs">
                                                                <button>{{ $t->type_word }}</button>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @if($t->type=='news')
                                                    <div class="parttext">
                                                        @if(property_exists($t, 'date') && property_exists($t, 'view'))
                                                        <div class="leftp">
                                                            <h2>
                                                              {{ $t->date }} 
                                                              <span class="views ml-20">
                                                              <i class="icon icon-eye-gray mr-5" style="margin-right: 12px;"></i>
                                                              </span>
                                                              {{ $t->view }}  
                                                            </h2>
                                                            <a href="{{$t->url}}" style="color: #1d1f1f;">
                                                                    @if(App::getLocale()=="ru"){{ $t->title_ru }}@else{{ $t->title_kz }}@endif</a>

                                                        </div>
                                                        @endif
                                                    </div> 
                                                    @elseif($t->type=='blogs')
                                                    <div class="parttext">
                                                        <div class="leftp">
                                                            @if(property_exists($t, 'date') && property_exists($t, 'view'))
                                                            <h2>
                                                              {{ $t->date }} 
                                                              <span class="views ml-20">
                                                              <i class="icon icon-eye-gray mr-5" style="margin-right: 12px;"></i>
                                                              </span>
                                                              {{ $t->view }} 
                                                            </h2>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @else
                                                    <div class="parttext">
                                                        <div class="leftp">
                                                         @if(property_exists($t, 'date'))
                                                            <h2>
                                                              {{ $t->date }} 
                                                            </h2>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @endforeach            
                            @endif                                       


                                <?php $i = 0; $bb = 0; ?>
                                @if(count($almaty_news_row) > 0)
                                    @foreach($almaty_news_row as $key => $main_news_item)
                                        @if($i < 2)
                                                <?php $i++; $bb = $i; ?>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="card card-md card-fixed">
                                                            <div class="card-body" {{$i}}>
                                                                <h3 class="card-title">
                                                                    <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">
                                                                        {{$main_news_item['news_title_' . App::getLocale()]}}
                                                                    </a>
                                                                </h3>
                                                                <ul class="article-meta">
                                                                    <li>
                                                                        @if($main_news_item['news_category_id'] > 0)
                                                                            <a class="label label-red" onclick="searchByNewsCategory({{$main_news_item['news_category_id']}})">{{$main_news_item['news_category_name_' . App::getLocale()]}}</a>
                                                                        @endif
                                                                    </li> 
                                                                </ul>
                                                                <div class="dv">
                                                                    <span class="date">{{$main_news_item['date']}}</span>
                                                                    <span class="views ml-20">
                                                                        <i class="icon icon-eye-gray mr-5"></i>{{$main_news_item['view_count']}}

                                                                        @if($main_news_item['is_has_foto'] > 0)
                                                                            <i class="icon icon-photo"></i>
                                                                        @endif

                                                                        @if($main_news_item['is_has_video'] > 0)
                                                                            <i class="icon icon-video"></i>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <form id="search_news_category" method="get" action="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/search") }}" style="display: none;">
                        <input type="hidden" value="3" name="search_type">
                        <input type="hidden" value="" name="search_news_category_id" id="search_news_category_id">
                    </form>

                    <script>
                        function searchByNewsCategory(news_category_id){
                            $("#search_news_category_id").val(news_category_id);
                            document.getElementById("search_news_category").submit();
                        }
                    </script>
                    <br>

                    <?php
                        function crop_str($string, $limit){
                            if (strlen($string) >= $limit ) {
                                $substring_limited = substr($string,0, $limit);
                                return substr($substring_limited, 0, strrpos($substring_limited, ' ' ));
                            }
                            else {
                                return $string;
                            }
                        }
                        
                        $i = 0;
                    ?>
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="latest-news card card-lg card-nm">
                                <div class="card-body">
                                    <p><h1 class="label label-blue" style="margin: 0; white-space: initial;">{{trans("messages.Последние новости")}}</h1></p>
                                    <ul class="latest-news-list">
                                        @if(count($row_new) > 0)
                                            @foreach($row_new as $key => $row_new_item)
                                                    @if($i < 4)
                                                        <?php $i++; ?>
                                                        <li>
                                                            <div class="dv">
                                                                <span class="date">{{$row_new_item['date']}}</span>
                                                                <span class="views ml-20">
                                                                    <i class="icon icon-eye-gray mr-5"></i>{{$row_new_item['view_count']}}

                                                                    @if($row_new_item['is_has_foto'] > 0)
                                                                        <i class="icon icon-photo"></i>
                                                                    @endif

                                                                    @if($row_new_item['is_has_video'] > 0)
                                                                        <i class="icon icon-video"></i>
                                                                    @endif
                                                                </span>
                                                                @if($row_new_item['is_whatsapp'] > 0)
                                                                    <span class="icon icon-whatsapp-gray ml-10"></span>
                                                                @endif
                                                            </div>
                                                            <h3 class="news-title">
                                                                <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $row_new_item->news_url_name) }}">
                                                                    {{ $row_new_item['news_title_' . App::getLocale()] }}
                                                                </a>
                                                            </h3>
                                                        </li>
                                                    @endif
                                            @endforeach
                                        @endif

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <?php  $lang_id = 2;
                            if(App::getLocale() == "kz"){
                                $lang_id = 1;
                            }
                            $kz_advertisement_list = App\Models\Advertisement::where("is_main_advertisement","=","4")->where("lang_id","=",$lang_id)->where("is_active","=","1")->get(); ?>
                        <?php $programm_advertisement_list = App\Models\Advertisement::where("is_main_advertisement","=","5")->where("lang_id","=",$lang_id)->where("is_active","=","1")->get(); ?>

                        <div class="col-md-6 col-sm-6">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    
                             <!--    Error 1 -->
                             @if(count($kz_advertisement_list) > 0)
                             <?php  $index_ad = rand(0, count($kz_advertisement_list)-1);
                                    $kz_advertisement_list->toArray();
                                $kz_advertisement = $kz_advertisement_list[$index_ad]; ?>
                                        <div class="card media-block">
                                            <a href="{{$kz_advertisement['link']}}" target="_blank">
                                                <div class="media-block-img" style="background-image: url('/adv/{{$kz_advertisement['image']}}')"></div>
                                            </a>
                                        </div>
                                    @else
                                        <div class="card media-block">
                                            <a href="#">
                                                <div class="media-block-img" style="background-image: url('/css/images/123321.jpg')">
                                                </div>
                                            </a>
                                        </div>
                                    @endif

                                </div>

                                <div class="col-md-6 col-sm-6">
                                    
                                    <!-- Error 2 -->
                                    @if(count($programm_advertisement_list) > 0)
                                    <?php  $index_ad = rand(0, count($programm_advertisement_list)-1);
                                    $programm_advertisement_list->toArray();
                                $programm_advertisement = $programm_advertisement_list[$index_ad]; ?>
                                        <div class="card media-block">
                                            <div class="media-block-img" style="background-image: url('/adv/{{$programm_advertisement['image']}}')">
                                                <div class="img-red-gradient">
                                                </div>
                                                <div class="media-text">
                                                    <div class="text-tr">
                                                        {{--<span>21:00</span><small>Сегодня</small>--}}
                                                    </div>
                                                    <div class="text-bl">
                                                        <h3 class="media-title"><a href="{{$programm_advertisement['link']}}">{{$programm_advertisement['advertisement_title_ru']}}</a></h3>
                                                        <div class="media-desc">
                                                            {{$programm_advertisement['advertisement_text_ru']}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card media-block">
                                            <div class="media-block-img" style="background-image: url('/css/images/123321.jpg')">
                                                <div class="media-text">
                                                    <div class="text-tr">
                                                        {{--<span>21:00</span><small>Сегодня</small>--}}
                                                    </div>
                                                    <div class="text-bl">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif



                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <?php $i = 0; $b = 0; ?>
                                @if(count($almaty_news_row) > 0)
                                    @foreach($almaty_news_row as $key => $main_news_item)
                                            <?php $i++; ?>
                                            @if($i > $bb && $b < 2)
                                                <?php $b++; ?>
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="partner-news card card-md card-nm card-fixed">
                                                        <div class="card-body">
                                                            <ul class="article-meta">
                                                                <li><span class="label label-gray">{{trans("messages.Новости")}}</span></li>
                                                            </ul>
                                                            <h3 class="card-title">
                                                                <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">
                                                                    {{$main_news_item['news_title_' . App::getLocale()]}}
                                                                </a>
                                                            </h3>

                                                            <div class="dv">
                                                                <span class="date">{{$main_news_item['date']}}</span>
                                                                <span class="views ml-20">
                                                                    <i class="icon icon-eye-gray mr-5"></i>{{$main_news_item['view_count']}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $tv_broadcast_prev = DB::select("
                                                SELECT t.*, t1.image as category_image, t1.category_name_kz, t1.category_name_ru, t1.category_name_en, t2.programm_url_name
                                                from tv_programm_tab t
                                                left join category_tab t1 on t.category_id = t1.category_id
                                                left join programm_tab t2 on t.tv_programm_programm_id = t2.programm_id
                                                where t.date = date_format(now(), '%Y-%m-%e')
                                                and   unix_timestamp(concat(t.date,' ', CASE WHEN t.time_end='00:00:00' THEN '24:00:00' ELSE t.time_end END)) < unix_timestamp(now())
                                                order by unix_timestamp(concat(t.date,' ',  CASE WHEN t.time_end='00:00:00' THEN '24:00:00' ELSE t.time_end END)) desc
                                                limit 1
                                               ");

            $tv_broadcast_row = DB::select("
                                                SELECT t.*, t1.image as category_image, t1.category_name_kz, t1.category_name_ru, t1.category_name_en, t2.programm_url_name
                                                from tv_programm_tab t
                                                left join category_tab t1 on t.category_id = t1.category_id
                                                left join programm_tab t2 on t.tv_programm_programm_id = t2.programm_id
                                                where t.date = date_format(now(), '%Y-%m-%e')
                                                and   unix_timestamp(concat(t.date,' ',  CASE WHEN t.time='00:00:00' THEN '24:00:00' ELSE t.time END)) <= unix_timestamp(now())
                                                and   case when t.time_end like '00:%'
                                                      THEN
                                                        unix_timestamp(concat(t.date,' ', CASE WHEN t.time_end like '00:%' THEN concat('23',substr(t.time_end,3)) ELSE t.time_end END))+3600 >= unix_timestamp(now())
                                                      else
                                                        unix_timestamp(concat(t.date,' ', t.time_end)) >= unix_timestamp(now())
                                                      end
                                                order by unix_timestamp(concat(t.date,' ',  CASE WHEN t.time='00:00:00' THEN '24:00:00' ELSE t.time END)) desc
                                                limit 1
                                               ");

            $tv_broadcast_row_next = DB::select("
                                                SELECT t.*, t1.image as category_image, t1.category_name_kz, t1.category_name_ru, t1.category_name_en, t2.programm_url_name
                                                from tv_programm_tab t
                                                left join category_tab t1 on t.category_id = t1.category_id
                                                left join programm_tab t2 on t.tv_programm_programm_id = t2.programm_id
                                                where t.date = date_format(now(), '%Y-%m-%e')
                                                and   unix_timestamp(concat(t.date,' ',  CASE WHEN t.time='00:00:00' THEN '24:00:00' ELSE t.time END)) > unix_timestamp(now())
                                                order by unix_timestamp(concat(t.date,' ',  CASE WHEN t.time='00:00:00' THEN '24:00:00' ELSE t.time END)) asc
                                                limit 5
                                               ");

            ?>

            <div class="col-md-3 hidden-sm hidden-xs col-np">
                <div class="index-event-block">
                    <div class="event-blog-item">
<!--                    <div class="event-blog-item-top">
                            <h5>{{trans('messages.События')}}</h5>
                        </div> -->
                        <div class="event-blog-item-top" style="min-height: 57px">
                            <div class="col-xs-9 no-padding top-different">
                              <h5>{{trans('messages.События')}}</h5>  
                            </div>
                            <div class="col-xs-3 no-padding">
                                <a href="/events" style="float: right;"><h4>{{trans('messages.all')}}&nbsp;<i class="fa fa-caret-down" style="line-height:1px" aria-hidden="true"></i></h4></a>
                            </div>
                        </div>
                        <div id="calendar"></div>
                    </div>
                    @if($rec_events)
                        <div class="index-events">
                            @foreach($rec_events as $event)
                            <div class="index-event_div" style="background-image: url('{{$event->image}}')">
                                <div>
                                    <p class="index-event_header"><a href="{{$event->url}}">{{$event->title}}</a></p>
                                    <div class="index-event_red">
                                        <p>{{$event->time}}</p>
                                        <p>{{$event->date}}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <a href="/events?from_blog=1" class="btn-add" style="text-decoration: none;color: #fff">{{trans('messages.add_event')}}</a>
                        </div>
                    @endif
                </div>

                @if(count($rec_blogs)>0)
                <div class="index-event-block">
                    <div class="event-blog-item">
                        <div class="event-blog-item-top">
                            <h5>{{trans('messages.blogs')}}</h5>
                        </div>
                    </div>
                    <div class="index-events" style="padding: 10px 0;">
                        @foreach($rec_blogs as $blog)
                        <div class="event-blog-item-tekst col-xs-12">
                            <div class="media">
                                <div class="media-left media-middle circle-pol">
                                    <img src="{{$blog->image}}" title="{{$blog->author}}" class="media-object">
                                </div>
                                <div class="media-body" style="width:inherit">
                                    <h6 class="media-heading"><a href="{{$blog->url}}" style="color: #272b27;font-size: 14px;">@if(App::getLocale() == "kz"){{$blog->title_kz}}@else{{$blog->title_ru}}@endif</a></h6>
                                    <a href="{{$blog->url}}" style="color:#1f5dea"><span style="font-size:12px;">{{trans('messages.event_more')}}</span></a>&nbsp;&nbsp;<br>
                                    <span style="color:#898989">{{$blog->date}}</span>&nbsp;&nbsp;
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <a href="/blogs"><button class="btn-add" >{{ trans('messages.write_blog') }}</button></a>
                        
                    </div>
                </div>
                @endif
                <p class="show-two">{{ trans('messages.Программа телепередач') }}</p>
                <div class="schedule">
                    <div class="schedule-two">
                        <p>{{ trans('messages.Программа телепередач') }}</p>
                    </div>
                    <!-- Error 3 -->
                    @if(count($tv_broadcast_prev) > 0)
                        <div class="schedule-item">
                            @if(strlen($tv_broadcast_prev[0]->image) > 0)
                                <div class="schedule-img" style="background-image: url('/tv_programm_photo/{{$tv_broadcast_prev[0]->image}}')"></div>
                            @else
                                <div class="schedule-img" style="background-image: url('/category_photo/{{$tv_broadcast_prev[0]->category_image}}')"></div>
                            @endif

                            <a href="/programma-peredach">
                                <div class="schedule-text">
                                    <div>
                                        <span class="schedule-time">{{$tv_broadcast_prev[0]->time}}</span>
                                        <span class="schedule-status">{{trans("messages.Уже прошел")}}</span>
                                    </div>
                                    <h3 class="schedule-title">
                                        @if(App::getLocale() == "kz")
                                            @if(strlen($tv_broadcast_prev[0]->tv_programm_name_kz) > 0)
                                                {{$tv_broadcast_prev[0]->tv_programm_name_kz}}
                                            @else
                                                {{$tv_broadcast_prev[0]->tv_programm_name_ru}}
                                            @endif
                                        @else
                                            @if(strlen($tv_broadcast_prev[0]->tv_programm_name_ru) > 0)
                                                {{$tv_broadcast_prev[0]->tv_programm_name_ru}}
                                            @else
                                                {{$tv_broadcast_prev[0]->tv_programm_name_kz}}
                                            @endif
                                        @endif
                                    </h3>
                                    <div>
                                        @if(App::getLocale() == "kz")
                                            {{$tv_broadcast_prev[0]->category_name_kz}}
                                        @elseif(App::getLocale() == "en")
                                            {{$tv_broadcast_prev[0]->category_name_en}}
                                        @else
                                            {{$tv_broadcast_prev[0]->category_name_ru}}
                                        @endif
                                    </div>
                                    <!-- <div class="schedule-social">
                                        
                                    </div> -->
                                </div>
                            </a>
                        </div>
                    @endif



                    @if(count($tv_broadcast_row) > 0)
                        <div class="schedule-item active" style="border-top: 2px solid #91ba42;">
                            @if(strlen($tv_broadcast_row[0]->image) > 0)
                                <div class="schedule-img" style="background-image: url('/tv_programm_photo/{{$tv_broadcast_row[0]->image}}')"></div>
                            @else
                                <div class="schedule-img" style="background-image: url('/category_photo/{{$tv_broadcast_row[0]->category_image}}')"></div>
                            @endif

                            <a href="/broadcasting">
                                <div class="schedule-text">
                                    <div>
                                        <span class="schedule-current">{{ trans('messages.efir') }}</span>
                                    </div>
                                    <h3 class="schedule-title">
                                        @if(App::getLocale() == "kz")
                                            @if(strlen($tv_broadcast_row[0]->tv_programm_name_kz) > 0)
                                                {{$tv_broadcast_row[0]->tv_programm_name_kz}}
                                            @else
                                                {{$tv_broadcast_row[0]->tv_programm_name_ru}}
                                            @endif
                                        @else
                                            @if(strlen($tv_broadcast_row[0]->tv_programm_name_ru) > 0)
                                                {{$tv_broadcast_row[0]->tv_programm_name_ru}}
                                            @else
                                                {{$tv_broadcast_row[0]->tv_programm_name_kz}}
                                            @endif
                                        @endif
                                    </h3>
                                    <div>
                                        @if(App::getLocale() == "kz")
                                            {{$tv_broadcast_row[0]->category_name_kz}}
                                        @elseif(App::getLocale() == "en")
                                            {{$tv_broadcast_row[0]->category_name_en}}
                                        @else
                                            {{$tv_broadcast_row[0]->category_name_ru}}
                                        @endif
                                    </div>
<!--                                     <div class="schedule-social">
                                        
                                    </div> -->
                                </div>
                            </a>
                        </div>
                    @endif



                    <?php $b = 0; ?>
                    @if(count($tv_broadcast_row_next) > 0)
                        @foreach($tv_broadcast_row_next as $key => $tv_broadcast_row_next_item)
                            <?php $b++; ?>
                            <div class="schedule-item" style="border-top: 2px solid rgba(79,0,48,1);">
                                @if(strlen($tv_broadcast_row_next_item->image) > 0)
                                    <div class="schedule-img" style="background-image: url('/tv_programm_photo/{{$tv_broadcast_row_next_item->image}}')"></div>
                                @else
                                    <div class="schedule-img" style="background-image: url('/category_photo/{{$tv_broadcast_row_next_item->category_image}}')"></div>
                                @endif

                                <a href="/programma-peredach">
                                    <div class="schedule-text">
                                        <div>
                                            <span class="schedule-time">{{$tv_broadcast_row_next_item->time}}</span>
                                            <!-- <span class="schedule-status">{{trans("messages.Скоро")}}</span> -->
                                        </div>
                                        <h3 class="schedule-title">
                                            @if(App::getLocale() == "kz")
                                                @if(strlen($tv_broadcast_row_next_item->tv_programm_name_kz) > 0)
                                                    {{$tv_broadcast_row_next_item->tv_programm_name_kz}}
                                                @else
                                                    {{$tv_broadcast_row_next_item->tv_programm_name_ru}}
                                                @endif
                                            @else
                                                @if(strlen($tv_broadcast_row_next_item->tv_programm_name_ru) > 0)
                                                    {{$tv_broadcast_row_next_item->tv_programm_name_ru}}
                                                @else
                                                    {{$tv_broadcast_row_next_item->tv_programm_name_kz}}
                                                @endif
                                            @endif
                                        </h3>
                                        <div>
                                            @if(App::getLocale() == "kz")
                                                {{$tv_broadcast_row_next_item->category_name_kz}}
                                            @elseif(App::getLocale() == "en")
                                                {{$tv_broadcast_row_next_item->category_name_en}}
                                            @else
                                                {{$tv_broadcast_row_next_item->category_name_ru}}
                                            @endif
                                        </div>
<!--                                         <div class="schedule-social">
                                            
                                        </div> -->
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                    @if((count($tv_broadcast_prev) + count($tv_broadcast_row) + count($tv_broadcast_row_next)) < 7)
                        <?php
                        $count_next_day_programm = 7 - (count($tv_broadcast_prev) + count($tv_broadcast_row) + count($tv_broadcast_row_next));
                        $tv_broadcast_row_next_day = DB::select("
                                                                SELECT t.*, t1.image as category_image, t1.category_name_kz, t1.category_name_ru, t1.category_name_en, t2.programm_url_name, DATE_FORMAT(t.date,'%d.%m.%Y') as date
                                                                from tv_programm_tab t
                                                                left join category_tab t1 on t.category_id = t1.category_id
                                                                left join programm_tab t2 on t.tv_programm_programm_id = t2.programm_id
                                                                where t.date = date_format(now() + INTERVAL 1 DAY, '%Y-%m-%e')
                                                                order by unix_timestamp(concat(t.date,' ',  CASE WHEN t.time='00:00:00' THEN '24:00:00' ELSE t.time END)) asc
                                                                ");
                        ?>

                        @if(count($tv_broadcast_row_next_day) > 0)
                            @foreach($tv_broadcast_row_next_day as $key => $tv_broadcast_row_next_item)
                                @if($count_next_day_programm > 0)
                                    <?php $b++; $count_next_day_programm--; ?>
                                    <div class="schedule-item">
                                        @if(strlen($tv_broadcast_row_next_item->image) > 0)
                                            <div class="schedule-img" style="background-image: url('/tv_programm_photo/{{$tv_broadcast_row_next_item->image}}')"></div>
                                        @else
                                            <div class="schedule-img" style="background-image: url('/category_photo/{{$tv_broadcast_row_next_item->category_image}}')"></div>
                                        @endif

                                        <a href="/programma-peredach">
                                            <div class="schedule-text">
                                                <div>
                                                    <span class="schedule-time">{{$tv_broadcast_row_next_item->date}} {{$tv_broadcast_row_next_item->time}}</span>
                                                    <span class="schedule-status">{{trans("messages.Скоро")}}</span>
                                                </div>
                                                <h3 class="schedule-title">
                                                    @if(App::getLocale() == "kz")
                                                        @if(strlen($tv_broadcast_row_next_item->tv_programm_name_kz) > 0)
                                                            {{$tv_broadcast_row_next_item->tv_programm_name_kz}}
                                                        @else
                                                            {{$tv_broadcast_row_next_item->tv_programm_name_ru}}
                                                        @endif
                                                    @else
                                                        @if(strlen($tv_broadcast_row_next_item->tv_programm_name_ru) > 0)
                                                            {{$tv_broadcast_row_next_item->tv_programm_name_ru}}
                                                        @else
                                                            {{$tv_broadcast_row_next_item->tv_programm_name_kz}}
                                                        @endif
                                                    @endif
                                                </h3>
                                                <div>
                                                    @if(App::getLocale() == "kz")
                                                        {{$tv_broadcast_row_next_item->category_name_kz}}
                                                    @elseif(App::getLocale() == "en")
                                                        {{$tv_broadcast_row_next_item->category_name_en}}
                                                    @else
                                                        {{$tv_broadcast_row_next_item->category_name_ru}}
                                                    @endif
                                                </div>
<!--                                                 <div class="schedule-social">
                                                    
                                                </div> -->
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                    @endif


                    <a class="shedule-more-btn" href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/programma-peredach/") }}">
                        <i class="icon icon-schedule-black"></i>
                        {{trans("messages.Посмотреть всю программу передач")}}
                    </a>
                </div>
            </div>
        </div>

<!-- Extended Page -->
<div class="row">
    <div class="wrappernew">
    <div class="padnew">
        <div class="container-fluid" style="padding-left: 5px;padding-right: 5px;">
            <div class="col-md-12 wraptele">
                <div class="teleproekty">
                <h1>{{ trans('messages.Телепроекты') }}</h1>
                <hr>
                  <ul class="nav nav-pills" id="movend">
                  @foreach($programs as $key => $program)
                    <li @if($key == 0) class="active" @endif><a data-toggle="pill" href="#menu{{$program->id}}">{{$program->title}}</a></li>
                  @endforeach
                    <button class="nextn"  href="#myCarousel" data-slide="next"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
                  </ul>
                  
                  <div class="tab-content">
                    @foreach($programs as $key => $program)
                    <div id="menu{{$program->id}}" class="tab-pane fade @if($key==0)in active @endif">
                      <div class="col-md-6 col-xs-12 tableft">
                            <div class="row">
                                <img src="/programm_photo/{{$program->main_image}}" class="img-responsive" style="width:100%" alt="">
                            </div>
                            <div class="row">
                            @if($program->link_videoarchive)
                                <h5><a href="{{$program->link_videoarchive}}">{{$program->title}}</a></h5>
                            @else
                                <h5>{{$program->title}}</h5>
                            @endif
                                <!-- <p><i class="fa fa-calendar" aria-hidden="true"></i> 13.07.2017</p> -->
                                <p><i class="fa fa-clock-o" aria-hidden="true"></i>{{ date('H:i', strtotime($program->time)) }}</p>
                                <p><i class="fa fa-table" aria-hidden="true"></i>{{$program->day_week}}</p>
                            </div>
                            <div class="clear"></div>
                      </div>
                      <div class="col-md-6 col-xs-12 tabright">
                          <h2>{{ trans('messages.about_project') }}</h2>
                          <div style="text-align: left;">{!! $program->description !!}</div>
                          @if($program->logo)
                            <img src="/programm_photo/{{$program->logo}}" alt="">
                          @else
                            <img src="/css/image/logo_example.png" alt="">
                          @endif
                          <a @if($program->link_programm)href="{{$program->link_programm}}"@endif>{{ trans('messages.watch') }}</a>
                      </div>
                      <div class="clear"></div>
                    </div>
                    @endforeach
                  </div>
                </div>
            </div>            
        </div>        
    </div>

    <div class="container-fluid">

        <div class="col-md-12">
            <h3 class="choosered">{{ trans('messages.choose_of_redaction') }}</h3>
            <hr>
        </div>            
        @foreach($favorites as $fav)
        <div class="col-md-3">
            <div class="newscard">
            <a href="#">
                <div class="cardbackimg" style="background-image: url(@if($fav->image)'{{ asset($fav->image) }}'@else'/css/images/blog_default.jpg'@endif)">
                    <div class="imgdarkabs">
                        <button>{{ $fav->type_word }}</button>
                        <p><a href="{{$fav->url}}" style="color: #fff;">
                            @if($fav->type!='events'){{ $fav["title_".App::getLocale()] }}@else{{ $fav->title_kz }}@endif</a>
                        </p>
                    </div>
                </div>
            </a> 
            @if($fav->type=='news')
            <div class="parttext">
                <div class="leftp">
                    <h2>
                      {{ $fav->date }} 
                      <span class="views ml-20">
                      <i class="icon icon-eye-gray mr-5" style="margin-right: 12px;"></i>
                      </span>
                      {{ $fav->view }}  
                    </h2>
                </div>
            </div> 
            @elseif($fav->type=='blogs')
            <div class="parttext">
                <div class="righttp">
                    <img src="{{$fav->author_img}}">
                </div>
                <div class="leftp">
                    <h2><span> <a href="{{ $fav->author_url }}" style="color: #6D0043;">
                    {{ $fav->author }}</a> </span></h2>
                    <h2>  {{ $fav->date }} </h2>
                </div>
            </div>
            @else
            <div class="parttext">
                <div class="leftp">
                    <h2>  <span> {{ $fav->address }} </span></h2>
                    <h2>  {{ $fav->date }} </h2>
                </div>
            </div>
            @endif
            </div>
        </div>
        @endforeach
    </div>
        
</div>
    <style>
        .event-blog-item-top {
            padding: 10px 20px;
        }
        #calendar{
            padding: 0 35px;
        }
        .index-event-block #calendar .header h1{
            font-size: 14px;
        }
        .index-event-block #calendar .day{
            padding: 0px;

        }
        .index-event-block #calendar .day-number{
            font-size: 13px;
            width: 27px;
            height: 27px;
            line-height: 25px;
        }
        .index-event-block{
            padding: 0 0 0 0;
            box-shadow: 0 0 2.5px 0 rgba(0, 0, 0, 0.15);
            margin-right: 15px;
            display: block;
        }
        .index-event-block #calendar .details{
            width: initial;
        }
        .index-events .index-event_div{
            /*height: 100px;*/
            background-position: center;
            /*padding: 5px 10px;*/
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .index-event_div>div{
            padding: 5px 10px;
            background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.8) 0%, transparent 100%);
            border-radius: 5px;
            text-align: left;
        }
        .index-events {
            padding: 15px;
            padding-top: 0;
            background-color: #fff;
            text-align: center;
            /*box-shadow: 0 0 2.5px 0 rgba(0, 0, 0, 0.15);*/
            border-bottom-right-radius: 2px;
            border-bottom-left-radius: 2px;
        }
        .event-blog-item{
            box-shadow: none;
        }
        .index-event_header{
            color: #fff;
            /*font-weight: bold;*/
            line-height: 17px;
            font-family: MyriadPro;
        }
        .index-event_header a{
            color: #fff;
        }
        .index-event_red{
            width: 50%;
            /* height: 40px; */
            padding: 3px 10px;
            background-color: #68003d;
        }
        .index-event_red>p{
            margin: 0;
            color: #fff;
            /* font-weight: bold; */
            line-height: 18px;
            font-family: MyriadPro;
        }
        .index-events .btn-add{
            margin: 0 auto;
            padding: 6px 20px;
            background-image: initial;
            background-color: #1667b1;
            width: initial;
        }

        .event-blog-item-tekst{
            text-align: left;
            padding: 0px 15px;
            padding-bottom: 5px;
            margin-bottom: 10px;
            border-bottom: #B8B8B8 1px solid;
        }
    </style>
    
    <script src="/extended_page/js/jquery-1.12.3.min.js"></script>   
    <script src="/extended_page/js/bootstrap.min.js"></script>
    <script src="/extended_page/js/mask.js"></script>
    <script src="/extended_page/js/script.js"></script>
    <script src="/extended_page/js/wow.min.js"></script>
    </div>
    <!-- End of Extended Page -->


    </div>

    <script>
        function goToUrl(url){
            window.location.href = url;
        }
    </script>
    <script>
    $(".show-two").on("click",function(){
        $(".show-two").css("display","none");
        $(".schedule").css("display","block");
        $(".index-event-block").css("display","none");
    });
    $(".schedule-two").on("click",function(){
        $(".show-two").css("display","block");
        $(".schedule").css("display","none");
        $(".index-event-block").css("display","block");
    });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
    <script type="text/javascript">
$(function() {

    moment.locale('ru');
    if('{{ App::getLocale() }}' == 'kz'){
        moment.locale('kk');
    }
    var today = moment();

    function Calendar(selector, events, date, category) {
        this.el = document.querySelector(selector);
        this.events = events;
        this.current = moment(date).date(1);
        this.category = category;
        this.draw();
    }

    Calendar.prototype.draw = function() {
        //Create Header
        this.drawHeader();

        //Draw Month
        this.drawMonth();
    }

    Calendar.prototype.drawHeader = function() {
        var self = this;
        if(!this.header) {
            //Create the header elements
            this.header = createElement('div', 'header');
            this.header.className = 'header';

            this.title = createElement('h1');

            var right = createElement('div', 'right');
            right.addEventListener('click', function() { self.nextMonth(); });

            var left = createElement('div', 'left');
            left.addEventListener('click', function() { self.prevMonth(); });

            //Append the Elements
            this.header.appendChild(this.title);
            this.header.appendChild(right);
            this.header.appendChild(left);
            this.el.appendChild(this.header);
        }

        this.title.innerHTML = this.current.format('MMMM YYYY');
    }

    Calendar.prototype.drawMonth = function() {
        var self = this;

        //this.events.forEach(function(ev) {
          //  ev.date = self.current.clone().date(Math.random() * (29 - 1) + 1);
        //});


        if(this.month) {
            this.oldMonth = this.month;
            this.oldMonth.className = 'month out ' + (self.next ? 'next' : 'prev');
            this.oldMonth.addEventListener('webkitAnimationEnd', function() {
                self.oldMonth.parentNode.removeChild(self.oldMonth);
                self.month = createElement('div', 'month');
                self.backFill();
                self.currentMonth();
                self.fowardFill();
                self.el.appendChild(self.month);
                window.setTimeout(function() {
                    self.month.className = 'month in ' + (self.next ? 'next' : 'prev');
                }, 16);
            });
        } else {
            this.month = createElement('div', 'month');
            this.el.appendChild(this.month);
            this.backFill();
            this.currentMonth();
            this.fowardFill();
            this.month.className = 'month new';
        }
    }

    Calendar.prototype.backFill = function() {
        var clone = this.current.clone();
        var dayOfWeek = clone.day();

        if(!dayOfWeek) { return; }

        clone.subtract('days', dayOfWeek+1);

        for(var i = dayOfWeek; i > 0 ; i--) {
            this.drawDay(clone.add('days', 1));
        }
    }

    Calendar.prototype.fowardFill = function() {
        var clone = this.current.clone().add('months', 1).subtract('days', 1);
        var dayOfWeek = clone.day();

        if(dayOfWeek === 6) { return; }

        for(var i = dayOfWeek; i < 6 ; i++) {
            this.drawDay(clone.add('days', 1));
        }
    }

    Calendar.prototype.currentMonth = function() {
        var clone = this.current.clone();

        while(clone.month() === this.current.month()) {
            this.drawDay(clone);
            clone.add('days', 1);
        }
    }

    Calendar.prototype.getWeek = function(day) {
        if(!this.week || day.day() === 0) {
            this.week = createElement('div', 'week');
            this.month.appendChild(this.week);
        }
    }

    Calendar.prototype.drawDay = function(day) {
        var self = this;
        this.getWeek(day);

        //Outer Day
        var outer = createElement('div', this.getDayClass(day));
        outer.addEventListener('click', function() {
            self.openDay(this);
        });

        //Day Number
        var number = createElement('div', 'day-number', day.format('DD'));


        //Events
        var events = createElement('div', 'day-events');
        this.drawEvents(day, events);

        outer.appendChild(number);
        outer.appendChild(events);
        this.week.appendChild(outer);
    }

    Calendar.prototype.drawEvents = function(day, element) {
        if(day.month() === this.current.month()) {
            var todaysEvents = this.events.reduce(function(memo, ev) {
                if(ev.date.isSame(day, 'day')) {
                    memo.push(ev);
                }
                return memo;
            }, []);

            todaysEvents.forEach(function(ev) {
                var evSpan = createSpan(ev.color);
                element.appendChild(evSpan);
            });
        }
    }

    Calendar.prototype.getDayClass = function(day) {
        classes = ['day'];
        if(day.month() !== this.current.month()) {
            classes.push('other');
        } else if (today.isSame(day, 'day')) {
            classes.push('today');
        }
        return classes.join(' ');
    }

    Calendar.prototype.openDay = function(el) {
        var details, arrow;
        var dayNumber = +el.querySelectorAll('.day-number')[0].innerText || +el.querySelectorAll('.day-number')[0].textContent;
        var day = this.current.clone().date(dayNumber);

        var todaysEvents = this.events.reduce(function(memo, ev) {
          if(ev.date.isSame(day, 'day')) {
            memo.push(ev);
          }
          return memo;
        }, []);

        var todaysEventsIds = this.events.reduce(function(memo, ev) {
          if(ev.date.isSame(day, 'day')) {
            memo.push(ev.event_id);
          }
          return memo;
        }, []);
    
        var currentOpened = document.querySelector('.details');
        if(todaysEvents.length>0){
            getByDayAndCategory(day.format(), this.category, todaysEventsIds);

            console.log(todaysEventsIds);
            //Check to see if there is an open detais box on the current row
            if(currentOpened && currentOpened.parentNode === el.parentNode) {
              details = currentOpened;
              arrow = document.querySelector('.arrow');
            } else {
              //Close the open events on differnt week row
              //currentOpened && currentOpened.parentNode.removeChild(currentOpened);
              if(currentOpened) {
                currentOpened.addEventListener('webkitAnimationEnd', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener('oanimationend', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener('msAnimationEnd', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener('animationend', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.className = 'details out';
              }

              //Create the Details Container
              details = createElement('div', 'details in');

              //Create the arrow
              var arrow = createElement('div', 'arrow');

              //Create the event wrapper

              details.appendChild(arrow);
              el.parentNode.appendChild(details);
            }

            this.renderEvents(todaysEvents, details);

            arrow.style.left = el.offsetLeft - el.parentNode.offsetLeft + 27 + 'px';
        } else{
            if(currentOpened) {
                currentOpened.addEventListener('webkitAnimationEnd', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener('oanimationend', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener('msAnimationEnd', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener('animationend', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.className = 'details out';
              }
        }
        
    }

    Calendar.prototype.renderEvents = function(events, ele) {
        //Remove any events in the current details element
        var currentWrapper = ele.querySelector('.events');
        var wrapper = createElement('div', 'events in' + (currentWrapper ? ' new' : ''));

        events.forEach(function(ev) {
            var div = createElement('div', 'event');
            var square = createElement('div', 'event-category ' + ev.color);
            var span = createElement('span', '', ev.eventName);

            div.appendChild(square);
            div.appendChild(span);
            wrapper.appendChild(div);
        });

        if(!events.length) {
        }

        if(currentWrapper) {
            currentWrapper.className = 'events out';
            currentWrapper.addEventListener('webkitAnimationEnd', function() {
                currentWrapper.parentNode.removeChild(currentWrapper);
                ele.appendChild(wrapper);
            });
            currentWrapper.addEventListener('oanimationend', function() {
                currentWrapper.parentNode.removeChild(currentWrapper);
                ele.appendChild(wrapper);
            });
            currentWrapper.addEventListener('msAnimationEnd', function() {
                currentWrapper.parentNode.removeChild(currentWrapper);
                ele.appendChild(wrapper);
            });
            currentWrapper.addEventListener('animationend', function() {
                currentWrapper.parentNode.removeChild(currentWrapper);
                ele.appendChild(wrapper);
            });
        } else {
            ele.appendChild(wrapper);
        }
    }


    Calendar.prototype.nextMonth = function() {
        this.current.add('months', 1);
        this.next = true;
        this.draw();

        getByMonthAndCategory(this.current.format(), this.category);
    }

    Calendar.prototype.prevMonth = function() {
        this.current.subtract('months', 1);
        this.next = false;
        this.draw();

        getByMonthAndCategory(this.current.format(), this.category);
    }

    window.Calendar = Calendar;

    function createElement(tagName, className, innerText) {
        var ele = document.createElement(tagName);
        if(className) {
            ele.className = className;
        }
        if(innerText) {
            ele.innderText = ele.textContent = innerText;
        }
        return ele;
    }

    function createSpan(color) {
        var ele = document.createElement('span');
        color.padStart(6, '0');
        ele.style.backgroundColor = '#' + color.padStart(6, '0');

        return ele;
    }

    getByMonthAndCategory(today.format());


});

function getByMonthAndCategory (date, category) {
    $.ajax({
        type: 'GET',
        url: "{{route('get_dates')}}",
        data: {date: date, category_id: category},
        async: false,
        success: function(data){
            events = data.events;

            Object.keys(events).map(function(key, index) {
                // let d= new Date(events[index]['date']);
                // events[index]['date'] = moment(date).date( d.toISOString());
                events[index]['date'] = moment(date).date(events[index]['date']);
            });

            if (events.length > 0) {
                $('#calendar').empty();
                new Calendar('#calendar', events, moment(date), category);
            };
        }
    });
}
function getByDayAndCategory (date, category, ids) {
    $.ajax({
        type: 'GET',
        url: "{{route('get_dates')}}",
        data: {date: date, category_id: category, ids: ids, type: 2},
        async: true,
        success: function(data){
            // console.log(events);
            if(data.events.length>0){
                $('.loaded-more').empty();
                $('.load-more').css('display', 'none');
                $('#thats_all').css('display', 'none');
                addEvents(data.events);
            }
        }
    });
}
    </script>
@endsection