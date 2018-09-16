
<html prefix="og: http://ogp.me/ns#" prefix="ya: https://webmaster.yandex.ru/vocabularies/">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="{{ asset('css/images/favicon.png') }}" type="image/x-icon">

    <meta name="google-site-verification" content="o0B5uU6c7GV4grBW0_5WavkI-SvGSoQbVSeqDkhitu0" />
    <meta content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" name="viewport">
    <meta content="#1d1f1f" name="theme-color">
    <meta name="yandex-verification" content="728bc79f92eb2ca4" />

    <link rel="stylesheet" type="text/css" href="{{ asset('css/header_style.css') }}">
    <script src="{{ asset('js/mobile-detect.js') }}"></script>
    <link href="{{ asset('css/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('css/swiper/swiper.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    
    <link href="{{ asset('css/banner.css') }}" rel="stylesheet">
    <link href="{{ asset('css/banner_blog.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mobile.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/banner.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/main.js') }}" type="text/javascript"></script>
    <link href="/css/dashboard.css" rel="stylesheet">
    
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="/extended_page/fonts/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/jssocials/jssocials.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('js/jssocials/jssocials-theme-plain.css') }}" />

    <!-- For Events -->
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
    <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('js/ru.js') }}"></script>
    <!-- End of>>> For Events -->
    
    <?php
    $action_name = "";
    $action_name_2 = "";
    $controller_name = "";
    if (!empty(Route::getFacadeRoot()->current())) {
        $current_path_parts = explode("/", Route::getFacadeRoot()->current()->uri());
    }
    
    if(isset($current_path_parts[0])){
        $controller_name = $current_path_parts[0];
    }
    if(isset($current_path_parts[1])){
        $action_name = $current_path_parts[1];
    }
    if(isset($current_path_parts[2])){
        $action_name_2 = $current_path_parts[2];
    }
    ?>

    @if(($controller_name == "kz" && $action_name == "news" && $action_name_2 == "news") || ($controller_name == "news" && $action_name == "news"))
        @if(isset($row))

        @if(strlen($row->image_big) > 0)
            <?php $og_image = "/news_photo/" . $row->image_big; ?>
        @else
            <?php $og_image = "/css/images/no_news_img" . rand(1,3) . ".png"; ?>
        @endif

        <meta property="og:title" content="{{$row['news_title_' . App::getLocale()]}}"/>
        <meta property="og:description" content="{{$row['news_meta_desc_'.App::getLocale()]}}"/>
        <meta property="og:site_name" content="almaty.tv" />
        <meta property="og:type" content="website">
        <meta property="fb:app_id" content="115481582470236">
        <meta property="og:url" content="https://almaty.tv/{{Request::path()}}">
        <meta property="og:locale" content="ru_RU" />
        <meta property="og:image" content="http://almaty.tv<?php echo $og_image; ?>">
        <meta property="og:image:secure_url" content="https://almaty.tv<?php echo $og_image; ?>">
        <meta property="og:image:type" content="image/jpeg" />
        <meta property="og:image:width" content="320" />
        <meta property="og:image:height" content="240" />
        <title>{{$row['news_title_' . App::getLocale()]}}</title>
        <meta name="description" content="{{$row['news_meta_desc_'.App::getLocale()]}}">
        @endif
    @elseif(($controller_name == "kz" && $action_name == "archive" && strlen($action_name_2) > 0) || ($controller_name == "archive" && strlen($action_name) > 0))
        <meta property="og:title" content="{{$row['video_archive_title_' . App::getLocale()]}}"/>
        @if($row["video_archive_meta_desc"])
            <meta property="og:description" content='{{ $row["video_archive_meta_desc"] }}'/>
            <meta name="description" content='{{$row["video_archive_meta_desc"]}}'>
        @else
            @if($controller_name == "kz")
                <meta property="og:description" content='Almaty.tv арнасы Алматы  мен Қазақстанның өзекті және жаңа жаңалықтарын ұсынады. Біздің арнада бағдарламалар мен фильмдер көріп, уақытыңызды қызықты өткізе аласыздар'/>
                <meta name="description" content="Almaty.tv арнасы Алматы  мен Қазақстанның өзекті және жаңа жаңалықтарын ұсынады. Біздің арнада бағдарламалар мен фильмдер көріп, уақытыңызды қызықты өткізе аласыздар">
            @else
                <meta name="description" content="Телеканал Almaty.tv предоставляет актуальные и свежие новости г. Алматы и Казахстана. У нас вы можете посмотреть передачи, фильмы и интересно провести время.">
                <meta property="og:description" content='Телеканал Almaty.tv предоставляет актуальные и свежие новости г. Алматы и Казахстана. У нас вы можете посмотреть передачи, фильмы и интересно провести время.'/>
            @endif
        @endif

        <meta property="og:site_name" content="almaty.tv" />
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{Request::url()}}">
        <meta property="og:image" content="https://almaty.tv/video_archive_photo/{{$row['image']}}">
        <!-- <meta property="og:image" content="http://almaty.tv/css/image/logo_new.jpg"> -->
        <meta property="og:image:width" content="320" />
        <meta property="og:image:height" content="240" />
        <title>{{$row['video_archive_title_' . App::getLocale()]}}</title>

    <!-- BLOGS -->
    @elseif(($controller_name == "kz" && $action_name == "blogs" && strlen($action_name_2) > 0) || ($controller_name == "blogs" && strlen($action_name) > 0))
        @if(isset($blog))
            <meta property="og:title" content="{{ $blog->title }}"/>
            <meta property="og:site_name" content="almaty.tv" />
            <meta property="og:type" content="website">
            <meta property="og:url" content="{{Request::url()}}">
            <meta property="og:image" content="https://almaty.tv{{ $blog->image}}">
            <meta property="og:image:width" content="320" />
            <meta property="og:image:height" content="240" />
            <title>{{ $blog->title }}</title>
            @if(!empty($blog->blog_meta_desc))
                <meta property="og:description" content='{{ $blog->blog_meta_desc }}'/>
                <meta name="description" content='{{ $blog->blog_meta_desc }}'>
            @else
                @if($controller_name == "kz")
                    <meta property="og:description" content='Almaty.tv арнасы Алматы  мен Қазақстанның өзекті және жаңа жаңалықтарын ұсынады. Біздің арнада бағдарламалар мен фильмдер көріп, уақытыңызды қызықты өткізе аласыздар'/>
                    <meta name="description" content="Almaty.tv арнасы Алматы  мен Қазақстанның өзекті және жаңа жаңалықтарын ұсынады. Біздің арнада бағдарламалар мен фильмдер көріп, уақытыңызды қызықты өткізе аласыздар">
                @else
                    <meta name="description" content="Телеканал Almaty.tv предоставляет актуальные и свежие новости г. Алматы и Казахстана. У нас вы можете посмотреть передачи, фильмы и интересно провести время.">
                    <meta property="og:description" content='Телеканал Almaty.tv предоставляет актуальные и свежие новости г. Алматы и Казахстана. У нас вы можете посмотреть передачи, фильмы и интересно провести время.'/>
                @endif
            @endif                
        @endif

    <!-- EVENTS -->
    @elseif(($controller_name == "kz" && $action_name == "events" && strlen($action_name_2) > 0) || ($controller_name == "events" && strlen($action_name) > 0))
        <!-- <link href="/css/events.css" rel="stylesheet" type="text/css"> -->
        <meta property="og:site_name" content="almaty.tv" />
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{Request::url()}}">
        <meta property="og:image:width" content="320" />
        <meta property="og:image:height" content="240" />
        @if(isset($event))
            <?php
            $event_img = "";
            if(count($event->media)>0){
                $event_img = $event->media[0]->link;
            }
            else { $event_img = 'event_def.jpg'; }
            ?>
            <meta property="og:image" content="https://almaty.tv/event_photo/{{$event_img}}">
            <meta property="og:title" content="{{$event->title}}"/>
            <title>{{$event->title}}</title>
            <meta name="description" content="{{$event->event_meta_desc}}">
            <meta property="og:description" content='{{$event->event_meta_desc}}'/>            
        @endif
    <!-- END >>> EVENTS -->


    @else
        <meta property="og:site_name" content="almaty.tv" />
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{Request::url()}}">
        <meta property="og:image:width" content="320" />
        <meta property="og:image:height" content="240" />
        @if($controller_name == "kz")
            <meta property="og:image" content="https://almaty.tv/css/messenger_kz.jpg">
            <meta property="og:title" content="Қазақстан мен Алматының соңғы жаңалықтары | Алматы.тв телеарнасы"/>
            <title>Қазақстан мен Алматының соңғы жаңалықтары | Алматы.тв телеарнасы</title>
            <meta name="description" content="Almaty.tv арнасы Алматы  мен Қазақстанның өзекті және жаңа жаңалықтарын ұсынады. Біздің арнада бағдарламалар мен фильмдер көріп, уақытыңызды қызықты өткізе аласыздар">
            <meta property="og:description" content='Almaty.tv арнасы Алматы  мен Қазақстанның өзекті және жаңа жаңалықтарын ұсынады. Біздің арнада бағдарламалар мен фильмдер көріп, уақытыңызды қызықты өткізе аласыздар'/>

        @else
            <meta property="og:image" content="https://almaty.tv/css/messenger_ru.jpg">
            <meta property="og:title" content='Актуальные новости Алматы и Казахстана | телеканал Almaty.tv'/>
            <title>Актуальные новости Алматы и Казахстана | телеканал Almaty.tv</title>
            <meta name="description" content="Телеканал Almaty.tv предоставляет актуальные и свежие новости г. Алматы и Казахстана. У нас вы можете посмотреть передачи, фильмы и интересно провести время. ">
            <meta property="og:description" content='Телеканал Almaty.tv предоставляет актуальные и свежие новости г. Алматы и Казахстана. У нас вы можете посмотреть передачи, фильмы и интересно провести время. '/>
        @endif
    @endif


    @if(($controller_name == "kz" && $action_name == "events") || ($controller_name == "events"))
        <link href="{{ asset('css/events_style.css') }}" rel="stylesheet">
    @endif
    @if(($controller_name == "kz" && $action_name == "about") || ($controller_name == "about"))
        <link href="/css/about-us.css" rel="stylesheet" type="text/css">
    @endif
    @if(($controller_name == "kz" && $action_name == "") || ($controller_name == ""))
        <!-- Extended Page -->
        <link href="{{ asset('extended_page/css/style.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('extended_page/css/animate_css/animate.min.css') }}">
        <link href="{{ asset('extended_page/css/responsive.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('extended_page/fonts/font-awesome/css/font-awesome.min.css') }}">
        <!-- End of Extended Page -->
    @endif
    
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-84357438-6', 'auto');
  ga('send', 'pageview');

</script>

</head>
@yield('layout')

</html>
