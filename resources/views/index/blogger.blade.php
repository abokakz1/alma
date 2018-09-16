@extends('index.layout')

@section('content')
    @include('index.header_second')

    <div class="container mp-container">
        <div class="blogger blogger-big">
            @if($blogger->image)
                <img src="/user_photo/{{ $blogger->image }}" alt="" class="img img-responsive">
            @endif
            <h2 class="name">{{ $blogger->fio }}</h2>
            @if($blogger->quote)
            <blockquote>
                <p>{{ $blogger->quote }}</p>
            </blockquote>
            @endif
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-xs-12 sw">
                <div class="nav-heading">
                    <ul class="nav nav-pills main-pills">
                        <li class="active"><a data-toggle="tab" href="#blog">{{ trans('messages.posts') }}</a></li>
                        {{--<li><a href="#">Профиль</a></li>--}}

                    </ul>

                    <div class="swiper-controls">
                        <ul class="nav nav-pills">
                            <li><a data-toggle="tab" href="#subscribers">{{ trans_choice('messages.subscriptions', $blogger->subscriptions->count()) }}</a></li>
                            @if(Auth::check())
                                @if(Auth::user()->user_id != $blogger->user_id)
                                    <li><a data-subscribe="{{ $blogger->user_id }}" data-lang="{{ App::getLocale() }}" class="btn-subscription @if(!$blogger->subscribed()) follow @endif">
                                        @if($blogger->subscribed())
                                            {{ trans('messages.unfollow') }}
                                        @else
                                            {{ trans('messages.follow') }}
                                        @endif
                                    </a></li>
                                @endif
                            @else
                                <li><a data-toggle="modal" data-target="#login" class="btn-subscription follow">{{ trans('messages.follow') }}</a></li>
                            @endif
                            <!-- <li>
                                <form class="navbar-form" id="following-form">
                                    <input type="hidden" class="form-control" name='user' id="user" value="{{ $blogger->email }}">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name='search' id="searchUser" placeholder="{{ trans('messages.blog.search') }}">
                                    </div>
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </form>
                            </li> -->
                        </ul>
                    </div>
                </div>
                <div class="sw-block">
                    <div class="row">
                        <div class="tab-content">
                            <div id="blog" class="tab-pane fade in active">
                                @foreach($blogger->blogs as $blog)
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        @include('index.blog-card', ['blog'=>$blog])
                                    </div>
                                @endforeach
                            </div>
                            <div id="subscribers" class="tab-pane fade">
                                @foreach($blogger->subscriptions as $subscription)
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        @include('index.blogger-card', ['blogger'=>$subscription])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('index.subscribe')
@endsection