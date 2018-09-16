@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <a href="/admin/user-edit/0">
                <input type="button" class="btn btn-primary" value="Добавить пользователя" style="margin: 0px 0px 15px 0px;">
            </a>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 50px;">ID</th>
                        <th class="center" style="width: 250px;">ФИО</th>
                        <th class="center" style="width: 250px;">Email</th>
                        <th class="center" style="width: 250px;">Роль</th>
                        <th class="center" style="width: 250px;">Заблокирован</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $user_item)
                        <tr>
                            <td class="center">
                                <a href="/admin/user-edit/{{$user_item->user_id}}">{{$user_item->user_id}}</a>
                            </td>
                            <td><a href="/admin/user-edit/{{$user_item->user_id}}">{{$user_item->fio}}</a></td>
                            <td>{{$user_item->email}}</td>
                            <td>{{$user_item->role_name}}</td>
                            <td>
                                @if($user_item->is_blocked == 1)
                                    Да
                                @else
                                    Нет
                                @endif
                            </td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0px 0px 5px 0px;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0px 20px;" onclick="deleteUser({{$user_item->user_id}},this)" value="Удалить">
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
        function deleteUser(user_id,ob){
            if (!confirm('Вы действительно хотите удалить пользователя №' + user_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-user",
                data: {_token: CSRF_TOKEN, user_id: user_id},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при удалении пользователя");
                    }
                    else{
                        showInfo("Пользователь #" + user_id + " удален");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

