@extends('index.layout')

@section('content')
    <div class="page-header">
        <div class="back-link-block">
            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
        </div>
        <div class="page-title-block">
            <h1 class="page-title">{{$row->menu_title}}</h1>
        </div>
    </div>
    <div class="content-block">
        <?php echo $row->menu_text; ?>
    </div>
@endsection