@if(count($row) > 0)
    <?
    function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }
    $b = 0; ?>
    @foreach($row as $key => $archive_item)
        @if(strlen($archive_item['video_archive_title_' . $lang]) > 0)
            <? $b++; ?>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="release-item">
                    <a href="{{ LaravelLocalization::getLocalizedURL($lang, "/archive/$archive_item->programm_url_name/$archive_item->video_archive_url_name") }}">
                        @if(strlen($archive_item['image']) > 0)
                            <div class="release-item-img op-darked" style="background-image: url('/video_archive_photo/{{$archive_item['image']}}')">
                        @else
                            <div class="release-item-img op-darked" style="background-image: url('/css/images/videoarchive.jpg')">
                        @endif
                            <div class="img-item-content">
                                <span class="release-item-views">
                                    <i class="icon icon-eye-white"></i>
                                    <span>
                                        <?php
                                        if(strlen($archive_item['youtube_video_code']) > 0){
                                            if(get_http_response_code("https://www.googleapis.com/youtube/v3/videos?part=statistics&id=" . $archive_item['youtube_video_code'] . "&key=AIzaSyA_bs_UFM6LK955j93P9pjoPfECoHMMwx0") != "200"){
                                            }
                                            else{
                                                $json_youtube = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics&id=" . $archive_item['youtube_video_code'] . "&key=AIzaSyA_bs_UFM6LK955j93P9pjoPfECoHMMwx0");
                                                $json_youtube_data = json_decode($json_youtube, true);
                                                if(count($json_youtube_data['items']) > 0){
                                                    echo $json_youtube_data['items'][0]['statistics']['viewCount'];
                                                }
                                            }
                                        }
                                        ?>
                                    </span>
                                </span>
                                <h4 class="release-item-title">{{$archive_item['video_archive_title_' . $lang]}}</h4>
                                <div class="release-item-date">
                                    @if($lang == "kz")
                                        Шығарылым {{$archive_item['video_archive_date']}} бастап
                                    @else
                                        Выпуск от {{$archive_item['video_archive_date']}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endif
    @endforeach

    <div style="clear: both;"></div>
@endif

<script>
    $(document).ready(function(){
        $(".ajax-loader").fadeOut(200);
    });
</script>