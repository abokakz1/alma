@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0;">
            <a href="{{ url('/admin/about-edit/0') }}">
                <input type="button" class="btn btn-primary" value="Добавить ссылку" style="margin: 0 0 15px 0;">
            </a>
            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 100px;"></th>
                        <th class="center" style="width: 400px;">Имя(Каз)</th>
                        <th class="center" style="width: 400px;">Имя(Рус)</th>
                        <th class="center" style="width: 100px;">Ссылка</th>
                        <th class="center" style="width: 50px;">Порядок</th>
                        <th class="center" style="width: 100px;">Парент</th>
                        <th class="center" style="width: 100px;">Тип</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($main as $key => $item)
                        <tr class="row_{{$item->id}}">
                            <td><span class="main">main</span></td>
                            <td><a href="/admin/about-edit/{{$item->id}}">{{$item->name_kz}}</a></td>
                            <td><a href="/admin/about-edit/{{$item->id}}">{{$item->name_ru}}</a></td>
                            <td>@if($item->url)<a href="/about/{{$item->url}}">Перейти</a>@endif</td>
                            <td>{{$item->order}}</td>
                            <td></td>
                            <td>{{$item->type}}</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0 0 5px 0;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0 20px;" onclick="deleteFooter({{$item->id}},this, '{{$item->name_ru}}')" value="Удалить">
                                </div>
                            </td>
                        </tr>
                        <?php
                        $subitems = $row->filter(function ($value, $key) use($item){
                            return $value->parent_id == $item->id;
                        });?>
                        @foreach($subitems as $s)
                        <tr class="row_{{$s->id}}">
                            <td><span class="sub">sub</span></td>
                            <td><a href="/admin/about-edit/{{$s->id}}">{{$s->name_kz}}</a></td>
                            <td><a href="/admin/about-edit/{{$s->id}}">{{$s->name_ru}}</a></td>
                            <td>@if($s->url)<a href="/about/{{$s->url}}">Перейти</a>@endif</td>
                            <td>{{$s->order}}</td>
                            <td>{{$item->name_ru}}</td>
                            <td>{{$s->type}}</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0 0 5px 0;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0 20px;" onclick="deleteFooter({{$s->id}},this, '{{$s->name_ru}}')" value="Удалить">
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                @endif
            </table>
        </div>
    </div>

    <script>
        function deleteFooter(id,ob, name){
            if (!confirm('Вы действительно хотите удалить ссылку ' + name +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-footer",
                data: {_token: CSRF_TOKEN, id: id},
                success: function(data){
                    if(data.result === false){
                        alert("Ошибка при удалении cсылки");
                    }
                    else{
                        showInfo("Ccылка "+name+" удалена");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>
    <style>
        .sub{
            background-color: gray;
            color: #fff;
            padding: 1px 5px;
            border-radius: 5px;
        }
        .main{
            background-color: green;
            color: #fff;
            padding: 1px 5px;
            border-radius: 5px;
        }
    </style>

@endsection

