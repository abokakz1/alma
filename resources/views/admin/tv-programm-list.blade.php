@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <a href="/admin/tv-programm-edit/0">
                <input type="button" class="btn btn-primary" value="Добавить программу" style="margin: 0px 0px 15px 0px;">
            </a>
            <hr style="margin-top: 0px;">

            <form method="post" id="myform" action="/admin/tv-programm-list">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="left-block" style="width: 50px !important;">
                    <p>Дата</p>
                </div>
                <div class="right-block" style="width: 150px;">
                    <div class="control-group">
                        <div class="row-fluid input-prepend">
                            <span class="add-on">
                                <i class="icon-calendar"></i>
                            </span>
                            <input type="text" name="date" value="{{date("d.m.Y",strtotime($allparam['date']))}}" class="datepicker selected-date" style="width: 95px; text-align: center;">
                        </div>
                    </div>
                </div>

                <div class="left-block" style="width: 100px !important;">
                    <input type="submit" class="btn btn-primary" value="Поиск" style="line-height: normal; padding: 2px 15px;">
                </div>

                <div class="clearfloat"></div>
            </form>

            <div>
                <p style="margin: 0px; font-size: 14px; font-weight: bold;">Добавление постоянной ТВ программы:</p>
                <div class="left-block">
                    <p>ТВ программа</p>
                </div>
                <div class="right-block">
                    <? use App\Models\ConstTVProgramm;
                        $const_tv_programm_list = ConstTVProgramm::orderBy("time","asc")->get();
                    ?>
                    <select name="tv_programm_id" id="const_tv_programm_id">
                        <option value="0">Выберите ТВ программу</option>
                        @if(count($const_tv_programm_list) > 0)
                            @foreach($const_tv_programm_list as $key => $const_tv_programm_item)
                                <option value="{{$const_tv_programm_item['tv_programm_id']}}">{{substr($const_tv_programm_item['time'],0,5)}} - {{$const_tv_programm_item['tv_programm_name_ru']}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="left-block" style="width: 100px !important;">
                    <input type="submit" class="btn btn-primary" value="Добавить" style="line-height: normal; padding: 2px 15px;" onclick="addConstTvProgramm()">
                </div>
                <div class="clearfloat"></div>

                <script>
                    function addConstTvProgramm(){
                        val = $("#const_tv_programm_id").val();
                        date = $(".selected-date").val();
                        if(val > 0 && date.length > 0){
                            $.ajax({
                                type: 'GET',
                                url: "/admin/add-const-tv-programm",
                                data: {_token: CSRF_TOKEN, tv_programm_id: val, date: date},
                                success: function(data){
                                    if(data.result == false){
                                        alert("Ошибка при добавлении ТВ программы");
                                    }
                                    else{
                                        showInfo("ТВ программа добавлена");
                                        $("#const_tv_programm_id").val(0);
                                        $("#sample-table-1").find("tbody").load("/admin/load-tv-programm/" + $(".selected-date").val());
                                    }
                                }
                            });
                        }
                        else{
                            showError("Дата или ТВ программа не выбрана");
                        }
                    }

                    function addExpressTvProgramm(){
                        date = $(".selected-date").val();
                        if(date.length > 0){
                            $("#form_date").val(date);
                            $.ajax({
                                type: 'POST',
                                url: "/admin/add-express-tv-programm",
                                data: $("#expressmyform").serialize(),
                                success: function(data){
                                    if(data.result == false){
                                        alert("Ошибка при добавлении ТВ программы");
                                    }
                                    else{
                                        showInfo("ТВ программа добавлена");
                                        document.getElementById("expressmyform").reset();
                                        $("#sample-table-1").find("tbody").load("/admin/load-tv-programm/" + $(".selected-date").val());
                                    }
                                }
                            });
                        }
                        else{
                            showError("Дата не выбрана");
                        }
                    }
                </script>
                <br>
                <hr style="margin-top: 0px;">
                <p style="margin: 0px; font-size: 14px; font-weight: bold;">Добавление ТВ программы:</p>
                <form id="expressmyform" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="date" id="form_date">
                    <div class="left-block" style="width: 130px">
                        <p>Наименование (каз)</p>
                    </div>
                    <div class="right-block">
                        <input type="text" value="" name="tv_programm_name_kz">
                    </div>

                    <div style="float: left; ">
                        <div class="left-block" style="width: 110px">
                            <p>Время начало</p>
                        </div>
                        <div class="right-block">
                            <div class="input-append bootstrap-timepicker" style="margin-bottom: 0px;">
                                <input type="text" class="input-small timepicker" style="width: 75px;" name="time"/>
                                            <span class="add-on">
                                                <i class="icon-time"></i>
                                            </span>
                            </div>
                        </div>
                        <div class="clearfloat"></div>
                    </div>
                    <div class="clearfloat"></div>

                    <div class="left-block" style="width: 130px">
                        <p>Наименование (рус)</p>
                    </div>
                    <div class="right-block">
                        <input type="text" value="" name="tv_programm_name_ru">
                    </div>
                    <div style="float: left; ">
                        <div class="left-block" style="width: 110px">
                            <p>Время окончания</p>
                        </div>
                        <div class="right-block">
                            <div class="input-append bootstrap-timepicker" style="margin-bottom: 0px;">
                                <input type="text" class="input-small timepicker" style="width: 75px;" name="time_end"/>
                                <span class="add-on">
                                    <i class="icon-time"></i>
                                </span>
                            </div>
                        </div>
                        <div class="clearfloat"></div>
                    </div>
                    <div class="clearfloat"></div>

                    <div class="left-block" style="width: 130px">
                        <p>Категория программы</p>
                    </div>
                    <div class="right-block">
                        <?php use App\Models\Category;
                        $category_list = Category::all();
                        ?>
                        <select name="category_id">
                            <option value="0">Выберите категорию</option>
                            @if(count($category_list) > 0)
                                @foreach($category_list as $key => $category_item)
                                    <option value="{{$category_item->category_id}}">{{$category_item->category_name_ru}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="left-block" style="width: 110px">
                        <p>Телепроект</p>
                    </div>
                    <div class="right-block">
                        <?php use App\Models\Programm;
                        $programm_list = Programm::all();
                        ?>
                        <select name="tv_programm_programm_id">
                            <option value="0">Выберите телепроект</option>
                            @if(count($programm_list) > 0)
                                @foreach($programm_list as $key => $programm_item)
                                    <option value="{{$programm_item->programm_id}}">{{$programm_item->programm_name_ru}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="clearfloat"></div>

                    <div class="left-block" style="width: 100px !important;">
                        <input type="button" class="btn btn-primary" value="Добавить" style="line-height: normal; padding: 2px 15px;" onclick="addExpressTvProgramm()">
                    </div>
                    <div class="clearfloat"></div><br>
                </form>

                <hr style="margin-top: 0px;">

                <form id="tv_programm_copy_form" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="left-block" style="width: 50px !important;">
                        <p>Дата c</p>
                    </div>
                    <div class="right-block" style="width: 150px;">
                        <div class="control-group">
                            <div class="row-fluid input-prepend">
                            <span class="add-on">
                                <i class="icon-calendar"></i>
                            </span>
                                <input type="text" name="date_from" value="" class="datepicker date_from_copy" style="width: 95px; text-align: center;">
                            </div>
                        </div>
                    </div>

                    <div class="left-block" style="width: 50px !important;">
                        <p>Дата на</p>
                    </div>
                    <div class="right-block" style="width: 150px;">
                        <div class="control-group">
                            <div class="row-fluid input-prepend">
                            <span class="add-on">
                                <i class="icon-calendar"></i>
                            </span>
                                <input type="text" name="date_to" value="{{date("d.m.Y")}}" class="datepicker date_to_copy" style="width: 95px; text-align: center;">
                            </div>
                        </div>
                    </div>

                    <div class="left-block" style="width: 100px !important;">
                        <input type="button" class="btn btn-primary" value="Копировать" style="line-height: normal; padding: 2px 15px;" onclick="copyTvProgramm()">
                    </div>

                    <div class="clearfloat"></div>
                </form>
                <div class="clearfloat"></div>
                <input type="button" class="btn btn-primary" value="Удалить отмеченные" style="line-height: normal; padding: 2px 15px; float: right; margin-bottom: 10px; margin-top: 0px;" onclick="deleteSelectedRows('tv-programm-list')">
                <div class="clearfloat"></div>

                <script>
                    function copyTvProgramm(){
                        date_from  = $(".date_from_copy").val();
                        date_to = $(".date_to_copy").val();
                        if(date_from.length > 0 && date_to.length > 0){
                            $.ajax({
                                type: 'POST',
                                url: "/admin/copy-tv-programm",
                                data: $("#tv_programm_copy_form").serialize(),
                                success: function(data){
                                    if(data.result == false){
                                        alert("Ошибка при копировании ТВ программы");
                                    }
                                    else{
                                        showInfo("ТВ программа скопирована");
                                        document.getElementById("tv_programm_copy_form").reset();
                                        $("#sample-table-1").find("tbody").load("/admin/load-tv-programm/" + date_to);
                                    }
                                }
                            });
                        }
                        else{
                            showError("Дата не выбрана");
                        }
                    }
                </script>
            </div>

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

                <tbody>

                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function(){
           $("#sample-table-1").find("tbody").load("/admin/load-tv-programm/" + $(".selected-date").val());
        });
        function deleteTVProgramm(tv_programm_id,ob){
            if (!confirm('Вы действительно хотите удалить ТВ программу №' + tv_programm_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-tv-programm",
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

