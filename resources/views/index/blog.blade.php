@extends('index.layout')

@section('content')
@include('index.header_second')

@if($blog)
    <div class="full-container">
        <div class="bg-banner" @if(!$blog->video && !$blog->image) style="background-image: url('/css/images/blog_default.jpg')" @elseif(!$blog->video)  style="background-image: url({{ $blog->image }})" @endif>
            @if($blog->video)
                <iframe width="100%" height="100%" src="https://youtube.com/embed/{{ $blog->video }}" frameborder="0" allowfullscreen>
                </iframe>
                <!-- <div class="bg-heading tube">
                    
                </div> -->
            @else
                <div class="bg-heading">
                    <!-- <h1 class="title">
                        {{ $blog->title }}
                    </h1> -->
                    <div class="blog-title_div">
                        <h1 class="title" style="max-width: initial;">{{$blog->title}}</h1>
                        <p>
                            <img src="/css/image/calendar.png" style="height: 17px;">
                            <span style="color: white;margin: 0 14px;">{{$blog->date}}</span>
                            <i class="icon icon-eye-white mr-5"></i>
                            <span style="color: white;margin: 10px;">{{$blog->view_count}}</span>
                        </p> 
                    </div>
                    {{--<p class="subtitle">--}}
                    {{--Adding value to companies through strategic,operational, and financial guidance.--}}
                    {{--</p>--}}
                </div>
            @endif

        </div>
    </div>
    <div class="container mp-container">
        <div class="row">
            <div class="col-md-9 col-xs-12 tinymce-container">
                @if($blog->video)
                <div class="blog-title_div" style="max-width: initial;">
                    <h1 class="title" style="max-width: initial;">{{$blog->title}}</h1>
<!--                     <p>
                        <img src="/css/image/calendar_2.png" style="height: 17px;min-width: 17px">
                        <span style="margin: 0 14px;">{{$blog->date}}</span>
                        <img src="/css/image/visits.png" style="height: 17px;min-width: 17px">
                        <span style="margin: 10px;">{{$blog->view_count}}</span>
                    </p>  -->
                </div>
                @endif
    
                {!! $blog->text !!}
                
                <p>
                    <img src="/css/image/calendar_2.png" style="height: 17px; min-width: initial;">
                    <span style="margin: 0 14px;">{{$blog->date}}</span>
                    <img src="/css/image/visits.png" style="height: 17px; min-width: initial;">
                    <span style="margin: 10px;">{{$blog->view_count}}</span>
                </p> 
                <div class="blog-share">
                    {{ trans('messages.share_post') }}

                    <div id="share"></div>
                </div>
                <div class="blogger">
                	<div><a href="{{ route('blogger', [$blog->author->username ? $blog->author->username : $blog->author->email]) }}">
                        @if($blog->author->image)
    	                	<img src="/user_photo/{{ $blog->author->image }}" alt="" class="img img-responsive">
    	                @else
    	                    <img src="/user_photo/user.png" alt="" class="img img-responsive">
    	                @endif
                    </a></div>
                    <p class="name" style="color: #69003e"><a <a class="media-body" href="{{ route('blogger', [$blog->author->username ? $blog->author->username : $blog->author->email]) }}">{{ $blog->author->fio }}</a></p>
                </div>
            </div>
            <div class="col-md-3 col-xs-12">
                <div class="row banner-av" style="padding: 0px 0;">
                    @if($ads1)
                        <div class="col-xs-12">
                            <div class="card media-block">
                                <a href="{{ $ads1->link }}" target="_blank">
                                    <div class="media-block-img" style="background-image: url('/adv/{{$ads1->image}}')"></div>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if($ads2)
                        <div class="col-xs-12">
                            <div class="card media-block">
                                <a href="{{ $ads2->link }}" target="_blank">
                                    <div class="media-block-img" style="background-image: url('/adv/{{$ads2->image}}')"></div>
                                </a>
                            </div>
                        </div>
                    @endif
                   
                </div>
            </div>
        </div>
    </div>
@else
    <div class="full-container">
        <div class="bg-banner" style="background-image: url('/css/images/blog_default.jpg')">
            <div class="bg-heading">
                <h1 class="title">
                    {{ trans('messages.no_blog') }}
                </h1>
                {{--<p class="subtitle">--}}
                {{--Adding value to companies through strategic,operational, and financial guidance.--}}
                {{--</p>--}}
            </div>
        </div>
    </div>
@endif


    @if($recommended_blogs && $recommended_blogs->count())
        <div class="container">
            <div class="swiper-heading">
                <h4 class="swiper-title-hidden">{{ trans_choice('messages.blog.featured', $recommended_blogs->count()) }}</h4>
                <h4 class="swiper-title">{{ trans_choice('messages.blog.featured', $recommended_blogs->count()) }}</h4>
            </div>
            <div class="row">
                @foreach($recommended_blogs as $r_blog)
                    <div class="col-md-3">
                        @include('index.blog-card', ['blog'=>$r_blog])
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@endsection