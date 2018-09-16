@extends('index.layout')

@section('content')
<div class="page-header">
    <div class="back-link-block">
        <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
    </div>
    <div class="page-title-block">
        <h1 class="page-title">История</h1>
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
                                <li class="list-group-item active"><a href="/history-two">История</a></li>
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
            <div class="col-sm-9 cool-md-9 about-us__main">
                <p class="medium-text">1999 год - начало истории</p>
                <p class="small-text">(Текст раскрывающийся - краткая версия описания сразу на странице. Нажимаешь Подробнее - и читаешь длинное описание)</p>
                <hr class="line">
                <p class="medium-text">Аудитория телеканала</p>
                <p class="small-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
        </div>
    </div>
</div>



@endsection
