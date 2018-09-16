@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <a href="/admin/news-archive-edit/0/{{$lang}}">
                <input type="button" class="btn btn-primary" value="Добавить новость" style="margin: 0px 0px 15px 0px;">
            </a>

            <form method="post" id="myform" action="/admin/news-archive-list/{{$lang}}">
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
                            <input type="text" name="date" value="@if(strlen($news_date) > 0) {{date("d.m.Y",strtotime($news_date))}} @endif" class="datepicker selected-date" style="width: 95px; text-align: center;">
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
                            <input type="text" name="date_to" value="@if(strlen($news_date_to) > 0) {{date("d.m.Y",strtotime($news_date_to))}} @endif" class="datepicker selected-date" style="width: 95px; text-align: center;">
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

                <div class="left-block">
                    <p>Категория новости</p>
                </div>
                <div class="right-block">
                    <? use App\Models\NewsCategory;
                    $news_category_list = NewsCategory::all();
                    ?>
                    <select name="news_category_id" >
                        <option value="">Выберите категорию новости</option>
                        @if(count($news_category_list) > 0)
                            @foreach($news_category_list as $key => $news_category_item)
                                <option value="{{$news_category_item->news_category_id}}" @if($news_category_item->news_category_id == $news_category_id) selected @endif >{{$news_category_item['news_category_name_ru']}}</option>
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
            <input type="button" class="btn btn-primary" value="Удалить отмеченные" style="line-height: normal; padding: 2px 15px; float: right; margin-bottom: 10px; margin-top: 0px;" onclick="deleteSelectedRows('news-archive-list-{{$lang}}')">
            <div class="clearfloat"></div>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 50px;"><input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addAllToArray(this)">ID</th>
                        <th class="center" style="width: 400px;">Заголовок </th>
                        <th class="center" style="width: 50px;">Дата</th>
                        <th class="center" style="width: 50px;">Картинка</th>
                        <th class="center" style="width: 100px;">Предпросмотр</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $news_item)
                        <tr class="row_{{$news_item->news_id}}">
                            <td class="center">
                                <input type="hidden" value="{{$news_item->news_id}}" class="hidden-id-input">
                                <input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addToArray({{$news_item->news_id}},this)" class="checkbox-item-list">
                                <a href="/admin/news-archive-edit/{{$news_item->news_id}}/{{$lang}}">{{$news_item->news_id}}</a>
                            </td>
                            @if($lang == "kz")
                                <td><a href="/admin/news-archive-edit/{{$news_item->news_id}}/{{$lang}}">{{$news_item->news_title_kz}}</a></td>
                            @elseif($lang == "ru")
                                <td><a href="/admin/news-archive-edit/{{$news_item->news_id}}/{{$lang}}">{{$news_item->news_title_ru}}</a></td>
                            @else
                                <td></td>
                            @endif
                            <td>{{$news_item->date}}</td>
                            <td>{{$news_item->image}}</td>
                            <td class="center"><a target="_blank" href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news-archive/news/" . $news_item->news_url_name) }}">Перейти</a></td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0px 0px 5px 0px;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0px 20px;" onclick="deleteNews({{$news_item->news_id}},this,'{{$lang}}')" value="Удалить">
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
        function deleteNews(news_id,ob,lang){
            if (!confirm('Вы действительно хотите удалить новость №' + news_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-archive-news",
                data: {_token: CSRF_TOKEN, news_id: news_id, lang: lang},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при удалении новости");
                    }
                    else{
                        showInfo("Новость #"+news_id+" удалена");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

