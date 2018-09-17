@extends('index.top')

@section('layout')

    <?php
    $action_name = "";
    $action_name2 = "";
    $action_name3 = "";
    $action_name5 = "";
    $action_name6 = "";
    $controller_name = "";
    if (!empty(Route::getFacadeRoot()->current())) {
        $current_path_parts = explode("/", Route::getFacadeRoot()->current()->uri());
    }
    $current_path_parts2 = explode("/",Request::url());
    if(isset($current_path_parts[0])){
        $controller_name = $current_path_parts[0];
    }
    if(isset($current_path_parts[1])){
        $action_name = $current_path_parts[1];
    }
    if(isset($current_path_parts[2])){
        $action_name2 = $current_path_parts[2];
    }
    if(isset($current_path_parts[3])){
        $action_name3 = $current_path_parts[3];
    }

    if(isset($current_path_parts2[5])){
        $action_name5 = $current_path_parts2[5];
    }

    if(isset($current_path_parts2[6])){
        $action_name6 = $current_path_parts2[6];
    }
    ?>
    <body>

        <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter21005383 = new Ya.Metrika({
                            id:21005383,
                            clickmap:true,
                            trackLinks:true,
                            accurateTrackBounce:true,
                            webvisor:true
                        });
                    } catch(e) { }
                });

                var n = d.getElementsByTagName("script")[0],
                        s = d.createElement("script"),
                        f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mc.yandex.ru/metrika/watch.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks");
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/21005383" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        
        @include('banner_blog')
        <!-- /Yandex.Metrika counter -->
        <div class="wrapper">
            <div class="ajax-loader" style="display: none; position: fixed; background-color: rgba(0,0,0,0.5); z-index: 1000; width: 100%; height: 100%;">
                <img src="/css/images/oval.svg" style="margin-left: 50%; margin-top: 20%;">
            </div>
            
            <!-- NEW HEADER -->
            <header id="main-header">
            <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-1 menu_button">
                        </div>
                        <div class="sidebar-toggle">
                            <div>
                                <span></span><span></span><span></span><span></span>
                            </div>
                        </div>
                        <div class="search-icon">
                            <a href="https://almaty.tv/search">
                                <i class="icon icon-search-circle"></i>
                            </a>
                        </div>
                        <nav class="top-nav visible-md visible-lg">
                            <ul class="nav_menu nav-justified">
                                <li>
                                    <a class="menu_select" href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/") }}'>{{trans("messages.Новости")}}</a>
                                </li>
                                <li>
                                    <a class="menu_select" href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/programms/") }}'>{{trans("messages.Телепроекты")}}</a>
                                </li>
                                <li>
                                    <a class="menu_select" href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/archive/") }}'>{{trans("messages.Видеоархив")}}</a>
                                </li>
                                <li>
                                    <a class="menu_select" href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/programma-peredach/") }}'>{{trans("messages.Программа телепередач")}}</a>
                                </li>
                                <li>
                                    <a class="logo_link" href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}'><img class="logo" src="/css/image/logo.png" /></a>
                                </li>
                                <li>
                                    <a class="menu_select" href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/events/") }}'>{{trans("messages.events")}}</a>
                                </li>
                                <li>
                                    <a class="menu_select" href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/blogs/") }}'>{{trans("messages.blogs")}}</a>
                                </li>
                                <li>
                                    <a class="menu_select" href='{{  LaravelLocalization::getLocalizedURL(App::getLocale(), "/broadcasting") }}'><div class="efir"></div>{{trans("messages.efir")}}</a>
                                </li>
                                <li>
                                    <div style="display: flex; padding-left: 4px;">
                                        <a href="https://www.facebook.com/almaty.tv/?fref=ts" target="_blank">
                                            <i class="icon icon-facebook-white"></i>
                                        </a>
                                        <a style="padding-left:5px" href="https://www.instagram.com/almaty.tv/" target="_blank">
                                            <i class="icon icon-instagram-white"></i>
                                        </a>
                                        <a style="padding-left:5px" href="https://www.youtube.com/user/AlmatyUS" target="_blank">
                                            <i class="icon icon-twitter-white"></i>
                                        </a>
                                    </div>
                                </li>
                                <li>
                                    <div class="lang-selector dropdown">
                                        <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown">
                                            @if(App::getLocale() == "kz")
                                                {{trans("messages.каз")}}
                                            @else
                                                рус
                                            @endif
                                        <i class="icon icon-caret-white"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                        <?php $current_url = parse_url(LaravelLocalization::getNonLocalizedURL(Request::url())); ?>
                                        @foreach(config('app.locales')  as $lang)
                                            <li {!! config('app.locale') == $lang ? 'class="active"' : '' !!}>
                                                @if($lang == 'ru')
                                                    <a href="{{  url('/ru'.$current_url['path']) }}">рус</a>
                                                @else
                                                    <a href="{{ LaravelLocalization::getLocalizedURL($lang) }}">каз</a>
                                                @endif
                                            </li>
                                        @endforeach
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                        
                        <div id="sidebar">
                            <nav>
                                <ul class="nav">
                                    <li style="display: none;">
                                        <a href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/about/") }}'>{{trans("messages.О канале")}}</a>
                                    </li>
                                    <li>
                                        <a href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/") }}'>{{trans("messages.Новости")}}</a>
                                    </li>
                                    <li>
                                        <a href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/programms/") }}'>{{trans("messages.Телепроекты")}}</a>
                                    </li>
                                    <li class="hidden-md hidden-lg">
                                        <a href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/archive/") }}'>{{trans("messages.Видеоархив")}}</a>
                                    </li>
                                    <li>
                                        <a href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/events/") }}'>{{trans("messages.events")}}</a>
                                    </li>
                                    <li>
                                        <a href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/blogs/") }}'>{{trans("messages.blogs")}}</a>
                                    </li>
                                </ul>
                                <ul class="nav">
                                    <li>
                                        <a href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/ads/") }}'>{{trans("messages.Рекламодателям")}}</a>
                                    </li>
                                    <li>
                                        <a href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/vakansii/") }}'>{{trans("messages.Вакансии")}}</a>
                                    </li>
                                    <li>
                                        <a href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/obratnaya-svyaz/") }}'>{{trans("messages.Контакты")}}</a>
                                    </li>
                                    <li>
                                        <a href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/programma-peredach/") }}'>{{trans("messages.Программа телепередач")}}</a>
                                    </li>
