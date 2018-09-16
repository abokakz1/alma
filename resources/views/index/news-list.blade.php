@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <a href="/admin/news-edit/0">
                <input type="button" class="btn btn-primary" value="Добавить новость" style="margin: 0px 0px 15px 0px;">
            </a>

            <form method="post" id="myform" action="/admin/news-list">
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

                <div class="left-block">
                    <p>Тэг новости</p>
                </div>
                <div class="right-block">
                    <? use App\Models\Tag;
                    $tag_list = Tag::all();
                    ?>
                    <select name="tag_id" >
                        <option value="">Выберите тэг новости</option>
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

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 50px;">ID</th>
                        <th class="center" style="width: 400px;">Заголовок (Каз) </th>
                        <th class="center" style="width: 400px;">Заголовок (Рус)</th>
                        <th class="center" style="width: 50px;">Дата</th>
                        <th class="center" style="width: 100px;">Программа</th>
                        <th class="center" style="width: 50px;">Картинка</th>
                        <th class="center" style="width: 100px;">Предпросмотр</th>
                        <th class="center" style="width: 100px;">Активный</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $news_item)
                        <tr>
                            <td class="center">
                                <a href="/admin/news-edit/{{$news_item->news_id}}">{{$news_item->news_id}}</a>
                            </td>
                            <td><a href="/admin/news-edit/{{$news_item->news_id}}">{{$news_item->news_title_kz}}</a></td>
                            <td><a href="/admin/news-edit/{{$news_item->news_id}}">{{$news_item->news_title_ru}}</a></td>
                            <td>{{$news_item->date}}</td>
                            <td>{{$news_item->programm_name}}</td>
                            <td>{{$news_item->image}}</td>
                            <td class="center"><a target="_blank" href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/news/news/" . $news_item->news_url_name) }}">Перейти</a></td>
                            <td>
                                @if($news_item->is_active == 1)
                                    Да
                                @else
                                    Нет
                                @endif
                            </td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0px 0px 5px 0px;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0px 20px;" onclick="deleteNews({{$news_item->news_id}},this)" value="Удалить">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>

    <script>
        function deleteNews(news_id,ob){
            if (!confirm('Вы действительно хотите удалить новость №' + news_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-news",
                data: {_token: CSRF_TOKEN, news_id: news_id},
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

