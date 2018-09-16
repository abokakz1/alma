@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <a href="/admin/vacancy-edit/0">
                <input type="button" class="btn btn-primary" value="Добавить вакансию" style="margin: 0px 0px 15px 0px;">
            </a>
            <div class="clearfloat"></div>
            <input type="button" class="btn btn-primary" value="Удалить отмеченные" style="line-height: normal; padding: 2px 15px; float: right; margin-bottom: 10px; margin-top: 0px;" onclick="deleteSelectedRows('vacancy-list')">
            <div class="clearfloat"></div>


            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 50px;"><input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addAllToArray(this)">ID</th>
                        <th class="center" style="width: 250px;">Должность (Рус)</th>
                        <th class="center" style="width: 250px;">Описание (Рус)</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $vacancy_item)
                        <tr class="row_{{$vacancy_item->vacancy_id}}">
                            <td class="center">
                                <input type="hidden" value="{{$vacancy_item->vacancy_id}}" class="hidden-id-input">
                                <input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addToArray({{$vacancy_item->vacancy_id}},this)" class="checkbox-item-list">
                                <a href="/admin/vacancy-edit/{{$vacancy_item->vacancy_id}}">{{$vacancy_item->vacancy_id}}</a>
                            </td>
                            <td><a href="/admin/vacancy-edit/{{$vacancy_item->vacancy_id}}">{{$vacancy_item->vacancy_position_name_ru}}</a></td>
                            <td><?php echo $vacancy_item->vacancy_description_ru; ?></td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0px 0px 5px 0px;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0px 20px;" onclick="deleteVacancy({{$vacancy_item->vacancy_id}},this)" value="Удалить">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>

    <script>
        function deleteVacancy(vacancy_id,ob){
            if (!confirm('Вы действительно хотите удалить вакансию №' + vacancy_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-vacancy",
                data: {_token: CSRF_TOKEN, vacancy_id: vacancy_id},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при удалении вакансии");
                    }
                    else{
                        showInfo("Вакансия #" + vacancy_id + " удалена");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

