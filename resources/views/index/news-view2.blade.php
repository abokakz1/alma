@extends('index.layout')

@section('content')
    <style>
        .article-content iframe{
            width: 100% !important;
        }
    </style>
    <? use App\Models\View;
        use App\Models\News;
        $next_news_url_name = $row_next->news_url_name;
    ?>

    <div class="article-header">
        <div class="col-md-3 col-sm-4 col-np">
            <div class="back link-block">
                <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
            </div>
            <div class="article-text-block">
                <div class="article-publish-date">
                    <i class="icon icon-calendar-blue"></i><small>{{$row['date']}}</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-np">
            @if(strlen($row->image_big) > 0)
                <div class="article-header-img op-darked" style="background-image: url('/news_photo/{{$row->image_big}}'">
            @else
                <div class="article-header-img op-darked" style="background-image: url('/css/images/no_news_img<?=rand(1,3)?>.png'">
            @endif
                <div class="img-item-content">
                    <h1 class="article-title">
                        {{$row['news_title_' . App::getLocale()]}}
                    </h1>
                </div>
            </div>
        </div>

        @if(strlen($next_news_url_name) > 0)
            <div class="col-md-3 col-np hidden-sm hidden-xs">
                <div class="forward link-block">
                    <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $next_news_url_name)}}">
                        <i class="icon icon-arrow-right-g"></i>
                        <span><?php echo trans("messages.Вперед<br> к следующей новости"); ?></span>
                    </a>
                </div>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="article-content">
                <?php echo $row['news_text_' . App::getLocale()]; ?>

                <div class="article-content-bottom">
                    <div class="row">
                        <div class="article-date col-md-7">
                            <ul class="list-inline">
                                <li class="mr-20"><i class="icon icon-calendar-blue mr-5"></i><span>{{$row['date']}}</span></li>

                                <li><i class="icon icon-clock-blue mr-5"></i><span> {{substr($row['date2'],11,5)}}</span></li>

                                <? use App\Models\NewsTag;
                                $news_tag_list = NewsTag::LeftJoin("tag_tab","news_tag_tab.tag_id","=","tag_tab.tag_id")->select("news_tag_tab.*","tag_tab.tag_name")->where("news_id","=",$row['news_id'])->get();
                                ?>
                                <li class="link-teg">
                                    <span>
                                        <a @if(strlen($row['news_category_name_' . App::getLocale()]) < 1) style="display: none;"  @endif onclick="searchByNewsCategory({{$row['news_category_id']}})">{{$row['news_category_name_' . App::getLocale()]}}</a>
                                        @if(count($news_tag_list) > 0)
                                            @foreach($news_tag_list as $key => $news_tag_item)
                                                <a onclick="searchByTag('{{$news_tag_item["tag_name"]}}')">{{$news_tag_item['tag_name']}}</a>
                                            @endforeach
                                        @endif
                                    </span>
                                </li>
                                <li><i class="icon icon-blue-eye"></i><span>{{ $row['view_count'] }}</span></li>
                            </ul>
                        </div>

                        @if(strlen($next_news_url_name) > 0)
                            <div class="article-bottom-back-link col-md-5">
                                <a class="text-gray" href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $next_news_url_name)}}">
                                    {{trans("messages.Следующая новость")}}
                                    <i class="icon icon-arrow-right-gg ml-10"></i>
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="article-share-buttons" id="shareNative" style="opacity: 0;">
                    </div>
            </div>
        </div>
    </div>

<div class="col-md-10 col-md-offset-1">
    <hr style="width: 40%;margin-top: 5px;margin-bottom: 10px;border-top: 2px solid rgba(115,0,70,1);">
    <div class="maybe_interesting" style="text-align: center;margin-bottom: 10px;color: rgba(115,0,70,1)">
        {{trans("messages.recommendation")}}
    </div>


    <div class="col-md-12 col-sm-6">
        <div class="row">
    <?php $i = 0; $bb = 0; ?>
    @if(count($kz_news_row) > 0)
        @foreach($kz_news_row as $key => $main_news_item)
            @if($i < 4)
                @if(strlen($main_news_item['news_title_' . App::getLocale()]) > 0)
                    <?php $i++; $bb = $i; ?>
                    @if(strlen($main_news_item->image) > 0 && $i < 5)
                        <div class="col-md-3 col-sm-6">
                            <div class="card card-md card-fixed">
                                <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}" style="text-decoration: none;">
                                <div class="card-img" style="background-image: url('/news_photo/{{$main_news_item->image}}')">
                                </div>
                                </a>
                                <div class="card-body">
                                    <h3 class="card-title">
                                        <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">
                                            {{$main_news_item['news_title_' . App::getLocale()]}}
                                        </a>
                                    </h3>

                                    <div class="dv">
                                        <span class="date">{{$main_news_item['date']}}</span>
                                        <span class="views ml-20">
                                            <i class="icon icon-eye-gray mr-5"></i>
                                            {{ $main_news_item['view_count'] }}

                                            @if($main_news_item['is_has_foto'] > 0)
                                                <i class="icon icon-photo"></i>
                                            @endif

                                            @if($main_news_item['is_has_video'] > 0)
                                                <i class="icon icon-video"></i>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-3 col-sm-6">
                            <div class="card card-md card-fixed">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}">
                                            {{$main_news_item['news_title_' . App::getLocale()]}}
                                        </a>
                                    </h3>
                                    <ul class="article-meta">
                                        <li>
                                            @if($main_news_item['news_category_id'] > 0)
                                                <a class="label label-red" onclick="searchByNewsCategory({{$main_news_item['news_category_id']}})">{{$main_news_item['news_category_name_' . App::getLocale()]}}</a>
                                            @endif
                                        </li>
                                    </ul>
                                    <div class="dv">
                                        <span class="date">{{$main_news_item['date']}}</span>
                                        <span class="views ml-20">
                                            <i class="icon icon-eye-gray mr-5"></i>
                                            {{ $view_row }}

                                            @if($main_news_item['is_has_foto'] > 0)
                                                <i class="icon icon-photo"></i>
                                            @endif

                                            @if($main_news_item['is_has_video'] > 0)
                                                <i class="icon icon-video"></i>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                @endif
            @endif
        @endforeach
    @endif
    </div>
    </div>
</div>
	
	<form id="search_news_category" method="get" action="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/search") }}" style="display: none;">
		<input type="hidden" value="3" name="search_type">
		<input type="hidden" value="" name="search_news_category_id" id="search_news_category_id">
	</form>

	<script>
		function searchByNewsCategory(news_category_id){
			$("#search_news_category_id").val(news_category_id);
			document.getElementById("search_news_category").submit();
		}
	</script>


    <script>
        $(document).ready(function(){
           setNewsView();

//            $('.news-text-new').find("p").each(function() {
//                if($(this).find("strong").html() != null){
//                    $(this).addClass("list_paragraph");
//                }
//            });
        });

        function setNewsView(){
            $.ajax({
                type: 'POST',
                url: "{{route('set_news_view')}}",
                data: {_token: CSRF_TOKEN, news_id: <?php echo $row['news_id']?>},
                success: function(data){

                }
            });
        }
    </script>
@endsection