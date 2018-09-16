@extends('index.layout')

@section('content')
<div class="page-header">
    <div class="back-link-block">
        <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
    </div>
    <div class="page-title-block">
        <h1 class="page-title">Аудированная годовая финансовая отчетность</h1>
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
                                <li class="list-group-item"><a href="/management">Руководство</a></li>
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
                                <li class="list-group-item active"><a href="/audio-god-finance">Аудированная годовая финансовая отчетность</a></li>     
                            </ul>
                        </div>
                </ul>
            </div>
            <div class="col-sm-9 cool-md-9 about-us__main">
                <li class="down-file"><a href="/css/image/latex.pdf" download><i class="fa fa-file-pdf-o pdf"></i><span>Устав АО "Телерадиокомпания Южная столица (прикреплен - на странице значок  УСТАВ)"</span><span class="accordion-toggle-two collapsed" data-toggle="collapse" href="#collapse4"></span></a></li>
                <div id="collapse4" class="panel-collapse collapse">
                    <object data="/css/image/latex.pdf#page=1" type="application/pdf" width="50%" height="50%">
                        <iframe src="/css/image/latex.pdf#page=1" width="50%" height="50%" style="border: none;">
                        This browser does not support PDFs. Please download the PDF to view it: <a href="/css/image/latex.pdf">Download PDF</a>
                        </iframe>
                    </object>

                </div>
            </div>
        </div>
    </div>
</div>



@endsection
