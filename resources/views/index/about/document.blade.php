@extends('index.layout')

@section('content')
@include('index.about.page-header')  
<div class="about-us">
    <div class="container-fluid">
        <div class="row row-two">
            @include('index.about.sidebar-menu')
            <div class="col-sm-9 cool-md-9 about-us__main">
                @foreach($content as $c)
                <li class="down-file">
                    <a href="/response_files/{{$c->document}}" download>
                        <i @if($c->type == 'pdf') class="fa fa-file-pdf-o pdf" @else class="fa fa-file-word-o word" @endif></i>
                        <span>{{$c->name}}</span>
                    </a>
                </li>
                @endforeach
            </div>
        </div>
    </div>
</div>



@endsection
