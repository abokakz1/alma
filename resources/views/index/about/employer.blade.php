@extends('index.layout')

@section('content')
@include('index.about.page-header')
<div class="about-us">
    <div class="container-fluid">
        <div class="row row-two">
            @include('index.about.sidebar-menu')
            <div class="col-sm-9 cool-md-9 about-us__main">
                <div id="employer-list">
                    @foreach($content as $c)
                        <div class="human">
                            <img src="@if($c->image)/blog_photo/{{$c->image}}@else /css/image/employer.jpg @endif" class="img img-responsive image">
                            <a class="href" style="cursor:pointer"><p class="small-text">{{$c->name}}</p></a>
                            <p class="xs-small">{{$c->position}}</p>
                        </div>
                    @endforeach
                </div>

                <div id="employer-self" style="display: none;">
                <div class="swiper-container swiper-container-two">
                    <div class="swiper-wrapper">
                        @foreach($content as $c)
                        <div class="swiper-slide">
                            <div class="col-md-12 human only">
                                <img src="/blog_photo/{{$c->image}}" class="img img-responsive image">
                                <p class="small-text small-text__two">{{$c->name}}</p>
                                <p class="small-text__two">{{$c->position}}</p>
                                <div class="sociall">
                                    <ul>
                                        @if($c->fb)
                                        <li><a href="{{$c->fb}}" class="face"><i class="fa fa-facebook about-icon"></i></a></li>
                                        @endif
                                        @if($c->vk)
                                        <li><a href="{{$c->vk}}" class="vkon"><i class="fa fa-vk about-icon"></i></a></li>
                                        @endif
                                        @if($c->youtube)
                                        <li><a href="{{$c->youtube}}" class="yout"><i class="fa fa-youtube about-icon"></i></a></li>
                                        @endif
                                        @if($c->insta)
                                        <li><a href="{{$c->insta}}" class="insta"><i class="fa fa-instagram about-icon"></i></a></li>
                                        @endif
                                        @if($c->telegram)
                                        <li><a href="{{$c->telegram}}" class="tele"><i class=" fa fa-telegram about-icon"></i></a></li>
                                        @endif
                                    </ul>
                                </div>
                                @if($c->description)
                                <p class="small-text__two hello justify">Приветствие:<br>{{$c->description}}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div style="display:flex">
                    <div class="flex-item" style="margin-left: auto; order: 2;">
                        <span class="vpered">Вперед</span>
                        <img src="/css/image/next.png" class="swiper-button-next next">
                    </div>
                    <div class="flex-item">
                        <img src="/css/image/back.png" class="swiper-button-prev back">
                        <span class="nazad">Назад</span>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var swiper = new Swiper('.swiper-container', {
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev',
    slidesPerView: 1,
    centeredSlides: true,
    spaceBetween: 20,
    loop: true,
    breakpoints: {
        320: {
          slidesPerView: 1
        },
        375: {
          slidesPerView: 1
        },
        425: {
          slidesPerView: 1
        },
        768: {
          slidesPerView: 1
        }
    }
});
</script>
<script>
$(".href").click(function () {
    console.log( $(this).closest('div').index() );
    let i = $(this).closest('div').index()
    $('#employer-self').css('display', 'block');
    $('#employer-list').css('display', 'none');
    swiper.update(true);
    swiper.slideTo(i+1);
});
</script>
@endsection
