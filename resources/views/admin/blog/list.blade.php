@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0;">
            <a href="{{ url('/admin/blog-edit/0') }}">
                <input type="button" class="btn btn-primary" value="Добавить блог" style="margin: 0 0 15px 0;">
            </a>

            <form method="post" id="myform" action="{{ url('/admin/blog-list') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="left-block" style="width: 50px !important;">
                    <p>Дата c </p>
                </div>
                <div class="right-block" style="width: 150px;">
                    <div class="control-group">
                        <div class="row-fluid input-prepend">
                            <span class="add-on">
                                <i class="icon-calendar"></i>
                            </span>
                            <input type="text" name="date" value="@if(strlen($blog_date) > 0) {{date("d.m.Y",strtotime($blog_date))}} @endif" class="datepicker selected-date" style="width: 95px; text-align: center;" title="">
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
                            <input type="text" name="date_to" value="@if(strlen($blog_date_to) > 0) {{date("d.m.Y",strtotime($blog_date_to))}} @endif" class="datepicker selected-date" style="width: 95px; text-align: center;" title="">
                        </div>
                    </div>
                </div>
                <div class="clearfloat"></div>

                <div class="left-block" style="width: 100px;">
                    <p>Язык блога</p>
                </div>
                <div class="right-block">
                    <select name="blog_lang_id" title="">
                        <option value="0">Выберите язык</option>
                        <option value="1" @if($blog_lang_id == 1) selected @endif>Казахский</option>
                        <option value="2" @if($blog_lang_id == 2) selected @endif>Русский</option>
                    </select>
                </div>
                <div class="clearfloat"></div>

                <div class="left-block" style="width: 100px;">
                    <p>Наименование</p>
                </div>
                <div class="right-block">
                    <input type="text" value="@if(strlen($name) > 0){{$name}}@endif" name="name" title="">
                </div>
                <div class="clearfloat"></div>

                <div class="left-block" style="width: 100px;">
                    <p>Тэг блога</p>
                </div>
                <div class="right-block">
                    <?php use App\Models\Tag;
                    $tag_list = Tag::all();
                    ?>
                    <select name="tag_id" title="">
                        <option value="">Выберите тэг блога</option>
                        @if(count($tag_list) > 0)
                            @foreach($tag_list as $key => $tag_item)
                                <option value="{{$tag_item->tag_id}}" @if($tag_item->tag_id == $tag_id) selected @endif >{{$tag_item['tag_name']}}</option>
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
            <input type="button" class="btn btn-primary" value="Удалить отмеченные" style="line-height: normal; padding: 2px 15px; float: right; margin-bottom: 10px; margin-top: 0;" onclick="deleteSelectedRows('blog')">
            <div class="clearfloat"></div>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 60px;"><input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addAllToArray(this)" title=""> ID</th>
                        <th class="center" style="width: 400px;">Заголовок (Каз) </th>
                        <th class="center" style="width: 400px;">Заголовок (Рус)</th>
                        <th class="center" style="width: 50px;">Дата</th>
                        <th class="center" style="width: 100px;">Предпросмотр</th>
                        <th class="center" style="width: 100px;">Активный (Каз)</th>
                        <th class="center" style="width: 100px;">Активный (Рус)</th>
                        <th class="center" style="width: 50px;">Блогер</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $blog_item)
                        <tr class="row_{{$blog_item->blog_id}}">
                            <td class="center">
                                <input type="hidden" value="{{$blog_item->blog_id}}" class="hidden-id-input">
                                <input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addToArray({{$blog_item->blog_id}},this)" class="checkbox-item-list" title="">
                                <a href="/admin/blog-edit/{{$blog_item->blog_id}}">{{$blog_item->blog_id}}</a>
                            </td>
                            <td><a href="/admin/blog-edit/{{$blog_item->blog_id}}">{{$blog_item->blog_title_kz}}</a></td>
                            <td><a href="/admin/blog-edit/{{$blog_item->blog_id}}">{{$blog_item->blog_title_ru}}</a></td>
                            <td>{{$blog_item->date}}</td>
                            <td class="center"><a target="_blank" href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/blogs/" . $blog_item->blog_url_name) }}">Перейти</a></td>
                            <td>
                                @if($blog_item->is_active_kz == 1)
                                    Да
                                @else
                                    Нет
                                @endif
                            </td>
                            <td>
                                @if($blog_item->is_active_ru == 1)
                                    Да
                                @else
                                    Нет
                                @endif
                            </td>
                            <td>{{$blog_item->author->fio}}</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0 0 5px 0;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0 20px;" onclick="deleteBlog({{$blog_item->blog_id}},this)" value="Удалить">
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
        function deleteBlog(blog_id,ob){
            if (!confirm('Вы действительно хотите удалить блог №' + blog_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-blog",
                data: {_token: CSRF_TOKEN, blog_id: blog_id},
                success: function(data){
                    if(data.result === false){
                        alert("Ошибка при удалении блога");
                    }
                    else{
                        showInfo("Блог #"+blog_id+" удалена");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

