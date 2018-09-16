@extends('index.layout')

@section('content')
@include('index.about.page-header')
<div class="about-us">
    <div class="container-fluid">
        <div class="row row-two">
            @include('index.about.sidebar-menu')
            <div class="col-sm-9 cool-md-9 about-us__main">
                @if($content)
                <p>
                	{!! $content->text !!}
            	</p>
            	@endif
            </div>
        </div>
    </div>
</div>
@endsection