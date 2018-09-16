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
                    <form id="myform" method="post" enctype="multipart/form-data" action="/admin/category-edit/{{$row->category_id}}" style="margin-bottom: 0px;">
                        <div style="float: left; width: 900px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="category_id" value="{{$row->category_id}}">

                            <div class="left-block">
                                <p>Наименование (Каз)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="category_name_kz" value="{{$row->category_name_kz}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Наименование (Рус)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="category_name_ru" value="{{$row->category_name_ru}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Наименование (Анг)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="category_name_en" value="{{$row->category_name_en}}">
                            </div>
                            <div class="clearfloat"></div>
                        </div>

                        <div style="float: left; margin: -6px 0px 0px -313px; text-align: center;">
                            <div style="text-align: center;">
                                @if(strlen($row->image) > 0)
                                    <img src="/category_photo/{{$row->image}}" width="150px" height="150px" id="category_image">
                                @else
                                    <img src="/css/image/no_photo.png" width="150px" height="150px" id="category_image">
                                @endif
                            </div>
                            <div style="text-align: center; margin: 10px 0px 0px 20px;">
                                <input id="fileupload" type="file" name="image">
                                <br>
                                <p style="font-size: 14px; color: red;">Рекомендуемый размер картинки: 475x80</p>
                            </div>
                        </div>

                        @if($row->category_id > 0)
                            <div style="margin-top: 200px; float: right;">
                                <input type="submit" class="btn btn-primary" value="Сохранить">
                            </div>
                        @else
                            <div style="margin-top: 200px; float: right;">
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
