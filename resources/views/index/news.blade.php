@extends('index.layout')

@section('content')
    <div class="page-header">
        <div class="back-link-block">
            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
        </div>
        <div class="page-title-block">
            <h1 class="page-title text-blue">{{trans("messages.Новости")}}</h1>
        </div>
    </div>
    <div class="news v-tabs">
        <aside>
            <?php $news_sort_id1 = " active ";
            $news_sort_id2 = "";
            ?>
            @if($news_sort_id == 2)
                <?php   $news_sort_id1 = "";
                $news_sort_id2 = " active ";
                ?>
            @endif

            <ul class="v-tab-list blue">
                <li class="<?php echo $news_sort_id1;?>">
                    <a style="cursor: pointer;" onclick="sortNewsByParam(1)">{{trans("messages.САМЫЕ СВЕЖИЕ")}}</a>

                    <form id="myform" method="post" action="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/") }}" style="display: none;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" value="1" name="news_sort_id" class="news-sort-id-1">
                    </form>
                </li>
                <li class="<?php echo $news_sort_id2;?>">
                    <a style="cursor: pointer;" onclick="sortNewsByParam(2)">{{trans("messages.САМЫЕ ПОПУЛЯРНЫЕ")}}</a>

                    <form id="myform2" method="post" action="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/") }}" style="display: none;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" value="2" name="news_sort_id">
                    </form>
                </li>
                <li>
                    <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(),"/news-archive/") }}">
                        {{trans("messages.АРХИВ НОВОСТЕЙ")}}
                    </a>
                </li>
            </ul>

            <script>
                function sortNewsByParam(form_id){
                    if(form_id == 1){
                        document.getElementById("myform").submit();
                    }
                    else if(form_id == 2){
                        document.getElementById("myform2").submit();
                    }
                }

                function setNewsByTypeMobile(ob){
                    form_id = $(ob).val();

                    if(form_id == 1){
                        document.getElementById("myform").submit();
                    }
                    else if(form_id == 2){
                        document.getElementById("myform2").submit();
                    }
                    else if(form_id == 3){
                        window.location.href = '{{ LaravelLocalization::getLocalizedURL(App::getLocale(),"/news-archive/") }}';
                    }
                }
            </script>
        </aside>

        {{--Add code --}}

        <div class="mob-category hidden-lg hidden-md">
            <select onchange="setNewsByTypeMobile(this)" class="select-new-type">
                <option value="1">{{trans("messages.САМЫЕ СВЕЖИЕ")}}</option>
                <option value="2" @if($news_sort_id == 2) selected @endif>{{trans("messages.САМЫЕ ПОПУЛЯРНЫЕ")}}</option>
                <option value="3">{{trans("messages.АРХИВ НОВОСТЕЙ")}}</option>
            </select>
        </div>
        <style>
            .mob-category .select-new-type{
                background: #e6e6e6;
                height: 40px;
                border:0;
                outline:none;
                width: 100%;
                padding: 0 15px;
                -moz-appearance: none;
                -webkit-appearance: none;
                appearance: none;
                background-image: url(/css/images/ic_arrow_down@2x.png);
                background-position: center right 15px;
                background-repeat: no-repeat;
                color: #1667b1;
                background-size: 18px;

            }
            .select-new-type option:checked {
                color: #1667b1;
            }

        </style>
        {{--Add code --}}

        <article>
            <div class="row">
                @if(count($row) > 0)
                    <?php $max_count = 0; ?>
                    @foreach($row as $key => $news_item)
                        @if(strlen(trim($news_item['news_title_' . App::getLocale()])) > 0)
                            <?php $max_count++; ?>
                        @endif
                    @endforeach
                    <?php
                    $i = 0; $b = 0; ?>
                    @foreach($row as $key => $news_item)
                        @if(strlen(trim($news_item['news_title_' . App::getLocale()])) > 0)
                            <?php $i++; $b++; ?>

                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="news-item">
                                    <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $news_item->news_url_name) }}">
                                        @if(strlen($news_item->image) > 0)
                                            <div class="news-item-img op-darked" style="background-image: url('/news_photo/{{$news_item->image}}')">
                                        @else
                                            <div class="news-item-img op-darked" style="background-image: url('/css/images/no_news_img<?=rand(1,3)?>.png')">
                                        @endif
                                            <div class="img-item-content">
                                                <span class="news-meta news-views">
                                                    <i class="icon icon-eye-white"></i>
                                                    <span>{{$news_item->view_count}}</span>
                                                </span>
                                                <span class="news-meta news-date">
                                                    <i class="icon icon-calendar-white"></i>
                                                    <span>{{$news_item->date}}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="news-item-title">
                                        <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $news_item->news_url_name) }}">{{ $news_item['news_title_' . App::getLocale()] }}</a>
                                    </div>
                                </div>
                            </div>

                        @endif
                    @endforeach
                @endif
            </div>
            {{$row->appends(['news_sort_id' => $news_sort_id])->links() }}
        </article>
    </div>   
 @endsection