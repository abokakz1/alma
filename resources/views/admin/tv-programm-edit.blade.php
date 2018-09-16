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
                    <form id="myform" enctype="multipart/form-data" method="post" action="/admin/tv-programm-edit/{{$row->tv_programm_id}}" style="margin-bottom: 0px;">
                        <div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="tv_programm_id" value="{{$row->tv_programm_id}}">

                            <div style="float: left;">
                                <div class="left-block">
                                    <p>Наименование ТВ программа (каз)</p>
                                </div>
                                <div class="right-block">
                                    <input type="text" name="tv_programm_name_kz" value="{{$row->tv_programm_name_kz}}">
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Наименование ТВ программа (рус)</p>
                                </div>
                                <div class="right-block">
                                    <input type="text" name="tv_programm_name_ru" value="{{$row->tv_programm_name_ru}}">
                                </div>
                                <div class="clearfloat"></div>

                                <div style="float: left; margin-top: 10px;">
                                    <div class="left-block">
                                        <p>Дата</p>
                                    </div>
                                    <div class="right-block">
                                        <input type="text" name="date" value="{{$row->date}}" class="datepicker" style="width: 95px; text-align: center;">
                                    </div>
                                    <div class="clearfloat"></div>
                                </div>
                                <div class="clearfloat"></div>

                                <div style="float: left; margin-top: 10px;">
                                    <div class="left-block">
                                        <p>Время начало</p>
                                    </div>
                                    <div class="right-block">
                                        <div class="input-append bootstrap-timepicker" style="margin-bottom: 0px;">
                                            <input type="text" class="input-small timepicker" style="width: 75px;" name="time" value="{{$row->time}}"/>
                                            <span class="add-on">
                                                <i class="icon-time"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="clearfloat"></div>
                                </div>
                                <div class="clearfloat"></div>

                                <div style="float: left; margin-top: 10px;">
                                    <div class="left-block">
                                        <p>Время окончания</p>
                                    </div>
                                    <div class="right-block">
                                        <div class="input-append bootstrap-timepicker" style="margin-bottom: 0px;">
                                            <input type="text" class="input-small timepicker" style="width: 75px;" name="time_end" value="{{$row->time_end}}"/>
                                            <span class="add-on">
                                                <i class="icon-time"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="clearfloat"></div>
                                </div>
                                <div class="clearfloat"></div>

                                <div style="float: left; margin-top: 10px;">
                                    <div class="left-block">
                                        <p>Краткое описание (каз)</p>
                                    </div>
                                    <div class="right-block">
                                        <textarea name="tv_programm_short_description_kz" style="width: 244px; resize: vertical; min-height: 100px; max-height: 200px; height: 100px;">{{$row->tv_programm_short_description_kz}}</textarea>
                                    </div>
                                    <div class="clearfloat"></div>
                                </div>
                                <div class="clearfloat"></div>

                                <div style="float: left; margin-top: 10px;">
                                    <div class="left-block">
                                        <p>Краткое описание (рус)</p>
                                    </div>
                                    <div class="right-block">
                                        <textarea name="tv_programm_short_description_ru" style="width: 244px; resize: vertical; min-height: 100px; max-height: 200px; height: 100px;">{{$row->tv_programm_short_description_ru}}</textarea>
                                    </div>
                                    <div class="clearfloat"></div>
                                </div>
                                <div class="clearfloat"></div>

                                <div style="float: left; margin-top: 10px;">
                                    <div class="left-block">
                                        <p>Полное описание (каз)</p>
                                    </div>
                                    <div class="right-block">
                                        <textarea name="tv_programm_description_kz" style="width: 244px; resize: vertical; min-height: 100px; max-height: 200px; height: 100px;">{{$row->tv_programm_description_kz}}</textarea>
                                    </div>
                                    <div class="clearfloat"></div>
                                </div>
                                <div class="clearfloat"></div>

                                <div style="float: left; margin-top: 10px;">
                                    <div class="left-block">
                                        <p>Полное описание (рус)</p>
                                    </div>
                                    <div class="right-block">
                                        <textarea name="tv_programm_description_ru" style="width: 244px; resize: vertical; min-height: 100px; max-height: 200px; height: 100px;">{{$row->tv_programm_description_ru}}</textarea>
                                    </div>
                                    <div class="clearfloat"></div>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Основная программа</p>
                                </div>
                                <div class="right-block">
                                    <input type="hidden" name="is_main_programm" value="{{$row->is_main_programm}}" class="hidden-checkbox-value">
                                    <label style="margin-top: 5px;">
                                        <?php $checked1 = " "; ?>
                                        @if($row->is_main_programm == 1)
                                            <?php $checked1 = " checked"; ?>
                                        @endif
                                        <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_main_programm}}" onchange="changeCheckboxValue(this)"/>
                                        <span class="lbl"></span>
                                    </label>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Категория программы</p>
                                </div>
                                <div class="right-block">
                                    <?php use App\Models\Category;
                                        $category_list = Category::all();
                                    ?>
                                    <select name="category_id">
                                        <option value="0">Выберите категорию</option>
                                        @if(count($category_list) > 0)
                                            @foreach($category_list as $key => $category_item)
                                                <option value="{{$category_item->category_id}}" @if($category_item->category_id == $row->category_id) selected @endif>{{$category_item->category_name_ru}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Телепроект</p>
                                </div>
                                <div class="right-block">
                                    <?php use App\Models\Programm;
                                    $programm_list = Programm::all();
                                    ?>
                                    <select name="tv_programm_programm_id">
                                        <option value="0">Выберите телепроект</option>
                                        @if(count($programm_list) > 0)
                                            @foreach($programm_list as $key => $programm_item)
                                                <option value="{{$programm_item->programm_id}}" @if($programm_item->programm_id == $row->tv_programm_programm_id) selected @endif>{{$programm_item->programm_name_ru}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="clearfloat"></div>
                            </div>

                            <div style="float: left; margin-left: 100px;">
                                <div style="text-align: center;">
                                    @if(strlen($row->image) > 0)
                                        <img src="/tv_programm_photo/{{$row->image}}" width="150px" height="150px" id="tv_programm_image">
                                    @else
                                        <img src="/css/image/no_photo.png" width="150px" height="150px" id="tv_programm_image">
                                    @endif
                                </div>
                                <div style="text-align: center; margin: 10px 0px 0px 20px;">
                                    <input id="fileupload" type="file" name="image"><br>
                                    <p style="font-size: 14px; color: red;">Рекомендуемый размер картинки: 480х135</p>
                                </div>
                            </div>
                            <div class="clearfloat"></div>

                            @if($row->tv_programm_id > 0)
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
