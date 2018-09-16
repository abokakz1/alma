<?php 
    // dd($footer_data['footers']);

    // foreach ($main as $m) {
    //     echo "<b>".$m->name_ru."</b><br>";
    //     $subitems = $items->filter(function ($value, $key) use($m){
    //         return $value->parent_id == $m->id;
    //     });
    //     foreach ($subitems as $s) {
    //         echo "<i>".$s->name_ru."</i><br>";    
    //     }
    // }
?>
<div class="footer-two">
    <div class="container-fluid">
        <a href="/">
            <img alt="AlmatyTv" style="width: 170px;margin: 0px 5px 20px;" src="/css/img/logo.png">
        </a>
        <div class="row">
            <div class="col-xs-12 col-md-4 move-down">
                <div class="social">
                    <p>{{ trans('messages.footer_1') }} &copy;</p>
                    <p>{{ trans('messages.footer_2') }}</p>
                    <p>{{ trans('messages.footer_3') }}</p>
                    <p>{{ trans('messages.footer_4') }}</p>
                    <p>{{ trans('messages.footer_5') }}</p>
                    <a href="https://www.facebook.com/almaty.tv"><i class="fa fa-facebook"></i></a>
                    <!-- <a href="https://twitter.com/almaty_tv"><i class="fa fa-twitter"></i></a> -->
                    <a href="https://www.instagram.com/almaty.tv/"><i class="fa fa-instagram"></i></a>
                    <a href="https://www.youtube.com/user/AlmatyUS"><i class="fa fa-youtube"></i></a>
                    <!-- <a href="#"><i class="fa fa-linkedin"></i></a>
                    <a href="#"><i class="fa fa-pinterest"></i></a>  -->
                </div>
            </div>

            @foreach($footer_data['main_footers'] as $m)
            <div class="col-xs-12 col-md-2 move-down">
                <div class="footer-two__link">
                    <p class="footer-two__link__head">{{ $m->name }}</p>
                    <?php
                    $subitems = $footer_data['footers']->filter(function ($value, $key) use($m){
                        return $value->parent_id == $m->id;
                    });?>
                    @foreach($subitems as $s)
                    <p><a href="{{$s->url}}">{{ $s->name }}</a></p>
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $("#footer_subscribtion").click(function(){
        $(".sidebar-toggle").toggleClass("open"), $("#sidebar").toggleClass("open");
        $(".delivery-mail").focus();
    });
});
</script>

<style>
.footer-two{
    /*background-color: #68003d;*/
    padding: 30px 0;
    color: #fff;
    background: linear-gradient(180deg, rgba(79,0,48,1) 0%, rgba(115,0,70,1) 100%);
}
.social{
    font-size:12px; 
    line-height: 24px;
    color: #c1bfbf;
}
.social a{
    color: #fff;
    font-size: 26px;
    margin-right: 38px;
}
.footer-two__link a{
    font-size: 12px;
    color: #c1bfbf;
}
.footer-two__link__head{
    text-transform: uppercase;
}
.grid {
  display: grid;
  display: -ms-grid;
  grid-template-columns: 500px repeat(3, 1fr);
  grid-column-gap: 20px;
  -ms-grid-columns: 500px repeat(3, 1fr);
  -ms-grid-gap: 20px;
  padding: 0 15px;
}
#footer_subscribtion{
	cursor: hand;
}

@media (max-width:767px) {
    .grid {
        display: block;
        grid-template-rows: repeat(6, 1fr);
        -ms-grid-rows: repeat(6, 1fr);
    } 
    .grid > div{
        margin-bottom: 30px;
    }
    .grid > div:last-child{
        margin-bottom: 0;
    }
    .move-down{
        margin-bottom: 30px;
    }
}
</style>