<!--                                     <li>
                                        <a href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/history/") }}'>{{trans("messages.history")}}</a>
                                    </li> -->
                                    <li>
                                        <a href='{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/about") }}'>{{trans("messages.about_us")}}</a>
                                    </li>
                                </ul>
                                <ul class="nav hidden-md hidden-lg">
                                    <li>
                                        <a class="menu_select" href='{{  LaravelLocalization::getLocalizedURL(App::getLocale(), "/broadcasting") }}'> <div class="efir1"></div>{{trans("messages.efir")}}</a>
                                    </li>
                                </ul>
                            </nav>
                            <div class="divider"></div>
                            <div class="sidebar-soc-links">
                                <ul class="list-inline">
                                    <li class="facebook">
                                        <a href="https://www.facebook.com/almaty.tv/?fref=ts" target="_blank"><i class="fa fa-facebook"></i></a>
                                    </li>
<!--                                     <li class="twitter">
                                        <a href="https://twitter.com/almaty_tv" target="_blank"><i class="fa fa-twitter"></i></a>
                                    </li> -->
                                    <li class="instagram">
                                        <a href="https://www.instagram.com/almaty.tv/" target="_blank"><i class="fa fa-instagram"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://www.youtube.com/user/AlmatyUS" target="_blank"><i class="fa fa-youtube"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="whatsapp-contact" style="float: none;margin: 5px auto;text-align: center">
                                <a class="number" href="whatsapp://send?abid=username&amp;text=HeyThere!">
                                    <i class="icon icon-whatsapp-white" style="float: none"></i>
                                    <span>+7 (747) 7 502 502</span>
                                    </a>
                                <div class="caption">{{trans("messages.Whatsapp только для новостей")}}</div>
                            </div>
                            <div class="app-stores" style="padding: 10px 30px 0 30px;">
                                <a href="https://play.google.com/store/apps/details?id=com.bugingroup.almatytv">
                                    <img src="https://almaty.tv/css/image/play_market.png" style="max-width: 48% !important;">
                                </a>
                                <a href="https://itunes.apple.com/kz/app/almatytv/id1144924615?mt=8">
                                    <img src="https://almaty.tv/css/image/app_store.svg" style="max-width: 48% !important;float: right;">
                                </a>
                            </div>
                            <div class="subscribe-form">
                                <form id="delivery_form" method="post">
                                    <input type="hidden" name="_token" value="DTMVra8oLwh8LQxLZLfLgWyebpCsVEaSqYUFgx78">
                                    <div class="form-group">
                                        <input class="delivery-mail form-control" placeholder='{{trans("messages.Введите Email")}}' type="email" name="delivery_email">
                                    </div>
                                    <button onclick="addDelivery()" class="btn btn-block"><i class="icon icon-subscribe-white"></i>{{trans("messages.Подписаться на новости")}}</button>
                                </form>
                            </div>
                            <div class="divider"></div>
                            <div class="sidebar-footer">
                                <p>Copyright © "АЛМАТЫ", 2016<br> {{trans("messages.Все права защищены")}}</p>
                                <p></p>
                                <p><?php echo trans("messages.При использовании материалов ссылка<br>на сайт и автора обязательна"); ?></p>
                                <p></p>
                                    <p>Разработка сайта —&nbsp;<a href="https://avsoft.kz/">Компания AvSoftware</a></p>
                            </div>
                        </div>

                        <ul type="none" style="display: flex;" class="after992nav">
                            <li class="logo_li">
                                <a class="logo_link1" href="/"><img class="logo_img" src="/css/image/logo.png" /></a>
                            </li>
                            <li>
                                <div class="lang-selector lang-selector_mod dropdown">
                                    <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown">
                                        @if(App::getLocale() == "kz")
                                                {{trans("messages.каз")}}
                                        @else
                                            рус
                                        @endif
                                    <i class="icon icon-caret-white"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php $current_url = parse_url(LaravelLocalization::getNonLocalizedURL(Request::url())); ?>
                                        @foreach(config('app.locales')  as $lang)
                                            <li {!! config('app.locale') == $lang ? 'class="active"' : '' !!}>
                                                @if($lang == 'ru')
                                                    <a href="{{  url('/ru'.$current_url['path']) }}">рус</a>
                                                @else
                                                    <a href="{{ LaravelLocalization::getLocalizedURL($lang) }}">каз</a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                            </li>
                        </ul> 
                    </div>
                </div>
            </header>
            <!-- End of NEW HEADER -->

            <form id="search_by_tag_form" method="get" action="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/search") }}" style="display: none;">
                <input type="hidden" value="2" name="search_type">
                <input type="hidden" value="" name="search_by_tag" id="search_by_tag">
            </form>
            <!--END_HEADER-->
            <meta name="csrf-token" content="{{ csrf_token() }}" />

            @yield('content')
        </div>
        
        @include('index.footer_2')

        @if(!Auth::check())
            @include('index.register')
            @include('index.congratulations')
        @endif
    </body>

    <script>
        $(document).ready(function(){
            $('.dropdown-toggle').dropdown();
        });
    </script>
    <script>
        function searchByTag(tag){
            $("#search_by_tag").val(tag);
            document.getElementById("search_by_tag_form").submit();
        }

        function addDelivery(){
            if($(".delivery-mail").val().length > 0){
                $.ajax({
                    type: 'POST',
                    url: "/index/send-delivery",
                    data: $("#delivery_form").serialize(),
                    success: function(data){
                        if(data.result == "true"){
                            alert("Вы успешно подписаны на новости");
                            document.getElementById("delivery_form").reset();
                        }
                        else{
                            alert(data.value);
                        }
                    }
                });
                return false;
            }
            else{
                alert("Введите email");
            }
        }
    </script>

    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    </script>

