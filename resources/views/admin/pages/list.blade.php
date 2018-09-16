@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0;">
            <a href="{{ url('/admin/page-edit/0') }}">
                <input type="button" class="btn btn-primary" value="Добавить страницу" style="margin: 0 0 15px 0;">
            </a>
            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th class="center" style="width: 400px;">Текст(Каз)</th>
                        <th class="center" style="width: 400px;">Текст(Рус)</th>
                        <th class="center" style="width: 100px;">Меню</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $item)
                        <tr class="row_{{$item->id}}">
                            <td>{{$item->id}}</td>
                            <td><a href="/admin/page-edit/{{$item->id}}">{{ mb_substr($item->text_kz, 0, 100) }}</a></td>
                            <td><a href="/admin/page-edit/{{$item->id}}">{{ mb_substr($item->text_ru, 0, 100) }}</a></td>
                            <td>@foreach($items as $i) @if($i->id == $item->menu_item_id){{$i->name_ru}}@endif @endforeach</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0 0 5px 0;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0 20px;" onclick="deleteFooter({{$item->id}},this, '{{$item->id}}')" value="Удалить">
                                </div>
                            </td>
                        </tr>
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
                url: "/admin/delete-page",
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

