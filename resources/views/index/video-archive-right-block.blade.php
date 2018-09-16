<div class="row video_win">
    <h3><b><a href="/archive">{{trans("messages.Видеоархив")}}</a></b></h3>
    <p>...</p>
<?php use App\Models\VideoArchive;
      $new_video_archive_row = VideoArchive::LeftJoin('programm_tab','video_archive_tab.programm_id','=','programm_tab.programm_id')
                              ->select('video_archive_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'programm_tab.programm_url_name')
                              ->orderByRaw("video_archive_tab.video_archive_date asc")
                              ->take(4)
                              ->get();
?>
    @if(count($new_video_archive_row) > 0)
        @foreach($new_video_archive_row as $key => $new_video_archive_item)
            @if(strlen(trim($new_video_archive_item['video_archive_title_' . App::getLocale()])) > 0)

                @if(strlen($new_video_archive_item->image) > 0)
                    <style>
                        .inset_video<?php echo $new_video_archive_item['video_archive_id']; ?>:hover{
                            background-image: url('/video_archive_photo/<?php echo $new_video_archive_item->image; ?>');
                        }
                    </style>
                @else
                    <style>
                        .inset_video<?php echo $new_video_archive_item['video_archive_id']; ?>:hover{
                            background-image: url('/css/images/videoarchive.jpg');
                        }
                    </style>
                @endif

                @if(strlen($new_video_archive_item['programm_url_name']) < 1)
                    <? $new_video_archive_item['programm_url_name'] = "default"; ?>
                @endif
                <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/archive/" . $new_video_archive_item['programm_url_name'] . "/" . $new_video_archive_item['video_archive_url_name']) }}">
                    <div class="inset_video inset_video{{$new_video_archive_item['video_archive_id']}}">
                        <h4>{{$new_video_archive_item['programm_name_ru']}}: </h4>
                        <p><a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/archive/" . $new_video_archive_item['programm_url_name'] . "/" . $new_video_archive_item['video_archive_url_name']) }}">{{$new_video_archive_item['video_archive_title_' . App::getLocale()]}}</a></p>
                    </div>
                </a>
            @endif
        @endforeach
    @endif
</div>
