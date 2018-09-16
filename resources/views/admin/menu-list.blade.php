@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <a href="/admin/menu-edit/0">
                <input type="button" class="btn btn-primary" value="Добавить меню" style="margin: 0px 0px 15px 0px;">
            </a>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 50px;">ID</th>
                        <th class="center" style="width: 250px;">Название</th>
                        <th class="center" style="width: 250px;">Порядковый номер</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $menu_item)
                        <tr>
                            <td class="center">
                                <a href="/admin/menu-edit/{{$menu_item->menu_id}}">{{$menu_item->menu_id}}</a>
                            </td>
                            <td><a href="/admin/menu-edit/{{$menu_item->menu_id}}">{{$menu_item->menu_title}}</a></td>
                            <td>{{$menu_item->order_num}}</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0px 0px 5px 0px;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0px 20px;" onclick="deleteMenu({{$menu_item->menu_id}},this)" value="Удалить">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>

    <script>
        function deleteMenu(menu_id,ob){
            if (!confirm('Вы действительно хотите удалить меню №' + menu_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-menu",
                data: {_token: CSRF_TOKEN, menu_id: menu_id},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при удалении меню");
                    }
                    else{
                        showInfo("Меню #" + menu_id + " удалена");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

