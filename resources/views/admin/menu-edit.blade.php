@extends('admin.layout')

@section('content')
    <style>
        select,input{
            margin-bottom: 0px !important;
        }
        .right-block{
            width: auto;
            text-align: center;
        }
    </style>
    <div class="row-fluid">
        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                    <a data-toggle="tab" href="#main_data">
                        <i class="green icon-home bigger-110"></i>
                        Основные данные
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane in active" id="main_data">
                    @if(isset($result['status']))
                        <p style="color: red; font-size: 14px; text-align: center;">
                            @if(count($result['value']) > 0)
                                @foreach($result['value'] as $key => $error_item)
                                    {{ $error_item }} <br>
                                @endforeach
                            @endif
                        </p>
                    @endif
                    <form id="myform" method="post" enctype="multipart/form-data" action="/admin/menu-edit/{{$row->menu_id}}" style="margin-bottom: 0px;">
                        <div style="float: left; width: 900px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="menu_id" value="{{$row->menu_id}}">

                            <div class="left-block">
                                <p>Название</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="menu_title" value="{{$row->menu_title}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Порядковый номер</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="order_num" value="{{$row->order_num}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Текст</p>
                            </div>
                            <div class="right-block">
                                <textarea id="editor1" name="menu_text">{{$row->menu_text}}</textarea>
                            </div>
                            <div class="clearfloat"></div>
                        </div>
                        <div class="clearfloat"></div>

                        @if($row->menu_id > 0)
                            <div style="float: right;">
                                <input type="submit" class="btn btn-primary" value="Сохранить">
                            </div>
                        @else
                            <div style="float: right;">
                                <input type="submit" class="btn btn-primary" value="Добавить">
                            </div>
                        @endif

                        <div class="clearfloat"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
