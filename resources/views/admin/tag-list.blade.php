@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <a href="/admin/tag-edit/0">
                <input type="button" class="btn btn-primary" value="Добавить тэг" style="margin: 0px 0px 15px 0px;">
            </a>
            <div class="clearfloat"></div>
            <input type="button" class="btn btn-primary" value="Удалить отмеченные" style="line-height: normal; padding: 2px 15px; float: right; margin-bottom: 10px; margin-top: 0px;" onclick="deleteSelectedRows('tag-list')">
            <div class="clearfloat"></div>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 60px;"><input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addAllToArray(this)">ID</th>
                        <th class="center" style="width: 200px;">Название</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $value)
                        <tr class="row_{{$value->tag_id}}">
                            <td class="center">
                                <input type="hidden" value="{{$value->tag_id}}" class="hidden-id-input">
                                <input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addToArray({{$value->tag_id}},this)" class="checkbox-item-list">
                                <a>{{$value->tag_id}}</a>
                            </td>
                            <td>
                                <a href="/admin/tag-edit/{{$value->tag_id}}">{{$value->tag_name}}</a>
                            </td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0px 0px 5px 0px;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0px 20px;" onclick="deleteRow({{$value->tag_id}},this)" value="Удалить">
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
        function deleteRow(id,ob){
            if (!confirm('Вы действительно хотите удалить запись №' + id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-tag",
                data: {_token: CSRF_TOKEN, id: id},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при удалении записи");
                    }
                    else{
                        showInfo("Запись #"+id+" удалена");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

