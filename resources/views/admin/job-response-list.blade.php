@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <form method="post" id="myform" action="/admin/job-response-list">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="left-block" style="width: 80px !important;">
                    <p>Вакансия</p>
                </div>
                <div class="right-block">
                    <select name="vacancy_id">
                        <option value="0">Выберите вакансию</option>
                    <?php use App\Models\Vacancy;
                         $vacancy_list = Vacancy::all();
                    ?>
                         @if(count($vacancy_list) > 0)
                             @foreach($vacancy_list as $key => $vacancy_item)
                                 <option value="{{$vacancy_item['vacancy_id']}}" @if($vacancy_item['vacancy_id'] == $allparam['vacancy_id']) selected @endif>{{$vacancy_item['vacancy_position_name_ru']}}</option>
                             @endforeach
                         @endif
                    </select>
                </div>

                <div class="left-block" style="width: 100px !important;">
                    <input type="submit" class="btn btn-primary" value="Поиск" style="line-height: normal; padding: 2px 15px;">
                </div>

                <div class="clearfloat"></div>
            </form>
            <div class="clearfloat"></div>
            <input type="button" class="btn btn-primary" value="Удалить отмеченные" style="line-height: normal; padding: 2px 15px; float: right; margin-bottom: 10px; margin-top: 0px;" onclick="deleteSelectedRows('job-response-list')">
            <div class="clearfloat"></div>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 50px;"><input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addAllToArray(this)">ID</th>
                        <th class="center" style="width: 50px;">Дата</th>
                        <th class="center" style="width: 50px;">Файл</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $job_response_item)
                        <tr class="row_{{$job_response_item->job_response_id}}">
                            <td class="center">
                                <input type="hidden" value="{{$job_response_item->job_response_id}}" class="hidden-id-input">
                                <input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addToArray({{$job_response_item->job_response_id}},this)"  class="checkbox-item-list">
                                <a>{{$job_response_item->job_response_id}}</a>
                            </td>
                            <td class="center">
                                {{$job_response_item->job_response_date}}
                            </td>
                            <td>
                                <a href="/response_files/{{$job_response_item->filename}}" target="_blank">
                                    {{$job_response_item->filename}}
                                </a>
                            </td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0px 0px 5px 0px;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0px 20px;" onclick="deleteJobResponse({{$job_response_item->job_response_id}},this)" value="Удалить">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>

    <script>
        function deleteJobResponse(job_response_id,ob){
            if (!confirm('Вы действительно хотите удалить отклик №' + job_response_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-job-response",
                data: {_token: CSRF_TOKEN, job_response_id: job_response_id},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при удалении отклика");
                    }
                    else{
                        showInfo("Отклик #"+job_response_id+" удален");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

