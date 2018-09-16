<?  use App\Models\Advertisement;
    $vertical_advertisement_list = Advertisement::where("is_main_advertisement","=","3")->where("is_active","=","1")->first(); ?>
@if(count($vertical_advertisement_list) > 0)
    <a href="{{$vertical_advertisement_list->link}}">
        <div class="row next_tv" style="background-image: url('/adv/{{$vertical_advertisement_list['image']}}')">
            @if(mb_strlen($vertical_advertisement_list['advertisement_text_' . App::getLocale()]) > 0)
                <p class="next_tv_green"><?php echo $vertical_advertisement_list['advertisement_text_' . App::getLocale()]; ?></p>
            @endif

            @if(mb_strlen($vertical_advertisement_list['advertisement_title_' . App::getLocale()]) > 0)
                <p class="next_tv_more_vercion"><b><a href="{{$vertical_advertisement_list->link}}">{{$vertical_advertisement_list['advertisement_title_' . App::getLocale()]}}</a></b></p>
            @endif
        </div>
    </a>
@endif