@if(count($video_archive_programm_row) > 0)
<?php
    function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }
    $i = 0; ?>
    @foreach($video_archive_programm_row as $key => $video_archive_programm_item)
        @if(strlen(trim($video_archive_programm_item['video_archive_title_' . $language_name])) > 0)
    <?php  $i++;
        $programm_url_name = "default";
        if(strlen($video_archive_programm_item['programm_url_name']) > 0){
            $programm_url_name = $video_archive_programm_item['programm_url_name'];
        }
    ?>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="release-item">
                    <a href="/archive/{{$programm_url_name}}/{{$video_archive_programm_item['video_archive_url_name']}}">
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

    <div style="clear: both;"></div>
    @if($last_page > 1)
		<?
		$nazad = "Назад";
		$vpered = "Вперед";
		?>
		@if($language_name == "kz")
			<?
			$nazad = "Артқа";
			$vpered = "Алға";
			?>
		@endif
        <ul class="pagination">
            @if($page == 1)
                <li class="disabled"><span>{{$nazad}}</span></li>
            @else
                <li style="cursor: pointer;" onclick="setAllProgrammArchiveByPag({{$page-1}}, {{$year}})"><span>{{$nazad}}</span></li>
            @endif


            @for($i = 5; $i > 0; $i--)
                @if($page - $i > 0)
                    <li @if($page - $i == $page) class="active" @else onclick="setAllProgrammArchiveByPag({{$page-$i}}, {{$year}})" @endif style="cursor: pointer;"><span>{{$page - $i }}</span></li>
                @endif
            @endfor

            @for($i = 0; $i < 6; $i++)
                @if($page + $i != $last_page && $page + $i <= $last_page)
                    <li @if($page + $i == $page) class="active" @else onclick="setAllProgrammArchiveByPag({{$page+$i}}, {{$year}})" @endif style="cursor: pointer;"><span>{{$page + $i }}</span></li>
                @endif
            @endfor

            @if($page != $last_page)
                <li style="cursor: pointer;"><a rel="next" onclick="setAllProgrammArchiveByPag({{$page+1}}, {{$year}})">{{$vpered}}</a></li>
            @else
                <li class="disabled"><a rel="next">{{$vpered}}</a></li>
            @endif
        </ul>
    @endif
@endif

<script>
    function setAllProgrammArchiveByPag(page, year){
        $(".ajax-loader").fadeIn(200);
        $(".programm-item-list-block").empty();
        $(".programm-item-list-block").load("/index/all-video-archive-programm-item-list-block/" + page + "/{{$language_name}}/{{$programm_id}}?year="+year);
    }
</script>

<script>
    $(document).ready(function(){
        $(".ajax-loader").fadeOut(200);
        $("html, body").animate({
            scrollTop: 0
        }, 600);
    });
</script>