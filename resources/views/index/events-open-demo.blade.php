@extends('index.layout')

@section('content')
    
    <div class="banner" style="background-image: url(@if(count($event->media)>0)'/event_photo/{{ $event->media[0]->link }}'@else'/event_photo/event_def.jpg'@endif)">
        <div class="bg-heading">
        <div class="banner-title">
            <h1>{{ $event->title }}</h1>
        </div>
        </div>
    </div>
    <div class="open-event-contact">
        <div class="container">
            <div class="row" style="position:relative">
                <div class="col-xs-12 col-md-6 open-event-contact-item">
                    <h2>{{ $event->title }}</h2>
                    <p>{!! $event->text !!}</p>
                    <h3>{{trans('messages.organizer_contacts')}}</h3>
                    <!-- <h5>Lorem ipsum dolor sit amet, conse adipisicing elit. Libero incidunt quod ab mollitia quia dolorum conse.</h5> -->
                    <img src="{{asset ('css/image/login.png')}}" width="20">&nbsp;&nbsp;&nbsp;<span>@if($event->price==0) {{trans('messages.event_free')}} @else {{ trans('messages.event_entry')}} {{ $event->price}}тг @endif</span><br><br>

                    <img src="{{asset ('css/image/clock.png')}}" width="20">&nbsp;&nbsp;&nbsp;<span>@if(!empty($event->date_end)) {{ $event->date_start }} — {{$event->date_end }} @else {{$event->date_start}} @endif</span><br><br>
                    
                    <img src="{{asset ('css/image/location.png')}}" width="20">&nbsp;&nbsp;&nbsp;<span>{{$event->address}}</span><br>
                    <h6 style="padding-top:10px;">{{$event->organizer_number}}</h6>
                    
                    <p><a href="#">{{$event->organizer_email}}</a></p>
                    <p>{{$event->organizer_name}}</p>
                    <div class="social-icon">
                        <!-- <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-tumblr" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a> -->
                    </div>

                    <div class="user-blogers">
                    @for( $i=0; $i<4 && $i<count($event->blogers); $i++)
                        <img src="@if($event->blogers[$i]->image) /user_photo/{{ $event->blogers[$i]->image }} @else /user_photo/user.png @endif" title="{{ $event->blogers[$i]->fio }}" width="20">
                    @endfor        
                    @if( count($event->blogers)> 4)
                        <span>+ {{ count($event->blogers)-4 }} {{trans('messages.bloggers')}}</span>
                    @endif
                    </div>
                    <br>

                    @if(!Auth::check())
                        <a data-toggle="modal" data-target="#login"><button>{{trans('messages.i_go')}}</button></a>
                    @else
                        @if($event->blogers->contains(Auth::user()->user_id))
                            <button onclick="willGo({{$event->event_id}}, this)" style="background-image: initial;">{{trans('messages.i_go')}}</button>
                        @else
                            <button onclick="willGo({{$event->event_id}}, this)">{{trans('messages.i_go')}}</button>
                        @endif 
                    @endif
                </div>
                <div class="col-xs-12 col-md-6 open-event-contact-map" style="margin-bottom:50px;">
                    <div id="map" style="width: 100%; height: 730px;"></div>
                </div>
                @if(count($event->media)>1)
                <div class>
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                        @for($i=1; $i< count($event->media); $i++)
                            <div class="swiper-slide"><img src="{{asset ('event_photo/'. $event->media[$i]->link) }}"></div>
                        @endfor
                        </div>
                    </div>
                </div>
                <img class="swiper-button-next arrow-slider-right" src="{{asset ('css/images/right-arrow.svg')}}" width="100">
                <img class="swiper-button-prev arrow-slider-left" src="{{asset ('css/images/right-arrow.svg')}}" style="transform: rotate(180deg)" width="100">
                @endif
            </div>
        </div>
    </div>
    
<style>
    .social-icon a{
        padding: 6px 0px;
    }
    .social-icon i{
        line-height: 0;
        font-size: 20px;
    }
    .social-icon .jssocials-share{
        margin: 0.3em 1.6em 0.3em 0;
    }
    .clicked{
        background-image: initial;
    } 
    .banner .bg-heading{
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background-image: linear-gradient(to bottom, transparent, rgba(0,0,0,0.7));
    } 
    .banner-title{
        max-width: 50%;
        margin: 0 auto;
    } 
    @media screen and (max-width: 767px){
        .bg-heading .banner-title {
            max-width: 90%;
        }
    } .banner-title > h1{
        font-size: 36px;
        font-weight: 900;
    }
</style>
<script src="https://maps.api.2gis.ru/2.0/loader.js"></script>
<script type="text/javascript">
    var map;
    var center = [43.238949, 76.889709];
    var zoom = 11;
    @if($event->lat && $event->lng)
        center = [{{$event->lat}},{{$event->lng}}];
        zoom = 15;
    @endif
    DG.then(function () {
        map = DG.map('map', {
            center: center,
            zoom: zoom
        });

        if(zoom>11){
            DG.marker(center).addTo(map).bindPopup('Вы кликнули по мне!');
        }
    });
</script>
<script>
    var swiper = new Swiper('.swiper-container', {
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        slidesPerView: 1,
        centeredSlides: true,
        spaceBetween: 20,
        loop: true,
    });
</script>
<script>
    function willGo(evId, obj){
        $.ajax({
        type: 'GET',
        url: "{{route('will_go')}}",
        data: {id: evId},
        success: function(data){
            // console.log(data);
            if(data.contains){
                $(obj).css('background-image', 'initial');    
            } else{
                $(obj).css('background-image', 'linear-gradient(to right, #510031, #68003d 53%, #6f0044)');
            }
        }
        });
    }
</script>
  
@endsection

