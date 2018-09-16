<?  use App\Models\VideoArchive;
    $video_archive_programm_row2 = VideoArchive::LeftJoin('programm_tab','video_archive_tab.programm_id','=','programm_tab.programm_id')
            ->select('video_archive_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en')
            ->whereBetween('video_archive_tab.video_archive_date', array($date_start, $date_end))
            ->groupBy("video_archive_tab.programm_id")
            ->get();
?>
@if(count($video_archive_programm_row2) > 0)
    <li class="0">
        <a style="cursor: pointer;" onclick="setProgrammItemListByPrDate('{{$date_start}}','{{$date_end}}',0)">
            @if($language_name == "kz")
                Барлық бағдарламалар
            @else
                Все программы
            @endif
        </a>
    </li>

    <? $i = 0; $is_prog = false; ?>
    @if(isset($programm_id))
        <script>
            $(document).ready(function(){
                setProgrammItemListByPrDate('{{$date_start}}','{{$date_end}}',{{$programm_id}});
            });
        </script>
        <? $is_prog = true; ?>
    @endif

    @foreach($video_archive_programm_row2 as $key => $video_archive_programm_item2)
        <? $i++; $active = "";
        ?>
        @if($i == 1 && !$is_prog)
            <? $active = " active ";?>
            <script>
                $(document).ready(function(){
                    setProgrammItemListByPrDate('{{$date_start}}','{{$date_end}}',{{$video_archive_programm_item2['programm_id']}});
                });
            </script>
        @endif

        <li class="{{$active}} {{$video_archive_programm_item2['programm_id']}}">
            <a style="cursor: pointer;" onclick="setProgrammItemListByPrDate('{{$date_start}}','{{$date_end}}',{{$video_archive_programm_item2['programm_id']}})">{{$video_archive_programm_item2['programm_name_ru']}}</a>
        </li>
    @endforeach
@endif

<script>
    $(document).ready(function(){
        $(".ajax-loader").fadeOut(200);
    });
</script>