@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0;">
        <div class="left-block">
            <p><b>Выбор редакций</b></p>
        </div>
        <div class="clearfloat"></div>
            
            <form method="post" id="myform" action="{{ url('/admin/fav-list') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="type" value="fav">

                <div class="left-block" style="width: 60px;">
                    <p>Ссылка:</p>
                </div>
                <div class="right-block" style="width: 420px;">
                    <input type="text" value="" placeholder="Ссылка" name="link" style="float: left;min-width: 300px">
                    <input type="submit" class="btn btn-primary" value="Добавить" style="line-height: normal; padding: 2px 15px;float: right;width: 100px;">
                </div>
                <div class="clearfloat"></div>
            </form>

            <!-- <div class="clearfloat"></div>
            <input type="button" class="btn btn-primary" value="Удалить отмеченные" style="line-height: normal; padding: 2px 15px; float: right; margin-bottom: 10px; margin-top: 0;" onclick="deleteSelectedRows('blog')">
            <div class="clearfloat"></div> -->

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 60px;">ID</th>
                        <th class="center" style="width: 400px;">Заголовок (Каз) </th>
                        <th class="center" style="width: 400px;">Заголовок (Рус)</th>
                        <th class="center" style="width: 50px;">Дата</th>
                        <th class="center" style="width: 100px;">Предпросмотр</th>
                        <th class="center" style="width: 100px;">Тип</th>
                        <th class="center" style="width: 100px;">Языки</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $item)
                        <tr class="row_{{$item->id}}">
                            <td class="center">{{$item->id}}</td>
                            <td>{{$item->title_kz}}</td>
                            <td>{{$item->title_ru}}</td>
                            <td>{{$item->date}}</td>
                            <td class="center"><a target="_blank" href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), $item->url) }}">Перейти</a></td>
                            <td>{{$item->type_ru}}</td>
                            <td>@if($item->is_active_kz && $item->is_active_ru)Каз | Рус
                            @elseif($item->is_active_kz)Каз 
                            @elseif($item->is_active_ru)Рус
                            @endif</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0 0 5px 0;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0 20px;" onclick="deleteFav({{$item->id}},this, 1)" value="Удалить">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>

            <div class="left-block">
                <p><b>Два блока в главном</b></p>
            </div>
            <div class="clearfloat"></div>

            <form method="post" id="myform" action="{{ url('/admin/fav-list') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="type" value="two">
                
                <div class="left-block" style="width: 60px;">
                    <p>Ссылка:</p>
                </div>
                <div class="right-block" style="width: 420px;">
                    <input type="text" value="" placeholder="Ссылка" name="link" style="float: left;min-width: 300px">
                    <input type="submit" class="btn btn-primary" value="Добавить" style="line-height: normal; padding: 2px 15px;float: right;width: 100px;">
                </div>
                <div class="clearfloat"></div>
            </form>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 60px;">ID</th>
                        <th class="center" style="width: 400px;">Заголовок (Каз) </th>
                        <th class="center" style="width: 400px;">Заголовок (Рус)</th>
                        <th class="center" style="width: 50px;">Дата</th>
                        <th class="center" style="width: 100px;">Предпросмотр</th>
                        <th class="center" style="width: 100px;">Тип</th>
                        <th class="center" style="width: 100px;">Языки</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row2) > 0)
                    @foreach($row2 as $key => $item)
                        <tr class="row_{{$item->id}}">
                            <td class="center">{{$item->id}}</td>
                            <td>{{$item->title_kz}}</td>
                            <td>{{$item->title_ru}}</td>
                            <td>{{$item->date}}</td>
                            <td class="center"><a target="_blank" href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), $item->url) }}">Перейти</a></td>
                            <td>{{$item->type_ru}}</td>
                            <td>@if($item->is_active_kz && $item->is_active_ru)Каз | Рус
                            @elseif($item->is_active_kz)Каз 
                            @elseif($item->is_active_ru)Рус
                            @endif</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0 0 5px 0;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0 20px;" onclick="deleteFav({{$item->id}},this, 2)" value="Удалить">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>


            <!-- ДВА БЛОГА В ГЛАВНОМ -->
            <div class="left-block">
                <p><b>Два блога в главном</b></p>
            </div>
            <div class="clearfloat"></div>

            <form method="post" id="myform" action="{{ url('/admin/fav-list') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="type" value="blog">
                
                <div class="left-block" style="width: 60px;">
                    <p>Ссылка:</p>
                </div>
                <div class="right-block" style="width: 420px;">
                    <input type="text" value="" placeholder="Ссылка" name="link" style="float: left;min-width: 300px">
                    <input type="submit" class="btn btn-primary" value="Добавить" style="line-height: normal; padding: 2px 15px;float: right;width: 100px;">
                </div>
                <div class="clearfloat"></div>
            </form>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 60px;">ID</th>
                        <th class="center" style="width: 400px;">Заголовок (Каз) </th>
                        <th class="center" style="width: 400px;">Заголовок (Рус)</th>
                        <th class="center" style="width: 50px;">Дата</th>
                        <th class="center" style="width: 100px;">Предпросмотр</th>
                        <th class="center" style="width: 100px;">Тип</th>
                        <th class="center" style="width: 100px;">Языки</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row3) > 0)
                    @foreach($row3 as $key => $item)
                        <tr class="row_{{$item->id}}">
                            <td class="center">{{$item->id}}</td>
                            <td>{{$item->title_kz}}</td>
                            <td>{{$item->title_ru}}</td>
                            <td>{{$item->date}}</td>
                            <td class="center"><a target="_blank" href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), $item->url) }}">Перейти</a></td>
                            <td>{{$item->type_ru}}</td>
                            <td>@if($item->is_active_kz && $item->is_active_ru)Каз | Рус
                            @elseif($item->is_active_kz)Каз 
                            @elseif($item->is_active_ru)Рус
                            @endif</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0 0 5px 0;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0 20px;" onclick="deleteFav({{$item->id}},this, 3)" value="Удалить">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>

        </div>
    </div>

    <script>
        function deleteFav(fav_id,ob, type){
            if (!confirm('Вы действительно хотите удалить №' + fav_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-fav",
                data: {_token: CSRF_TOKEN, fav_id: fav_id, type: type},
                success: function(data){
                    if(data.result === false){
                        alert("Ошибка при удалении блога");
                    }
                    else{
                        showInfo("#"+fav_id+" удалена");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

