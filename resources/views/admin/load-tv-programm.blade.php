@if(count($row) > 0)
    @foreach($row as $key => $tv_programm_item)
        <tr class="row_{{$tv_programm_item->tv_programm_id}}">
            <td class="center">
                <input type="hidden" value="{{$tv_programm_item->tv_programm_id}}" class="hidden-id-input">
                <input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addToArray({{$tv_programm_item->tv_programm_id}},this)" class="checkbox-item-list">
                {{$tv_programm_item->time}} - {{$tv_programm_item->time_end}}
            </td>
            <td><a href="/admin/tv-programm-edit/{{$tv_programm_item->tv_programm_id}}">{{$tv_programm_item->tv_programm_name_ru}}</a></td>
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