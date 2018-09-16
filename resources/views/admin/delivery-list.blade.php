@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <a href="/admin/delivery-edit/0">
                <input type="button" class="btn btn-primary" value="Добавить подписчика" style="margin: 0px 0px 15px 0px;">
            </a>
            <div class="clearfloat"></div>
            <input type="button" class="btn btn-primary" value="Удалить отмеченные" style="line-height: normal; padding: 2px 15px; float: right; margin-bottom: 10px; margin-top: 0px;" onclick="deleteSelectedRows('delivery-list')">
            <div class="clearfloat"></div>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 50px;"><input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addAllToArray(this)">ID</th>
                        <th class="center" style="width: 250px">ФИО</th>
                        <th class="center" style="width: 250px;">Email</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $delivery_item)
                        <tr class="row_{{$delivery_item->delivery_id}}">
                            <td class="center">
                                <input type="hidden" value="{{$delivery_item->delivery_id}}" class="hidden-id-input">
                                <input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addToArray({{$delivery_item->delivery_id}},this)"  class="checkbox-item-list">
                                <a href="/admin/delivery-edit/{{$delivery_item->delivery_id}}">{{$delivery_item->delivery_id}}</a>
                            </td>
                            <td> <a href="/admin/delivery-edit/{{$delivery_item->delivery_id}}">{{$delivery_item->delivery_name}}</a></td>
                            <td><?php echo $delivery_item->delivery_email; ?></td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0px 0px 5px 0px;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0px 20px;" onclick="deleteDelivery({{$delivery_item->delivery_id}},this)" value="Удалить">
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
        function deleteDelivery(delivery_id,ob){
            if (!confirm('Вы действительно хотите удалить подписчика №' + delivery_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-delivery",
                data: {_token: CSRF_TOKEN, delivery_id: delivery_id},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при удалении подписчика");
                    }
                    else{
                        showInfo("Подписчик #"+delivery_id+" удален");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

