@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0;">
            <a href="{{ url('/admin/employer-edit/0') }}">
                <input type="button" class="btn btn-primary" value="Добавить сотрудника" style="margin: 0 0 15px 0;">
            </a>
            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 400px;">Имя</th>
                        <th class="center" style="width: 400px;">Позиция</th>
                        <th class="center" style="width: 50px;">Порядок</th>
                        <th class="center" style="width: 50px;">Меню</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $item)
                        <tr class="row_{{$item->id}}">
                            <td><a href="/admin/employer-edit/{{$item->id}}">{{$item->name}}</a></td>
                            <td>{{$item->position}}</td>
                            <td>{{$item->order}}</td>
                            <td>@foreach($items as $i)@if($item->menu_item_id == $i->id){{$i->name_ru}}@endif @endforeach</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0 0 5px 0;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0 20px;" onclick="deleteEmployer({{$item->id}},this, '{{$item->name}}')" value="Удалить">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>

    <script>
        function deleteEmployer(id,ob, name){
            if (!confirm('Вы действительно хотите удалить сотрудника ' + name +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-employer",
                data: {_token: CSRF_TOKEN, id: id},
                success: function(data){
                    if(data.result === false){
                        alert("Ошибка при удалении сотрудника");
                    }
                    else{
                        showInfo("Сотрудник "+name+" удалена");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

