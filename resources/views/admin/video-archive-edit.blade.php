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
                    <form id="myform" method="post" enctype="multipart/form-data" action="/admin/video-archive-edit/{{$row->video_archive_id}}" style="margin-bottom: 0px;">
                        <div style="float: left; width: 900px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="video_archive_id" value="{{$row->video_archive_id}}">

                            <div class="left-block">
                                <p>Заголовок (Каз)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="video_archive_title_kz" value="{{$row->video_archive_title_kz}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Заголовок (Рус)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="video_archive_title_ru" value="{{$row->video_archive_title_ru}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Заголовок (Анг)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="video_archive_title_en" value="{{$row->video_archive_title_en}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Программа</p>
                            </div>
                            <div class="right-block">
                                <select name="programm_id">
                                    <option value="null">Выберите программу/телепередачу</option>
                                    @if(count($programm_list) > 0)
                                        @foreach($programm_list as $key => $programm_item)
                                            <option value="{{$programm_item->programm_id}}" @if($programm_item->programm_id == $row->programm_id) selected @endif >{{$programm_item['programm_name_ru']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block" style="display: none;">
                                <p>Язык видеоархива</p>
                            </div>
                            <div class="right-block" style="display: none;">
                                <select name="programm_lang_id">
                                    <option value="0">Выберите язык</option>
                                    <option value="1" @if($row->programm_lang_id == 1) selected @endif>Казахский</option>
                                    <option value="2" @if($row->programm_lang_id == 2) selected @endif>Русский</option>
                                </select>
                            </div>
                            <div class="clearfloat"></div>

                            <div style="float: left; margin-top: 10px;">
                                <div class="left-block">
                                    <p>Дата</p>
                                </div>
                                <div class="right-block">
                                    <input type="text" name="video_archive_date" value="{{$row->video_archive_date}}" class="datepicker" style="width: 95px; text-align: center;">
                                </div>
                                <div class="clearfloat"></div>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Ссылка на YouTube</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="youtube_link" value="{{$row->youtube_link}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Код на видео YouTube</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="youtube_video_code" value="{{$row->youtube_video_code}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Описание видео (Каз)</p>
                            </div>
                            <div class="right-block">
                                <textarea id="video_archive_editor1" name="video_description_kz">{{$row->video_description_kz}}</textarea>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Описание видео (Рус)</p>
                            </div>
                            <div class="right-block">
                                <textarea id="video_archive_editor2" name="video_description_ru">{{$row->video_description_ru}}</textarea>
                            </div>
                            <div class="clearfloat"></div>
                        </div>

                        <div style="float: left; margin: -6px 0px 0px -313px; text-align: center;">
                            <div style="text-align: center;">
                                @if(strlen($row->image) > 0)
                                    <img src="/video_archive_photo/{{$row->image}}" width="150px" height="150px" id="video_archive_image">
                                @else
                                    <img src="/css/image/no_photo.png" width="150px" height="150px" id="video_archive_image">
                                @endif
                            </div>
                            <div style="text-align: center; margin: 10px 0px 0px 20px;">
                                {{--<input type="button" value="Загрузить" class="btn btn-primary" onclick = "$('#fileupload').click()">--}}
                                <input id="fileupload" type="file" name="image"><br>
                                <p style="font-size: 14px; color: red;">Рекомендуемый размер картинки: 277х220</p>
                            </div>
                        </div>
                        <div class="clearfloat"></div>

                        <div class="left-block">
                            <p>Описание (для мета тега) и Анонс</p>
                        </div>
                        <div class="right-block">
                            <textarea name="video_archive_meta_desc" style="width: 300px; height: 100px;">{{$row->video_archive_meta_desc}}</textarea>
                        </div>
                        <div class="clearfloat"></div>

                        @if($row->video_archive_id > 0)
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
