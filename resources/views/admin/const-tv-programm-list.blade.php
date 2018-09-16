@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <a href="/admin/const-tv-programm-edit/0">
                <input type="button" class="btn btn-primary" value="Добавить программу" style="margin: 0px 0px 15px 0px;">
            </a>
            <div class="clearfloat"></div>
            <input type="button" class="btn btn-primary" value="Удалить отмеченные" style="line-height: normal; padding: 2px 15px; float: right; margin-bottom: 10px; margin-top: 0px;" onclick="deleteSelectedRows('const-tv-programm-list')">
            <div class="clearfloat"></div>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 30px;"><input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addAllToArray(this)">Время (начало - конец)</th>
                        <th class="center" style="width: 250px;">Название программы</th>
                        <th class="center" style="width: 150px;">Наличие на телепроекта</th>
                        <th class="center" style="width: 250px;">Короткое описание</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $tv_programm_item)
                        <tr class="row_{{$tv_programm_item->tv_programm_id}}">
                            <td class="center">
                                <input type="hidden" value="{{$tv_programm_item->tv_programm_id}}" class="hidden-id-input">
                                <input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addToArray({{$tv_programm_item->tv_programm_id}},this)" class="checkbox-item-list">
                                {{$tv_programm_item->time}} - {{$tv_programm_item->time_end}}
                            </td>
                            <td><a href="/admin/const-tv-programm-edit/{{$tv_programm_item->tv_programm_id}}">{{$tv_programm_item->tv_programm_id}}. {{$tv_programm_item->tv_programm_name_ru}}</a></td>
                            <td>
                                @if($tv_programm_item->tv_programm_programm_id > 0)
                                    Да
                                @else
                                    Нет
                                @endif
                            </td>
                            <td>{{$tv_programm_item->tv_programm_short_description_ru}}</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0px 0px 5px 0px;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0px 20px;" onclick="deleteTVProgramm({{$tv_programm_item->tv_programm_id}},this)" value="Удалить">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>

    <script>
        function deleteTVProgramm(tv_programm_id,ob){
            if (!confirm('Вы действительно хотите удалить ТВ программу №' + tv_programm_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-const-tv-programm",
                data: {_token: CSRF_TOKEN, tv_programm_id: tv_programm_id},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при удалении ТВ программы");
                    }
                    else{
                        showInfo("ТВ программа #" + tv_programm_id + " удалена");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

