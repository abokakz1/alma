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
                    <form id="myform" method="post" action="/admin/vacancy-edit/{{$row->vacancy_id}}" style="margin-bottom: 0px;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="vacancy_id" value="{{$row->vacancy_id}}">

                        <div class="left-block">
                            <p>Должность (Каз)</p>
                        </div>
                        <div class="right-block">
                            <input type="text" name="vacancy_position_name_kz" value="{{$row->vacancy_position_name_kz}}">
                        </div>
                        <div class="clearfloat"></div>

                        <div class="left-block">
                            <p>Должность (Рус)</p>
                        </div>
                        <div class="right-block">
                            <input type="text" name="vacancy_position_name_ru" value="{{$row->vacancy_position_name_ru}}">
                        </div>
                        <div class="clearfloat"></div>

                        <div class="left-block">
                            <p>Должность (Анг)</p>
                        </div>
                        <div class="right-block">
                            <input type="text" name="vacancy_position_name_en" value="{{$row->vacancy_position_name_en}}">
                        </div>
                        <div class="clearfloat"></div>

                        <div class="left-block">
                            <p>Описание (Каз)</p>
                        </div>
                        <div class="right-block">
                            <textarea id="editor1" name="vacancy_description_kz">{{$row->vacancy_description_kz}}</textarea>
                        </div>
                        <div class="clearfloat"></div>

                        <div class="left-block">
                            <p>Описание (Рус)</p>
                        </div>
                        <div class="right-block">
                            <textarea id="editor2" name="vacancy_description_ru">{{$row->vacancy_description_ru}}</textarea>
                        </div>
                        <div class="clearfloat"></div>

                        <div class="left-block">
                            <p>Описание (Анг)</p>
                        </div>
                        <div class="right-block">
                            <textarea id="editor3" name="vacancy_description_en">{{$row->vacancy_description_en}}</textarea>
                        </div>
                        <div class="clearfloat"></div>

                        @if($row->vacancy_id > 0)
                            <div style="margin-top: 0px; float: right;">
                                <input type="submit" class="btn btn-primary" value="Сохранить">
                            </div>
                        @else
                            <div style="margin-top: 0px; float: right;">
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
