<?
$days = array(
        1 => trans("messages.Понедельник"), 2 => trans("messages.Вторник"), 3 => trans("messages.Среда"), 4 => trans("messages.Четверг"),
        5 => trans("messages.Пятница"), 6 => trans("messages.Суббота"), 7 => trans("messages.Воскресенье"));
use App\Models\ProgrammTime;
?>
<div class="row">
    @if(count($row) > 0)
        @foreach($row as $key => $row_item)
            @if(mb_strlen($row_item['programm_name_ru']) > 0)
                <?  $programm_type_name = "";
                    $programm_url = LaravelLocalization::getLocalizedURL($lang, "/programms/" .$row_item->programm_url_name);
                    if($row_item['pr_lang_id'] == 1){
                        $programm_type_name .= " kaz-programm ";
                        $programm_url = "/kz/programms/" .$row_item->programm_url_name;
                    }
                    else if($row_item['pr_lang_id'] == 2){
                        $programm_type_name .= " rus-programm ";
                        $programm_url = "/programms/" .$row_item->programm_url_name;
                    }

                    if($row_item['is_archive'] == 1){
                        $programm_type_name .= " is-archive ";
                    }
                    if($row_item['is_spec_project'] == 1){
                        $programm_type_name .= " is-spec-project ";
                    }

                    if($row_item['is_spec_project'] != 1 && $row_item['is_archive'] != 1){
                        $programm_type_name .= " is-cur-season ";
                    }
                ?>
                    <div class="col-lg-4 col-md-6 col-sm-6 programm-div-list {{$programm_type_name}}">
                    <div class="tv-project-item">
                        <a href="{{ $programm_url }}" style="margin: 0 auto; display: flex;">
                        @if($row_item->programm_logo)
                            <div class="tv-project-item-img tv-project-item_green" style="background-color: initial; margin: 0 auto;">
                                <img src="/programm_photo/{{$row_item->programm_logo}}" style="max-width: 160px !important;">
                            </div>
                        @else
                            <div class="tv-project-item-img tv-project-item_default" style=" margin: 0 auto;"><p><?php echo $row_item['programm_name_ru']; ?></p></div>
                        @endif
                        </a>
                        <?
                            $programm_times = ProgrammTime::select(DB::raw("DISTINCT(day_id)"))->where("programm_id","=",$row_item['programm_id'])->orderBy("day_id")->get();
                        ?>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>

<script>
    $(document).ready(function(){
        $(".ajax-loader").fadeOut(200);
    });
</script>
<style>
.wakeup i{
    margin: 0 !important;
    font-size: 20px !important;
    transform: translateY(-55%);
} .wakeup p{
    font-weight: bold;
    font-size: 20px !important;
} .wakeup span{
    font-size: 15px;
    font-weight: normal;
}.italic p{
    font-style: italic !important;
    font-size: 20px !important;
} .underline span{
    text-decoration: underline !important;
    font-weight: normal !important;
} .underline p{
    font-weight: bold !important;
} .bold p{
    font-weight: bold !important;
} .bold span{
    font-style: italic !important;
    font-weight: 600 !important;
}
</style>