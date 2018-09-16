@extends('index.layout')

@section('content')
<!-- <div class="page-header">
    <div class="back-link-block">
        <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
    </div>
    <div class="page-title-block">
        <h1 class="page-title">Лица канала</h1>
    </div>  
</div>   -->  
<div class="about-us">
    <div class="container-fluid">
        <div class="row row-two">
            <div class="col-sm-3 cool-md-3 sidebar-two">
                <ul>
                    <li><a href="/about-us-test"><span class="accordion-toggle collapsed" data-toggle="collapse" href="#collapse1"></span><span class="menu-text">О нас</span></a></li>
                        <div id="collapse1" class="panel-collapse collapse">
                            <ul class="list-group">
                                <li class="list-group-item"><a href="/history-two">История</a></li>
                            </ul>
                        </div>
                    <li><a href="/channel-faces"><span class="accordion-toggle collapsed" data-toggle="collapse" href="#collapse2"></span><span class="menu-text" >Лица канала</span></a></li>
                        <div id="collapse2" class="panel-collapse collapse">
                            <ul class="list-group">
                                <li class="list-group-item"><a href="/channel-faces">Руководство</a></li>
                                <li class="list-group-item"><a href="#">Информационная служба</a></li>
                                <li class="list-group-item"><a href="#">Ведущие программ</a></li>
                                <li class="list-group-item"><a href="#">Интернет редакция</a></li>
                            </ul>
                        </div>
                    <li><a href="#"><span class="accordion-toggle collapsed" data-toggle="collapse" href="#collapse3"></span><span class="menu-text">Корпоративное управление</span></a></li>
                        <div id="collapse3" class="panel-collapse collapse">
                            <ul class="list-group">
                                <li class="list-group-item"><a href="/documents">Учредительные документы</a></li>
                                <li class="list-group-item"><a href="#">Единственный акционер</a></li>
                                <li class="list-group-item"><a href="#">Совет директоров</a></li>
                                <li class="list-group-item"><a href="#">Исполнительный орган</a></li>
                                <li class="list-group-item"><a href="/audio-god-finance">Аудированная годовая финансовая отчетность</a></li>     
                            </ul>
                        </div>
                </ul>
            </div>
            <div class="col-sm-9 cool-md-9 about-us__main top" style="position:relative;">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="col-md-12 human">
                                <img src="/css/image/iron-man.jpg" class="img img-responsive image">
                                <p class="small-text">Тони Старк</p>
                                <p>Генеральный директор</p>
                                <div class="sociall">
                                    <ul>
                                        <li><a href="#" class="face"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#" class="twit"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#" class="vkon"><i class="fa fa-vk"></i></a></li>
                                        <li><a href="#" class="yout"><i class="fa fa-youtube"></i></a></li>
                                        <li><a href="#" class="insta"><i class="fa fa-instagram"></i></a></li>
                                        <li><a href="#" class="tele"><i class=" fa fa-telegram"></i></a></li>
                                    </ul>
                                </div>
                                <p class="medium-text hello">Приветствие:<br>Я рада помочь вам в работе с сайтом канала, а также -<br>воспользоваться другими аккаунтами и страницами в соцсетях, где представлен канал</p>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="col-md-12 human">
                                <img src="/css/image/CaptainAmerica-1.jpg" class="img img-responsive image">
                                <p class="small-text">Стив Роджерс</p>
                                <p>Генеральный директор</p>
                                <div class="sociall">
                                    <ul>
                                        <li><a href="#" class="face"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#" class="twit"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#" class="vkon"><i class="fa fa-vk"></i></a></li>
                                        <li><a href="#" class="yout"><i class="fa fa-youtube"></i></a></li>
                                        <li><a href="#" class="insta"><i class="fa fa-instagram"></i></a></li>
                                        <li><a href="#" class="tele"><i class=" fa fa-telegram"></i></a></li>
                                    </ul>
                                </div>
                                <p class="medium-text hello">Приветствие:<br>Я рада помочь вам в работе с сайтом канала, а также -<br>воспользоваться другими аккаунтами и страницами в соцсетях, где представлен канал</p>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="/css/image/next.png" class="swiper-button-next next">
                <span class="vpered">Вперед</span>
                <img src="/css/image/back.png" class="swiper-button-prev back">
                <span class="nazad">Назад</span>
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

@endsection
                    