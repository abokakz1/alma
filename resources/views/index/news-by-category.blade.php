@extends('index.layout')

@section('content')
    <? use App\Models\View; ?>

    <div class="content aboutContent">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">
                    <div class="row black_rows">
                        <div class="col-md-3 col-sm-3 col-xs-2">
                            <a style="color: inherit; text-decoration: none;" href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><img src="/css/images/arrow_left.png" alt="" align="middle" class="arrow_left1">
                                <h4 class="arrow_left hidden-sm hidden-xs"><?php echo trans("messages.Назад <br>  на главную страницу"); ?></h4>
                            </a>
                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-9 TopTitle">
                            <h4 class="about_title"><b>{{$news_category['news_category_name_' . App::getLocale()]}}:</b></h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-offset-1 col-md-10 page_list_news">

                    @if(count($row) > 0)
                        @foreach($row as $key => $news_item)
                            @if(strlen(trim($news_item['news_title_' . App::getLocale()])) > 0)
                                <div class="col-md-6 col-sm-6 AdvertisersContent">
                                    <div class="col-md-3 col-sm-3 col-xs-4 lists_news">
                                        <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $news_item->news_url_name) }}">
                                            @if(strlen($news_item->image) > 0)
                                                <img src="/news_photo/{{$news_item->image}}" class="img-responsive img_lists" alt="">
                                            @else
                                                <img src="/css/images/no_news_img<?=rand(1,3)?>.png" class="img-responsive img_lists" alt="">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="col-md-9 col-sm-9 col-xs-8 lists_news">
                                        <p class="blockNewsText">
                                            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $news_item->news_url_name) }}">
                                                {{ $news_item['news_title_' . App::getLocale()] }}
                                            </a>
                                        </p>

                                        <div class="date_News">
                                            <p><img src="/css/images/Calendar-.png" alt=""> &nbsp; &nbsp;{{$news_item->date}}</p>
                                        </div>

                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection