@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <a href="/admin/video-archive-edit/0">
                <input type="button" class="btn btn-primary" value="Добавить видео в архив" style="margin: 0px 0px 15px 0px;">
            </a>

            <form method="post" id="myform" action="/admin/video-archive-list">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="left-block" style="width: 50px !important;">
                    <p>Дата</p>
                </div>
                <div class="right-block" style="width: 150px;">
                    <div class="control-group">
                        <div class="row-fluid input-prepend">
                            <span class="add-on">
                                <i class="icon-calendar"></i>
                            </span>
                            <input type="text" name="date" value="@if(strlen($date) > 0) {{date("d.m.Y",strtotime($date))}} @endif" class="datepicker selected-date" style="width: 95px; text-align: center;">
                        </div>
                    </div>
                </div>

                <div class="left-block" style="width: 25px !important;">
                    <p>по</p>
                </div>
                <div class="right-block" style="width: 150px;">
                    <div class="control-group">
                        <div class="row-fluid input-prepend">
                            <span class="add-on">
                                <i class="icon-calendar"></i>
                            </span>
                            <input type="text" name="date_to" value="@if(strlen($date_to) > 0) {{date("d.m.Y",strtotime($date_to))}} @endif" class="datepicker selected-date" style="width: 95px; text-align: center;">
                        </div>
                    </div>
                </div>
                <div class="clearfloat"></div>

                <div class="left-block" style="width: 100px;">
                    <p>Наименование</p>
                </div>
                <div class="right-block">
                    <input type="text" value="@if(strlen($name) > 0){{$name}}@endif" name="name">
                </div>
                <div class="clearfloat"></div>

                <div class="left-block" style="width: 100px;">
                    <p>Язык видеоархива</p>
                </div>
                <div class="right-block">
                    <select name="programm_lang_id">
                        <option value="0">Выберите язык</option>
                        <option value="1" @if($programm_lang_id == 1) selected @endif>Казахский</option>
                        <option value="2" @if($programm_lang_id == 2) selected @endif>Русский</option>
                    </select>
                </div>
                <div class="clearfloat"></div>

                <div class="left-block" style="width: 100px;">
                    <p>Телепроект</p>
                </div>
                <div class="right-block">
                    <? use App\Models\Programm;
                        $programm_list = Programm::all();
                    ?>
                    <select name="programm_id">
                        <option value="0">Выберите телепроект</option>
                        @if(count($programm_list) > 0)
                            @foreach($programm_list as $key => $programm_item)
                                <option value="{{$programm_item['programm_id']}}" @if($programm_item['programm_id'] == $programm_id) selected @endif >
                                    @if(strlen($programm_item['programm_name_ru']) > 0)
                                        {{$programm_item['programm_name_ru']}}
                                    @else
                                        {{$programm_item['programm_name_kz']}}
                                    @endif
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="left-block" style="width: 100px !important;">
                    <input type="submit" class="btn btn-primary" value="Поиск" style="line-height: normal; padding: 2px 15px;">
                </div>

                <div class="clearfloat"></div>
            </form>
            <div class="clearfloat"></div>
            <input type="button" class="btn btn-primary" value="Удалить отмеченные" style="line-height: normal; padding: 2px 15px; float: right; margin-bottom: 10px; margin-top: 0px;" onclick="deleteSelectedRows('video-archive-list')">
            <div class="clearfloat"></div>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 50px;"><input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addAllToArray(this)">ID</th>
                        <th class="center" style="width: 250px;">Заголовок (Рус)</th>
                        <th class="center" style="width: 250px;">Заголовок (Каз)</th>
                        <th class="center" style="width: 150px;">Программа</th>
                        <th class="center" style="width: 150px;">Дата</th>
                        <th class="center" style="width: 150px;">Ссылка</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $video_archive_item)
                        <tr class="row_{{$video_archive_item->video_archive_id}}">
                            <td class="center">
                                <input type="hidden" value="{{$video_archive_item->video_archive_id}}" class="hidden-id-input">
                                <input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addToArray({{$video_archive_item->video_archive_id}},this)" class="checkbox-item-list">
                                <a href="/admin/video-archive-edit/{{$video_archive_item->video_archive_id}}">{{$video_archive_item->video_archive_id}}</a>
                            </td>
                            <td><a href="/admin/video-archive-edit/{{$video_archive_item->video_archive_id}}">{{$video_archive_item->video_archive_title_ru}}</a></td>
                            <td><a href="/admin/video-archive-edit/{{$video_archive_item->video_archive_id}}">{{$video_archive_item->video_archive_title_kz}}</a></td>
                            <td>{{$video_archive_item->programm_name_ru}}</td>
                            <td>{{$video_archive_item->video_archive_date}}</td>
                            <td>{{$video_archive_item->youtube_link}}</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0px 0px 5px 0px;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0px 20px;" onclick="deleteVideoArchive({{$video_archive_item->video_archive_id}},this)" value="Удалить">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
            <div class="dataTables_paginate paging_bootstrap pagination">
                {!! str_replace('/?', '?', $row->render()) !!}
            </div>
        </div>
    </div>

    <script>
        function deleteVideoArchive(video_archive_id,ob){
            if (!confirm('Вы действительно хотите удалить видео №' + video_archive_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-video-archive",
                data: {_token: CSRF_TOKEN, video_archive_id: video_archive_id},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при удалении видео");
                    }
                    else{
                        showInfo("Видео #"+video_archive_id+" удалено");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

