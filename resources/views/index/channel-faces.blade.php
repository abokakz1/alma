@extends('index.layout')

@section('content')
<div class="page-header">
    <div class="back-link-block">
        <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
    </div>
    <div class="page-title-block">
        <h1 class="page-title">Лица канала</h1>
    </div>  
</div>  
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
                                <li class="list-group-item active"><a href="/channel-faces">Руководство</a></li>
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
            <div class="col-sm-9 cool-md-9 about-us__main">
                <a href="/management" class="href"><div class="col-xs-12 col-sm-6 col-md-4 human">
                    <img src="/css/image/iron-man.jpg" class="img img-responsive image">
                    <p class="small-text">Тони Старк</p>
                    <p class="xs-small">Генеральный директор</p>
                </div></a>
                <a href="/management" class="href"><div class="col-xs-12 col-sm-6 col-md-4 human">
                    <img src="/css/image/CaptainAmerica-1.jpg" class="img img-responsive image">
                    <p class="small-text">Стив Роджерс</p>
                    <p class="xs-small">Генеральный директор</p>
                </div></a>
                <a href="/management" class="href"><div class="col-xs-12 col-sm-6 col-md-4 human">
                    <img src="/css/image/batman.jpg" class="img img-responsive image">
                    <p class="small-text">Брюс Уэйн</p>
                    <p class="xs-small">Генеральный директор</p>
                </div></a>
                <a href="/management" class="href"><div class="col-xs-12 col-sm-6 col-md-4 human">
                    <img src="/css/image/iron-man.jpg" class="img img-responsive image">
                    <p class="small-text">Тони Старк</p>
                    <p class="xs-small">Генеральный директор</p>
                </div></a>
                <a href="/management" class="href"><div class="col-xs-12 col-sm-6 col-md-4 human">
                    <img src="/css/image/CaptainAmerica-1.jpg" class="img img-responsive image">
                    <p class="small-text">Стив Роджерс</p>
                    <p class="xs-small">Генеральный директор</p>
                </div></a>
                <a href="/management" class="href"><div class="col-xs-12 col-sm-6 col-md-4 human">
                    <img src="/css/image/batman.jpg" class="img img-responsive image">
                    <p class="small-text">Брюс Уэйн</p>
                    <p class="xs-small">Генеральный директор</p>
                </div></a>
            </div>
        </div>
    </div>
</div>



@endsection
