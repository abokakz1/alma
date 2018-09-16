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
                    <form id="myform" enctype="multipart/form-data" method="post" action="/admin/document-edit/{{$row->id}}">
                        <div style="float: left; width: 900px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{$row->id}}">

                            <div class="left-block">
                                <p>Имя(Каз)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="name_kz" value="{{$row->name_kz}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Имя(Рус)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="name_ru" value="{{$row->name_ru}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Документ<br>(размер максимум 2мб)</p>
                            </div>
                            <div class="right-block">
                                <input type="file" name="document" accept=".doc,.docx,application/pdf,application/msword">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Меню</p>
                            </div>
                            <div class="right-block tag-select-block" style="width: 535px;">
                            <div class="tag-id-select" style="margin-bottom: 5px;">
                                <select name="menu_item_id" style="float: left; width: 178px">
                                    <option value="0">Выберите категорию</option>
                                    @if(count($items) > 0)
                                        @foreach($items as $key => $item)
                                            <option value="{{$item->id}}" @if($item->id == $row->menu_item_id) selected @endif>{{$item->name_ru}}</option>
                                        @endforeach
                                    @endif
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
