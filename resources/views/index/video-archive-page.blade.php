@extends('index.layout')

@section('content')
    <div class="page-header">
        <div class="back-link-block">
            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
        </div>
        <div class="page-title-block">
            <h1 class="page-title">{{$row['video_archive_title_' . App::getLocale()]}} </h1>
        </div>
    </div>

    <div class="content-block content-block-sm" style="margin: 0 auto;">
        <h3 class="video-title">
            @if(App::getLocale() == "kz")
                Шығарылым {{$row['video_archive_date']}} бастап / <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/programms/".$row['programm_url_name']) }}">{{$row['programm_name_ru']}}</a>
            @else
                Выпуск от {{$row['video_archive_date']}} / <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/programms/".$row['programm_url_name']) }}"> {{$row['programm_name_ru']}}</a>
            @endif
        </h3>


        <div class="embed-responsive embed-responsive-16by9">
            <iframe allowfullscreen frameborder="0" src="https://www.youtube.com/embed/{{$row['youtube_video_code']}}"></iframe>
        </div>

        <p><br>
        </p>

        <style>
            .fb-share-button{
                margin-top: 0px !important; float: left !important;
            }
        </style>
        <div class="fb-share-button"
             data-href="{{Request::url()}}"
             data-layout="button_count">
        </div>
        <div style="float: left; margin-left: 10px; margin-top: 0px;">
            <a href="https://twitter.com/share" class="twitter-share-button" data-via="almaty.tv" data-count="none" style="margin-top: -10px;">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        </div>
        <div style="clear: both; padding: 20px 0px;">
            {!! $row['video_description_' . App::getLocale()] !!}
        </div>

    </div>

    <section class="related-videos bg-gray-light">
        <div class="container">
            <h4 class="related-videos-title">
                <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/programms/".$row['programm_url_name']) }}"> {{trans("messages.Другое видео")}}</a></h4>

            <div class="row row-lg">
                <?  use App\Models\VideoArchive;
                    $video_archive_programm_row = VideoArchive::LeftJoin('programm_tab','video_archive_tab.programm_id','=','programm_tab.programm_id')
                        ->select('video_archive_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en','programm_tab.programm_url_name', DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'))
                        ->where("video_archive_tab.video_archive_id","!=",$row['video_archive_id'])
                        ->where("video_archive_tab.programm_id","=",$row['programm_id'])
                        ->orderByRaw("video_archive_tab.video_archive_date desc")
                        ->get();
                    function get_http_response_code($url) {
                        $headers = get_headers($url);
                        return substr($headers[0], 9, 3);
                    }
                    ?>

                    @if(count($video_archive_programm_row) > 0)
                        <?php

                        $i = 0; ?>
                        @foreach($video_archive_programm_row as $key => $video_archive_programm_item)
                                @if(strlen(trim($video_archive_programm_item['video_archive_title_' . App::getLocale()])) < 1)
                                    @if( App::getLocale() == "ru")
                                        <? $video_archive_programm_item['video_archive_title_' . App::getLocale()] = $video_archive_programm_item['video_archive_title_kz']; ?>
                                    @else
                                        <? $video_archive_programm_item['video_archive_title_' . App::getLocale()] = $video_archive_programm_item['video_archive_title_ru']; ?>
                                    @endif
                                @endif

                            @if(strlen(trim($video_archive_programm_item['video_archive_title_' . App::getLocale()])) > 0)
                                <?php  $i++; ?>
                                @if($i < 5)
                                        
                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <div class="release-item">
                                                <a href="/archive/{{$video_archive_programm_item['programm_url_name']}}/{{$video_archive_programm_item['video_archive_url_name']}}">
                                                    @if(strlen($video_archive_programm_item['image']) > 0)
                                                        <div class="release-item-img" style="background-image: url('/video_archive_photo/{{$video_archive_programm_item['image']}}')">
                                                    @else
                                                        <div class="release-item-img" style="background-image: url('/css/images/videoarchive.jpg')">
                                                    @endif
                                                        <span class="release-item-views">
                                                            <i class="icon icon-eye-white"></i>
                                                            <span>
                                                        <?php
                                                                if(strlen($video_archive_programm_item['youtube_video_code']) > 0){
                                                                    if(get_http_response_code("https://www.googleapis.com/youtube/v3/videos?part=statistics&id=" . $video_archive_programm_item['youtube_video_code'] . "&key=AIzaSyA_bs_UFM6LK955j93P9pjoPfECoHMMwx0") != "200"){
                                                                    }
                                                                    else{
                                                                        $json_youtube = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics&id=" . $video_archive_programm_item['youtube_video_code'] . "&key=AIzaSyA_bs_UFM6LK955j93P9pjoPfECoHMMwx0");
                                                                        $json_youtube_data = json_decode($json_youtube, true);
                                                                        if(count($json_youtube_data['items']) > 0){
                                                                            echo $json_youtube_data['items'][0]['statistics']['viewCount'];
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </span>
                                                        </span>

                                                        <h4 class="release-item-title">{{$video_archive_programm_item['video_archive_title_' . App::getLocale()]}}</h4>

                                                        <div class="release-item-date">
                                                            @if(App::getLocale() == "kz")
                                                                Шығарылым {{$video_archive_programm_item['video_archive_date']}} бастап
                                                            @else
                                                                Выпуск от {{$video_archive_programm_item['video_archive_date']}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                @endif
                            @endif
                        @endforeach
                    @endif
            </div>
        </div>
    </section>
@endsection