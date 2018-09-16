@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0;">
            <a href="{{ url('/admin/ads-edit/0') }}">
                <input type="button" class="btn btn-primary" value="Добавить документ" style="margin: 0 0 15px 0;">
            </a>
            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 50px;">Id</th>
                        <th class="center" style="width: 200px;">Имя</th>
                        <th class="center" style="width: 100px;">Тип</th>
                        <th class="center" style="width: 100px;">Язык</th>
                        <th class="center" style="width: 400px;">Документ</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $item)
                        <tr class="row_{{$item->id}}">
                            <td><a href="/admin/ads-edit/{{$item->id}}">{{$item->id}}</a></td>
                            <td><a href="/admin/ads-edit/{{$item->id}}">{{$item->name}}</a></td>
                            <td>{{$item->type}}</td>
                            <td>@if($item->locale == "ru")Рус@else Каз@endif</td>
                            <td>@if($item->file)
                                    @if($item->file_type == "pdf")
                                        <a href="/response_files/{{$item->file}}">Перейти</a>
                                    @else
                                        <a href="https://view.officeapps.live.com/op/view.aspx?src=https://almaty.tv/response_files/{{$item->file}}">Перейти</a>
                                    @endif
                                @else
                                    Нет файла
                                @endif
                            </td>
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
                url: "/admin/delete-ads",
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