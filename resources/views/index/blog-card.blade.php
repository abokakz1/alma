<div class="card card-blog">
    @if($blog->image)
        <img class="card-img-top" src="{{ asset($blog->images['cropped']) }}">
    @else
        <img class="card-img-top" src="/css/images/blog_default.jpg">
    @endif
    <a class="card-block" href="{{ url($blog->url) }}">
        <h4 class="card-title">{{ $blog->title }}</h4>
    </a>
    <div class="card-footer">
        <div class="media">
            <div class="media-left media-middle">
                <div style="width: 60px"></div>
                <a href="{{ route('blogger', [$blog->author->username ? $blog->author->username : $blog->author->email]) }}">
                @if($blog->author->image)
                    <img src="/user_photo/{{ $blog->author->image }}" class="media-object">
                @else
                    <img src="/user_photo/user.png" class="media-object">
                @endif
                </a>
            </div>
            <a class="media-body" href="{{ route('blogger', [$blog->author->username ? $blog->author->username : $blog->author->email]) }}">
            <?php
            $name = $blog->author->fio; 
            if(strlen($name)>15){
            	$name = mb_substr($name, 0, 14) . '...';
            }
            ?>
                <h5 class="media-heading" title="{{$blog->author->fio}}">{{ $name }}</h5>
                <p class="date" style="margin-bottom: 0;">{{ $blog->date }}</p>
                <p style="margin-bottom: 0;color: #898989;"><i class="icon icon-eye-gray mr-5"></i>{{ $blog->view_count }}</p>
            </a>
        </div>
    </div>
    <a href="{{ url($blog->url) }}"><div class="after-card-blog"></div></a>
</div>