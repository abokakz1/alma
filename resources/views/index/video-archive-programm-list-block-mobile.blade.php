<?  use App\Models\VideoArchive;
    if($date_start != 0 && $date_end != 0){
        $video_archive_programm_row2 = VideoArchive::LeftJoin('programm_tab','video_archive_tab.programm_id','=','programm_tab.programm_id')->select('video_archive_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en')->whereBetween('video_archive_tab.video_archive_date', array($date_start, $date_end))->groupBy("video_archive_tab.programm_id")->get();
    }
    else{
        if($language_name == "kz"){
            $video_archive_programm_row2 = VideoArchive::LeftJoin('programm_tab','video_archive_tab.programm_id','=','programm_tab.programm_id')
                    ->select('video_archive_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en')
                    ->whereBetween('video_archive_tab.video_archive_date', array("2000-01-01", "2016-08-01"))
                    ->where("video_archive_tab.video_archive_title_kz","!=","")
                    ->whereNotNull("video_archive_tab.video_archive_title_kz")
                    ->groupBy("video_archive_tab.programm_id")
                    ->get();
        }
        else{
            $video_archive_programm_row2 = VideoArchive::LeftJoin('programm_tab','video_archive_tab.programm_id','=','programm_tab.programm_id')
                    ->select('video_archive_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en')
                    ->whereBetween('video_archive_tab.video_archive_date', array("2000-01-01", "2016-08-01"))
                    ->where("video_archive_tab.video_archive_title_ru", "!=", "")
                    ->whereNotNull("video_archive_tab.video_archive_title_ru")
                    ->groupBy("video_archive_tab.programm_id")
                    ->get();
        }
    }
?>
@if(count($video_archive_programm_row2) > 0)
    <option value="0">{{trans("messages.Все программы")}}</option>
    <? $i = 0;?>
    @foreach($video_archive_programm_row2 as $key => $video_archive_programm_item2)
        @if(strlen($video_archive_programm_item2['programm_name_ru']) > 0)
            <? $i++; $active = "";
            ?>
            @if($i == 1)
                <? $active = " active ";?>
                <script>
                    $(document).ready(function(){
                        setProgrammItemListByDateMobile();
                    });
                </script>
            @endif
            <option value="{{$video_archive_programm_item2['programm_id']}}">{{$video_archive_programm_item2['programm_name_ru']}}</option>
        @endif
    @endforeach
@endif

<script>
    $(document).ready(function(){
        $(".ajax-loader").fadeOut(200);
    });
</script>