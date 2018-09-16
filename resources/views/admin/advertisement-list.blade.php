@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <a href="/admin/advertisement-edit/0">
                <input type="button" class="btn btn-primary" value="Добавить баннер" style="margin: 0px 0px 15px 0px;">
            </a>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 50px;">ID</th>
                        <th class="center" style="width: 250px">Заголовок</th>
                        <th class="center" style="width: 250px;">Описание</th>
                        <th class="center" style="width: 200px;">Картинка</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $advertisement_item)
                        <tr>
                            <td class="center">
                                <a href="/admin/advertisement-edit/{{$advertisement_item->advertisement_id}}">{{$advertisement_item->advertisement_id}}</a>
                            </td>
                            <td> <a href="/admin/advertisement-edit/{{$advertisement_item->advertisement_id}}">{{$advertisement_item->advertisement_title_ru}}</a></td>
                            <td><?php echo $advertisement_item->advertisement_text_ru; ?></td>
                            <td>{{$advertisement_item->image}}</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0px 0px 5px 0px;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0px 20px;" onclick="deleteAdvertisement({{$advertisement_item->advertisement_id}},this)" value="Удалить">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>

    <script>
        function deleteAdvertisement(advertisement_id,ob){
            if (!confirm('Вы действительно хотите удалить баннер №' + advertisement_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-advertisement",
                data: {_token: CSRF_TOKEN, advertisement_id: advertisement_id},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при удалении баннера");
                    }
                    else{
                        showInfo("Баннер #"+advertisement_id+" удален");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

