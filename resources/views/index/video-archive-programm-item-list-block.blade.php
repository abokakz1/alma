<?  use App\Models\VideoArchive;
    if($programm_id > 0){
        $video_archive_programm_row = VideoArchive::LeftJoin('programm_tab','video_archive_tab.programm_id','=','programm_tab.programm_id')
                ->select('video_archive_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en','programm_tab.programm_url_name', DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'))
                ->whereBetween('video_archive_tab.video_archive_date', array($date_start, $date_end))
                ->where("video_archive_tab.programm_id","=",$programm_id)
                ->orderByRaw("video_archive_tab.video_archive_date asc")
                ->paginate(30);
    }
    else{
        $video_archive_programm_row = VideoArchive::LeftJoin('programm_tab','video_archive_tab.programm_id','=','programm_tab.programm_id')
                ->select('video_archive_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en','programm_tab.programm_url_name', DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'))
                ->whereBetween('video_archive_tab.video_archive_date', array($date_start, $date_end))
                ->orderByRaw("video_archive_tab.video_archive_date asc")
                ->paginate(30);
    }

    function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }
?>
@if(count($video_archive_programm_row) > 0)
<?php  $i = 0; ?>
    @foreach($video_archive_programm_row as $key => $video_archive_programm_item)
        @if(strlen(trim($video_archive_programm_item['video_archive_title_' . $language_name])) < 1)
            @if($language_name == "ru")
                <? $video_archive_programm_item['video_archive_title_' . $language_name] = $video_archive_programm_item['video_archive_title_kz']; ?>
            @else
                <? $video_archive_programm_item['video_archive_title_' . $language_name] = $video_archive_programm_item['video_archive_title_ru']; ?>
            @endif
        @endif
        @if(strlen(trim($video_archive_programm_item['video_archive_title_' . $language_name])) > 0)
    <?php  $i++;
        $programm_url_name = "default";
        if(strlen($video_archive_programm_item['programm_url_name']) > 0){
            $programm_url_name = $video_archive_programm_item['programm_url_name'];
        }
    ?>

        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="release-item">
                @if($language_name == "kz")
                    <a href="/kz/archive/{{$programm_url_name}}/{{$video_archive_programm_item['video_archive_url_name']}}">
                @else
                    <a href="/archive/{{$programm_url_name}}/{{$video_archive_programm_item['video_archive_url_name']}}">
                @endif
                    @if(strlen($video_archive_programm_item['image']) > 0)
                        <div class="release-item-img op-darked" style="background-image: url('/video_archive_photo/{{$video_archive_programm_item['image']}}')">
                    @else
                        <div class="release-item-img op-darked" style="background-image: url('/css/images/videoarchive.jpg')">
                    @endif

                        <div class="img-item-content">
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
                            <h4 class="release-item-title">{{$video_archive_programm_item['video_archive_title_' . $language_name]}}</h4>
                            <div class="release-item-date">
                                @if($language_name == "kz")
                                    Шығарылым {{$video_archive_programm_item['video_archive_date']}} бастап
                                @else
                                    Выпуск от {{$video_archive_programm_item['video_archive_date']}}
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endif
    @endforeach
@endif

<script>
    $(document).ready(function(){
        $(".ajax-loader").fadeOut(200);
    });
</script>