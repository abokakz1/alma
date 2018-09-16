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
                    <form id="myform" enctype="multipart/form-data" method="post" action="/admin/advertisement-edit/{{$row->advertisement_id}}">
                        <div style="float: left; width: 900px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="advertisement_id" value="{{$row->advertisement_id}}">

                            <div class="left-block" style="display: none;">
                                <p>Заголовок (Каз)</p>
                            </div>
                            <div class="right-block" style="display: none;">
                                <input type="text" name="advertisement_title_kz" value="{{$row->advertisement_title_kz}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Заголовок</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="advertisement_title_ru" value="{{$row->advertisement_title_ru}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block" style="display: none;">
                                <p>Заголовок (Анг)</p>
                            </div>
                            <div class="right-block" style="display: none;">
                                <input type="text" name="advertisement_title_en" value="{{$row->advertisement_title_en}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Ссылка</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="link" value="{{$row->link}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block" style="display: none;">
                                <p>Описание (Каз)</p>
                            </div>
                            <div class="right-block" style="display: none;">
                                <textarea name="advertisement_text_kz" style="width: 300px; height: 100px;">{{$row->advertisement_text_kz}}</textarea>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Описание</p>
                            </div>
                            <div class="right-block">
                                <textarea name="advertisement_text_ru" style="width: 300px; height: 100px;">{{$row->advertisement_text_ru}}</textarea>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block" style="display: none;">
                                <p>Описание (Анг)</p>
                            </div>
                            <div class="right-block" style="display: none;">
                                <textarea name="advertisement_text_en" style="width: 300px; height: 100px;">{{$row->advertisement_text_en}}</textarea>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Язык рекламы</p>
                            </div>
                            <div class="right-block">
                                <select name="lang_id">
                                    <option value="0">Выберите язык</option>
                                    <option value="1" @if($row->lang_id == 1) selected @endif>Казахский</option>
                                    <option value="2" @if($row->lang_id == 2) selected @endif>Русский</option>
                                </select>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Активный</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_active" value="{{$row->is_active}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->is_active == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_active}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Тип баннера</p>
                            </div>
                            <div class="right-block">
                                <select name="is_main_advertisement">
                                    <option value="0">Выберите тип баннера</option>
                                    <option value="4" @if($row->is_main_advertisement == 4) selected @endif>Главная ст</option>
                                    <option value="5" @if($row->is_main_advertisement == 5) selected @endif>Реклама телепроекта</option>
                                </select>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Картинка баннера</p>
                            </div>
                            <div class="right-block" style="text-align: left;">
                                <div>
                                    @if(strlen($row->image) > 0)
                                        <img src="/adv/{{$row->image}}" width="150px" height="150px" id="image">
                                    @else
                                        <img src="/css/image/no_photo.png" width="150px" height="150px" id="image">
                                    @endif
                                </div>
                                <div style="margin: 10px 0px 0px 0px;">
                                    <input id="fileupload" type="file" name="image">
                                    <br>
                                    <p style="color: red;">Рекомендуемый размер картинки: 330х220 </p>
                                    {{--<p style="color: red;">Если выбран тип баннера Боковое меню размер картинки должно быть 476х183</p>--}}
                                </div>
                            </div>
                            <div class="clearfloat"></div>


                            @if($row->advertisement_id > 0)
                                <div style="margin-top: 10px; float: right;">
                                    <input type="submit" class="btn btn-primary" value="Сохранить">
                                </div>
                            @else
                                <div style="margin-top: 10px; float: right;">
                                    <input type="submit" class="btn btn-primary" value="Добавить">
                                </div>
                            @endif
                            <div class="clearfloat"></div>
                        </div>

                        <div class="clearfloat"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
