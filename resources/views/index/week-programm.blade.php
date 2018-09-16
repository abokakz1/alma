<?php $date = ""; ?>
<?php
$monthes = array(
        1 => trans("messages.Января"), 2 => trans("messages.Февраля"), 3 => trans("messages.Марта"), 4 => trans("messages.Апреля"),
        5 => trans("messages.Мая"), 6 => trans("messages.Июня"), 7 => trans("messages.Июля"), 8 => trans("messages.Августа"),
        9 => trans("messages.Сентября"), 10 => trans("messages.Октября"), 11 => trans("messages.Ноября"), 12 => trans("messages.Декабря"));
$days = array(
        1 => trans("messages.Понедельник"), 2 => trans("messages.Вторник"), 3 => trans("messages.Среда"), 4 => trans("messages.Четверг"),
        5 => trans("messages.Пятница"), 6 => trans("messages.Суббота"), 0 => trans("messages.Воскресенье"));
?>
<div class="row" >
    @if(count($row) > 0)
        <?php $i = 0; ?>
        @foreach($row as $key => $row_item)
            <?php $i++; $bool = false; ?>
            @if(strlen($row_item['image']) > 0)
                <?php $tv_programm_image = "/tv_programm_photo/" . $row_item['image']; ?>
            @else
                <?php $tv_programm_image = "/category_photo/" . $row_item['category_image']; ?>
            @endif

            @if($row_item['date'] != $date)
                <?php
                    $date = $row_item['date'];
                    if($i != 1){ echo "</div>"; $bool = true; }
                ?>
                <h3 class="title_date">{{$days[date('w', strtotime($row_item['date']))]}}, {{date('j', strtotime($row_item['date']))}} {{$monthes[date('n', strtotime($row_item['date']))]}}</h3>
                <div class=" newScrollBar owl-carousel">
            @endif
                    <div class="col-md-12 list_programms">
                        <style>
                            .div-new<?php echo $i?>:hover, .div-new<?php echo $i?>-active {
                                background-image: url("<?php echo $tv_programm_image;?>");
                                background-repeat: no-repeat;
                            }
                        </style>
                        <div class="col-md-1 col-sm-2 col-xs-3 ">
                            <span class="time_transmission active">{{substr($row_item['time'],0,5)}}</span>
                        </div>
                        <div class="col-md-8 col-sm-10 col-xs-9 list_program_peredach div-new<?php echo $i;?> @if($i == 1) .div-new<?php echo $i?>-active @endif">
                            <span class="title_transmission">
                                @if($lang == "kz")
                                    {{$row_item['tv_programm_name_kz']}}
                                @else
                                    {{$row_item['tv_programm_name_ru']}}
                                @endif
                            </span>
                            <span class="social_transmission hidden-xs">
                                @if($lang == "kz")
                                    {{$row_item['category_name_kz']}}
                                @elseif($lang == "en")
                                    {{$row_item['category_name_en']}}
                                @else
                                    {{$row_item['category_name_ru']}}
                                @endif
                            </span>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div style="clear: both;"></div>
        @endforeach
    @endif
</div>

<script>
    $(document).ready(function(){
        $(".ajax-loader").fadeOut(200);
    });
</script>