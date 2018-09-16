@extends('admin.layout')

@section('content')
    <style>
        select,input{
            margin-bottom: 0 !important;
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
                    <p style="color: red; font-size: 14px; text-align: center;">
                    @if(! empty($errors->all()))
                        @foreach ($errors->all() as $message)
                            {{$message}} <br>
                        @endforeach
                    @endif
                    </p>
                    <form id="myform" enctype="multipart/form-data" method="post" action="/admin/ads-edit/{{$row->id}}">
                        <div style="float: left; width: 900px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{$row->id}}">

                            <div class="left-block">
                                <p>Имя</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="name" value="{{$row->name}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Язык</p>
                            </div>
                            <div class="right-block">
                                <input type="radio" name="locale" value="ru" @if($row->locale == "ru")checked @endif style="width: 30px"> Русский
                                <input type="radio" name="locale" value="kz" @if($row->locale == "kz")checked @endif style="width: 30px;margin-left: 30px;"> Казахский
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Документ<br>(размер максимум 2мб)</p>
                            </div>
                            <div class="right-block">
                                <input type="file" name="file" accept=".doc,.docx,application/pdf,application/msword">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Тип</p>
                            </div>
                            <div class="right-block tag-select-block" style="width: 535px;">
                            <div class="tag-id-select" style="margin-bottom: 5px;">
                                <select name="type" style="float: left; width: 178px">
                                    <option value="pricelist" @if($row->type == "pricelist")selected @endif>Прайслист</option>
                                    <option value="presentation" @if($row->type == "presentation")selected @endif>Презентация</option>
                                </select>
                                <div class="clearfloat"></div>
                            </div>
                            </div>
                            <div class="clearfloat"></div>

                            <div style="margin-top: 10px; float: right;">
                                <input type="submit" class="btn btn-primary" value="Сохранить">
                            </div>
                            <div class="clearfloat"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
