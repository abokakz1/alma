@extends('index.layout')

@section('content')
<div class="main404">
	<div class="error404">
		<h1>{{trans("messages.404")}}</h1>
		<p>{{trans("messages.not_found")}}</p>
	</div>
	<hr>
	<div class="maybe_interesting">
		{{trans("messages.recommendation")}}
	</div>

<? use App\Models\View; ?>

<div class="col-md-12 col-sm-6">
    <div class="row">
    <?php $i = 0; $bb = 0; ?>
    @if(count($kz_news_row) > 0)
        @foreach($kz_news_row as $key => $main_news_item)
            @if($i < 4)
                @if(strlen($main_news_item['news_title_' . App::getLocale()]) > 0)
                    <?php $i++; $bb = $i; ?>
                    <?php $view_row = View::where("news_id","=",$main_news_item['news_id'])->get(); ?>
                    @if(strlen($main_news_item->image) > 0 && $i < 5)
                        <div class="col-md-3 col-sm-6">
                            <div class="card card-md card-fixed">
                                <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $main_news_item->news_url_name) }}" style="text-decoration: none;">
                                <div class="card-img" style="background-image: url('/news_photo/{{$main_news_item->image}}')">
                                        <!-- <div class="card-img-text">
                                            <div class="dv dv-inverse">
                                                <span class="date mr-20">{{$main_news_item['date']}}</span>
                                                <span class="views mr-20">
                                                    <i class="icon icon-eye-white mr-5"></i>{{ count($view_row) }}
                                                    @if($main_news_item['is_has_foto'] > 0)
                                                        <i class="icon icon-photos-white"></i>
                                                    @endif

                                                    @if($main_news_item['is_has_video'] > 0)
                                                        <i class="icon icon-videos-white"></i>
                                                    @endif
                                                </span>
                                            </div>
                                            <ul class="article-meta">
                                                <li>
                                                    @if($main_news_item['news_category_id'] > 0)
                                                        <a class="label label-red mr-10" onclick="searchByNewsCategory({{$main_news_item['news_category_id']}})">{{$main_news_item['news_category_name_' . App::getLocale()]}}</a>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div> -->
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
                                            {{ count($view_row) }}

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
                                            {{ count($view_row) }}

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


<style>
	.main404{
		width: 900px;
		height: 300px;
		max-width: 100%;
		min-width: 320px;
		margin: 0 auto;
	}
	.main404 hr{
		height: 2px;
		border: 0;
		margin: 0;
		width: 50%;
		margin: 0 auto;
		background-color: rgba(79,0,48,1); 
	}
	.error404{
		padding: 40px 0; 
		/*background-color: pink;*/
		text-align: center;
		font-family: Helvetica, sans-serif;
	}
	.error404 h1{
		color:rgba(79,0,48,1);
		font-weight: bold;
		font-size: 5em;
		margin-bottom: -10px;

	}
	.error404 p{
		color: #a8acac;
		font-weight: bold;
		font-size: 2.5em;
		padding-top: 0px;
	}
	.maybe_interesting{
		text-align: center;
		padding: 10px 0;
		color:rgba(79,0,48,1);
/*		background-color: yellow;*/
		font-size: 1.2em;
				font-family: Helvetica, sans-serif;
	}

    .wrapper {
        overflow-y: scroll;
    }
</style>
@endsection