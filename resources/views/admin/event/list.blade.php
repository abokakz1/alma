@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0;">
            <!-- <a href="{{ url('/admin/event-edit/0') }}">
                <input type="button" class="btn btn-primary" value="Добавить событие" style="margin: 0 0 15px 0;">
            </a> -->

            <form method="post" id="myform" action="{{ url('/admin/event-list') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="left-block" style="width: 120px !important;">
                    <p>Дата проведение c </p>
                </div>
                <div class="right-block" style="width: 150px;">
                    <div class="control-group">
                        <div class="row-fluid input-prepend">
                            <span class="add-on">
                                <i class="icon-calendar"></i>
                            </span>
                            <input type="text" name="date" value="@if(strlen($event_date) > 0) {{date("d.m.Y",strtotime($event_date))}} @endif" class="datepicker selected-date" style="width: 95px; text-align: center;" title="">
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
                            <input type="text" name="date_to" value="@if(strlen($event_date_to) > 0) {{date("d.m.Y",strtotime($event_date_to))}} @endif" class="datepicker selected-date" style="width: 95px; text-align: center;" title="">
                        </div>
                    </div>
                </div>
                <div class="clearfloat"></div>

                <div class="left-block" style="width: 120px;">
                    <p>Искать по типу</p>
                </div>
                <div class="right-block">
                    <select name="event_lang_id" title="">
                        <option value="0">Выберите тип</option>
                        <option value="1" @if($event_lang_id == 1) selected @endif>Топ событий</option>
                        <option value="2" @if($event_lang_id == 2) selected @endif>Опубликованные</option>
                        <option value="3" @if($event_lang_id == 3) selected @endif>В календаре</option>
                        <option value="4" @if($event_lang_id == 4) selected @endif>Бесплатные</option>
                        <option value="5" @if($event_lang_id == 5) selected @endif>Платные</option>
                    </select>
                </div>
                <div class="clearfloat"></div>

                <div class="left-block" style="width: 120px;">
                    <p>Наименование</p>
                </div>
                <div class="right-block">
                    <input type="text" value="@if(strlen($name) > 0){{$name}}@endif" name="name" title="">
                </div>
                <div class="clearfloat"></div>

                <div class="left-block" style="width: 120px;">
                    <p>Категория событий</p>
                </div>
                <div class="right-block">
                    <?php use App\Models\EvCategory;
                    $category_list = EvCategory::all();
                    ?>
                    <select name="category_id" title="">
                        <option value="">Выберите категорию событий</option>
                        @if(count($category_list) > 0)
                            @foreach($category_list as $key => $category_item)
                                <option value="{{$category_item->category_id}}" @if($category_item->category_id == $category_id) selected @endif >{{$category_item['category_name_ru']}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="clearfloat"></div>


                <div class="left-block" style="width: 100px !important;">
                    <input type="submit" class="btn btn-primary" value="Поиск" style="line-height: normal; padding: 2px 15px;">
                </div>

                <div class="clearfloat"></div>
            </form>
            <div class="clearfloat"></div>
            <input type="button" class="btn btn-primary" value="Удалить отмеченные" style="line-height: normal; padding: 2px 15px; float: right; margin-bottom: 10px; margin-top: 0;" onclick="deleteSelectedRows('event')">
            <div class="clearfloat"></div>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 60px;"><input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addAllToArray(this)" title=""> ID</th>
                        <th class="center" style="width: 400px;">Заголовок</th>
                        <!-- <th class="center" style="width: 400px;">Заголовок (Рус)</th> -->
                        <th class="center" style="width: 100px;">Дата проведение</th>
                        <th class="center" style="width: 100px;">Дата создания</th>
                        <th class="center" style="width: 100px;">Предпросмотр</th>
                        <th class="center" style="width: 100px;">Опубликовано</th>
                        <!-- <th class="center" style="width: 50px;">Топ событие</th> -->
                        <th class="center" style="width: 50px;">Организатор</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $event_item)
                        <tr class="row_{{$event_item->event_id}}">
                            <td class="center">
                                <input type="hidden" value="{{$event_item->event_id}}" class="hidden-id-input">
                                <input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addToArray({{$event_item->event_id}},this)" class="checkbox-item-list" title="">
                                <a href="/admin/event-edit/{{$event_item->event_id}}">{{$event_item->event_id}}</a>
                            </td>
                            <td><a href="/admin/event-edit/{{$event_item->event_id}}">{{$event_item->event_title}}</a></td>
                            <!-- <td><a href="/admin/event-edit/{{$event_item->event_id}}">{{$event_item->event_title}}</a></td> -->
                            <td>{{$event_item->date_start}}</td>
                            <td>{{$event_item->created_at}}</td>
                            <td>
                            <a target="_blank" href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/events/" . $event_item->event_url_name) }}">Перейти</a>
                            </td>
                            <td class="center">
                                @if($event_item->published == 1)
                                    Да
                                @else
                                    Нет
                                @endif
                            </td>
                            <!-- <td>
                                @if($event_item->is_main_event == 1)
                                    Да
                                @else
                                    Нет
                                @endif
                            </td> -->
                            <td>{{ $event_item->organizer_name }}</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0 0 5px 0;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0 20px;" onclick="deleteEvent({{$event_item->event_id}},this)" value="Удалить">
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
        function deleteEvent(event_id,ob){
            if (!confirm('Вы действительно хотите удалить событие №' + event_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-event",
                data: {_token: CSRF_TOKEN, event_id: event_id},
                success: function(data){
                    if(data.result === false){
                        alert("Ошибка при удалении событий");
                    }
                    else{
                        showInfo("Событие #"+event_id+" удалена");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

