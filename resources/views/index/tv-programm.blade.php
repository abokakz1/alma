@extends('index.layout')

@section('content')
    <br>
    @if(count($row) > 0)
        @foreach($row as $key => $tv_programm_item)
            {{ $tv_programm_item['time'] }} {{ $tv_programm_item['tv_programm_name_ru'] }} <br>
            <br>
        @endforeach
    @endif
@endsection