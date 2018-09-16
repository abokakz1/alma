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
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="tv-project-item">
                        <a href="{{ LaravelLocalization::getLocalizedURL($lang, "/programms/$row_item->programm_url_name") }}">
                            @if(strlen($row_item->programm_logo) > 0)
                                <div class="tv-project-item-img" style="background-image: url('/programm_photo/{{$row_item->programm_logo}}')"></div>
                            @else
                                <div class="tv-project-item-img" style="background-image: url('/css/images/teleproject.png')"></div>
                            @endif

                        </a>
                        <?
                        $programm_times = ProgrammTime::select(DB::raw("DISTINCT(day_id)"))->where("programm_id","=",$row_item['programm_id'])->orderBy("day_id")->get();
                        ?>
                        <div class="tv-project-item-desc">
                            <div class="tv-project-item-schedule">
                                @if(count($programm_times) > 0)
                                    <i class="icon icon-calendar-green"></i>
                                    <span>
                                            <? $b = 0; ?>
                                            @foreach($programm_times as $key => $programm_time)
                                                <? $b++; ?>
                                                {{$days[$programm_time->day_id]}}
                                                @if($b != count($programm_times)) , @endif
                                            @endforeach
                                    </span>
                                @endif
                            </div>
                            <a class="tv-project-item-title" href="{{ LaravelLocalization::getLocalizedURL($lang, "/programms/$row_item->programm_url_name") }}">
                                <?php echo $row_item['programm_name_ru']; ?>
                            </a>
                        </div>
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