@if($controller_name!="")
    <div id="fb-root"></div>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&appId=564171160441904&version=v2.0";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <!-- Added from Av.Almaty.tv -->
    <script src="{{ asset('js/jssocials/jssocials.min.js') }}"></script>
    <script>
        $("#share").jsSocials({
            shares: ["twitter", "facebook", "vkontakte", "googleplus", "pinterest"],
            showLabel: false,
            showCount: false,
        });
        $(".social-icon").jsSocials({
            shares: ["twitter", "facebook", "googleplus", "vkontakte", "pinterest"],
            showLabel: false,
            showCount: false,
        });
    </script>
    <script>
    $("#shareNative").jsSocials({
        showLabel: false,
        showCount: false,


        shares: [{
            shareIn : "blank",
            renderer: function() {
                var $result = $("<div>");

                var script = document.createElement("script");
                script.text = "(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = \"//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.3\"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));";
                $result.append(script);

                $("<div>").addClass("fb-share-button")
                    .attr("data-layout", "button_count")
                    .appendTo($result);

                return $result;
            }
        }, {
            shareIn : "blank",
            renderer: function() {
                var $result = $("<div>");

                var script = document.createElement("script");
                script.text = "window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return t;js=d.createElement(s);js.id=id;js.src=\"https://platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,\"script\",\"twitter-wjs\"));";
                $result.append(script);

                $("<a>").addClass("twitter-share-button")
                    .text("Tweet")
                    .attr("href", "https://twitter.com/share")
                    .appendTo($result);

                return $result;
            }
        }]
    });
    $(window).on('load', function () {
      $('#shareNative').css('opacity',1);
    });
    </script>
@endif

<!-- IF REGISTRATION ERROR OCCURES -->
<script>
    $(window).on('load',function(){
        if( $('.register-errors').children().length > 0 ){
            $('#login').modal('show');
        }
    });
</script>
@if (session('status'))
<span style="display: none;" id="ses-status">session('status')</span>
<script>
    $(window).on('load',function(){
        $('#congratulations').modal('show');   
    });
</script>
@endif

@endsection