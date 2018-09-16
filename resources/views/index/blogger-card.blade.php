<div class="card hovercard">
    <div class="card-header"></div>
    <div class="avatar">
        @if($blogger->image)
            <img alt="" src="/user_photo/{{ $blogger->image }}">
        @endif
    </div>
    <div class="card-block">
        <a class="card-title" href="{{ route('blogger', [$blogger->username ? $blogger->username : $blogger->email]) }}">{{ $blogger->fio }}</a>
        <!-- <p class="mail">{{ $blogger->email }}</p> --><br>
        @if(Auth::check())
            @if(Auth::user()->user_id != $blogger->user_id)
                <button class="btn btn-subscription @if(!$blogger->subscribed()) follow @endif" data-subscribe="{{ $blogger->user_id }}" data-lang="{{ App::getLocale() }}">
                    @if($blogger->subscribed())
                        {{ trans('messages.unfollow') }}
                    @else
                        {{ trans('messages.follow') }}
                    @endif
                </button>
            @else
                <div class="btn-empty"></div>
            @endif
        @else
            <a data-toggle="modal" data-target="#login" class="btn btn-subscription follow">{{ trans('messages.follow') }}</a>
        @endif
    </div>
    <div class="card-footer">
        <ul class="list-unstyled list-inline">
            <li><a class="blog-card-link" href="{{ route('blogger', [$blogger->username ? $blogger->username : $blogger->email]) }}">
                <strong>{{ count($blogger->posts) }}</strong><br> {{ trans('messages.posts') }}
            </a></li>
            <li><strong>{{ count($blogger->subscribers) }}</strong><br> {{ trans('messages.subscribers') }}</li>
            <li><strong class="li-counter">{{ count($blogger->subscriptions) }}</strong><br> {{ trans('messages.subscriptions') }}</li>
        </ul>
    </div>
</div>