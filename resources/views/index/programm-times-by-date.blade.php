<div class="schedule-list list-group">
    @if(count($row) > 0)
        <?php $i = 0;?>
        @foreach($row as $key => $row_item)
            <?php $i++; ?>
            @if(strlen($row_item['image']) > 0)
                <?php $tv_programm_image = "/tv_programm_photo/" . $row_item['image']; ?>
            @else
                <?php $tv_programm_image = "/category_photo/" . $row_item['category_image']; ?>
            @endif

            <div class="list-group-item">
                <h4 class="schedule-list-title">
                    @if($lang == "kz")
                        {{$row_item['tv_programm_name_kz']}}
                    @else
                        {{$row_item['tv_programm_name_ru']}}
                    @endif
                </h4>

                <div class="schedule-list-desc">
                    {{$row_item['category_name_' . $lang]}}
                </div>
                <span class="schedule-time">{{substr($row_item['time'],0,5)}}</span>
            </div>

        @endforeach
    @endif
</div>


<script>
    $(document).ready(function(){
        $(".ajax-loader").fadeOut(200);
    });
</script>

