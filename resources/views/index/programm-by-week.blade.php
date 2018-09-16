<div class="row">
    <?php use App\Models\ProgrammTime;
    $days = array(
            1 => 'Понедельник', 2 => 'Вторник', 3 => 'Среда', 4 => 'Четверг',
            5 => 'Пятница', 6 => 'Суббота', 7 => 'Воскресенье');
    ?>
    @if(count($row) > 0)
        @foreach($row as $key => $row_item)
            <?php
            $row_programm = ProgrammTime::LeftJoin("programm_tab","programm_time_tab.programm_id","=","programm_tab.programm_id")
                                    ->select("programm_tab.*", 'programm_time_tab.time')
                                    ->where("programm_time_tab.day_id","=",$row_item['day_id'])
                                    ->where("programm_tab.programm_id",">","0")
                                    ->orderBy("programm_tab.order_num","asc")
                                    ->get();
            ?>
            @if(count($row_programm) > 0)
                @foreach($row_programm as $key => $row_program_item)

                    <div class="col-md-12 painted_img">
                        <a href="{{ LaravelLocalization::getLocalizedURL($lang, "/programms/$row_program_item->programm_url_name") }}">
                            @if(strlen($row_program_item->image) > 0)
                                <img src="/programm_photo/{{$row_program_item->image}}" class="img-responsive center-block" alt="" id="fon_img">
                            @else
                                <img src="/css/images/teleproject.png" class="img-responsive center-block" alt="" id="fon_img">
                            @endif
                        </a>
                        <div class="col-md-3 col-sm-3 col-xs-3 tele_logo">
                            @if(strlen($row_program_item->programm_logo) > 0)
                                <img src="/programm_photo/{{$row_program_item->programm_logo}}" class="img-responsive" alt="">
                            @endif
                        </div>
                        <div class="inset_text">
                            <div class="hidden-xs"><?php echo $row_program_item['programm_description_' . $lang]; ?></div>
                            <p><a href="{{ LaravelLocalization::getLocalizedURL($lang, "/programms/$row_program_item->programm_url_name") }}" style="color:#91ba42">Подробнее о передаче</a></p>
                        </div>
                    </div>
                @endforeach
            @endif


        @endforeach
    @endif
</div>

