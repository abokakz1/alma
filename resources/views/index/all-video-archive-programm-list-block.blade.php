<?  use App\Models\VideoArchive;
    if($language_name == "kz"){
        $video_archive_programm_row2 = VideoArchive::LeftJoin('programm_tab','video_archive_tab.programm_id','=','programm_tab.programm_id')
                ->select('video_archive_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en')
                // ->whereBetween('video_archive_tab.video_archive_date', array("2000-01-01", "2016-08-01"))
                ->whereBetween('video_archive_tab.video_archive_date', array( $year."-01-01", $year."-12-31"))
                ->where("video_archive_tab.video_archive_title_kz","!=","")
                ->whereNotNull("video_archive_tab.video_archive_title_kz")
                ->groupBy("video_archive_tab.programm_id")
                ->get();
    }
    else{
        $video_archive_programm_row2 = VideoArchive::LeftJoin('programm_tab','video_archive_tab.programm_id','=','programm_tab.programm_id')
                ->select('video_archive_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en')
                ->whereBetween('video_archive_tab.video_archive_date', array($year."-01-01", $year."-12-31"))
                ->where("video_archive_tab.video_archive_title_ru", "!=", "")
                ->whereNotNull("video_archive_tab.video_archive_title_ru")
                ->groupBy("video_archive_tab.programm_id")
                ->get();
    }
?>
@if(count($video_archive_programm_row2) > 0)
    <? $i = 0; ?>
    <li class="active">
        <a style="cursor: pointer;" onclick="setAllProgrammArchive2(this, {{ $year}})">
            @if($language_name == "kz")
                Барлық бағдарламалар
            @else
                Все программы
            @endif
        </a>
    </li>
    @foreach($video_archive_programm_row2 as $key => $video_archive_programm_item2)
        <? $i++; $active = " ";  ?>
        @if($i == 1)
            <? $active = " active ";?>
        @endif

        <li>
            <a style="cursor: pointer;" onclick="setAllProgrammItemListByPrDate({{$video_archive_programm_item2['programm_id']}},this, {{ $year }})">{{$video_archive_programm_item2['programm_name_ru']}}</a>
        </li>
    @endforeach
@endif

<script>
    $(document).ready(function(){
//        $(".ajax-loader").fadeOut(200);
    });
</script>