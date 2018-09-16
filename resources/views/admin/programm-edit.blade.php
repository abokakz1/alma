@extends('admin.layout')

@section('content')
    <style>
        select,input{
            margin-bottom: 0px !important;
        }
        .right-block{
            width: auto;
            text-align: center;
        }
    </style>
    <div class="row-fluid">
        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                    <a data-toggle="tab" href="#main_data">
                        <i class="green icon-home bigger-110"></i>
                        Основные данные
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane in active" id="main_data">
                    @if(isset($result['status']))
                        <p style="color: red; font-size: 14px; text-align: center;">
                            @if(count($result['value']) > 0)
                                @foreach($result['value'] as $key => $error_item)
                                    {{ $error_item }} <br>
                                @endforeach
                            @endif
                        </p>
                    @endif
                    <form id="myform" enctype="multipart/form-data" method="post" action="/admin/programm-edit/{{$row->programm_id}}" style="margin-bottom: 0px;">
                        <div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="programm_id" value="{{$row->programm_id}}">

                            <div style="float: left;">
                                <div class="left-block" style="display: none;">
                                    <p>Наименование (Каз)</p>
                                </div>
                                <div class="right-block" style="display: none;">
                                    <input type="text" name="programm_name_kz" value="{{$row->programm_name_kz}}">
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Наименование (Рус)</p>
                                </div>
                                <div class="right-block">
                                    <input type="text" name="programm_name_ru" value="{{$row->programm_name_ru}}">
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block" style="display: none;">
                                    <p>Наименование (Анг)</p>
                                </div>
                                <div class="right-block" style="display: none;">
                                    <input type="text" name="programm_name_en" value="{{$row->programm_name_en}}">
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Порядковый номер</p>
                                </div>
                                <div class="right-block">
                                    <input type="text" name="order_num" value="{{$row->order_num}}">
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Лого программы</p>
                                </div>
                                <div class="right-block">
                                    <div style="text-align: left;">
                                        @if(strlen($row->programm_logo) > 0)
                                            <img src="/programm_photo/{{$row->programm_logo}}" width="100" height="100" id="programm_logo">
                                        @else
                                            <img src="/css/image/no_photo.png" width="100" height="100" id="programm_logo">
                                        @endif
                                    </div>
                                    <div style="text-align: left; margin: 10px 0px 0px 20px;">
                                        <input id="fileupload2" type="file" name="programm_logo"><br>
                                        <p style="font-size: 14px; color: red;">Лого должно быть размером 130х130 и формат png с прозрачным фоном</p>
                                        <input type="button" class="btn btn-small btn-primary" value="Удалить лого" onclick="deleteProgrammLogo({{$row->programm_id}})" style="width: auto;">
                                    </div>
                                </div>
                                <div class="clearfloat"></div>

                                <script>
                                    function deleteProgrammLogo(programm_id){
                                        if(programm_id > 0){
                                            $.ajax({
                                                type: 'GET',
                                                url: "/admin/delete-programm-logo",
                                                data: {_token: CSRF_TOKEN, programm_id: programm_id},
                                                success: function(data){
                                                    if(data.result == false){
                                                        alert("Ошибка при удалении лого программы");
                                                    }
                                                    else{
                                                        $("#programm_logo").attr("src","/css/image/no_photo.png");
                                                        showInfo("Лого программы удалено");
                                                    }
                                                }
                                            });
                                        }
                                    }
                                </script>

                                <div class="left-block">
                                    <p>Проект в архиве</p>
                                </div>
                                <div class="right-block">
                                    <input type="hidden" name="is_archive" value="{{$row->is_archive}}" class="hidden-checkbox-value">
                                    <label style="margin-top: 5px;">
                                        <?php $checked1 = " "; ?>
                                        @if($row->is_archive == 1)
                                            <?php $checked1 = " checked"; ?>
                                        @endif
                                        <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_archive}}" onchange="changeCheckboxValue(this)"/>
                                        <span class="lbl"></span>
                                    </label>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Спецпроект</p>
                                </div>
                                <div class="right-block">
                                    <input type="hidden" name="is_spec_project" value="{{$row->is_spec_project}}" class="hidden-checkbox-value">
                                    <label style="margin-top: 5px;">
                                        <?php $checked1 = " "; ?>
                                        @if($row->is_spec_project == 1)
                                            <?php $checked1 = " checked"; ?>
                                        @endif
                                        <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_spec_project}}" onchange="changeCheckboxValue(this)"/>
                                        <span class="lbl"></span>
                                    </label>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Категория программы</p>
                                </div>
                                <div class="right-block">
                                    <select name="category_id">
                                        <option value="0">Выберите категорию</option>
                                        @if(count($category_list) > 0)
                                            @foreach($category_list as $key => $category_item)
                                                <option value="{{$category_item->category_id}}" @if($category_item->category_id == $row->category_id) selected @endif>{{$category_item->category_name_ru}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Язык телепроекта</p>
                                </div>
                                <div class="right-block">
                                    <select name="pr_lang_id">
                                        <option value="0">Выберите язык</option>
                                        <option value="1" @if($row->pr_lang_id == 1) selected @endif>Казахский</option>
                                        <option value="2" @if($row->pr_lang_id == 2) selected @endif>Русский</option>
                                    </select>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Тип стиля как <a href="/programms">по ссылке</a></p>
                                </div>
                                <div class="right-block">
                                    <select name="type">
                                        <option value=0 @if(!$row->type) selected @endif>Обычный</option>
                                        <option value="wakeup" @if($row->type == "wakeup") selected @endif>WakeUp</option>
                                        <option value="italic" @if($row->type == "italic") selected @endif>Italic</option>
                                        <option value="underline" @if($row->type == "underline") selected @endif>Underline</option>
                                        <option value="bold" @if($row->type == "bold") selected @endif>Bold</option>
                                    </select>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Описание программы (Каз)</p>
                                </div>
                                <div class="right-block">
                                    <textarea id="editor1" name="programm_description_kz">{{$row->programm_description_kz}}</textarea>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Описание программы (Рус)</p>
                                </div>
                                <div class="right-block">
                                    <textarea id="editor2" name="programm_description_ru">{{$row->programm_description_ru}}</textarea>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block" style="display: none;">
                                    <p>Описание программы (Анг)</p>
                                </div>
                                <div class="right-block" style="display: none;">
                                    <textarea id="editor3" name="programm_description_en">{{$row->programm_description_en}}</textarea>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Регулярность программы</p>
                                </div>
                                <div class="right-block programm-time-block">
                                    <i class="icon-add" style="float: left; margin-top: 8px; margin-bottom: 10px;" onclick="addNewProgrammTime()"></i>
                                    <div class="clearfloat"></div>
                                    <?php use App\Models\ProgrammTime;
                                        $programm_time_row = ProgrammTime::where("programm_id","=",$row->programm_id)->get();
                                    ?>
                                    @if(count($programm_time_row) > 0)
                                        @foreach($programm_time_row as $key => $programm_time)
                                            <div class="programm-time-item-block" style="margin-bottom: 10px;">
                                                <select name="day_id[{{$programm_time['programm_time_id']}}]" style="float: left; margin-right: 20px;">
                                                    <option value="0">Выберите день</option>
                                                    <option value="1" @if($programm_time['day_id'] == 1) selected @endif>Понедельник</option>
                                                    <option value="2" @if($programm_time['day_id'] == 2) selected @endif>Вторник</option>
                                                    <option value="3" @if($programm_time['day_id'] == 3) selected @endif>Среда</option>
                                                    <option value="4" @if($programm_time['day_id'] == 4) selected @endif>Четверг</option>
                                                    <option value="5" @if($programm_time['day_id'] == 5) selected @endif>Пятница</option>
                                                    <option value="6" @if($programm_time['day_id'] == 6) selected @endif>Суббота</option>
                                                    <option value="7" @if($programm_time['day_id'] == 7) selected @endif>Воскресенье</option>
                                                </select>
                                                <input class="programm-time-input" type="text" style="float: left; width: 100px; text-align: center;" name="time[{{$programm_time['programm_time_id']}}]" value="{{$programm_time['time']}}">
                                                <i class="icon-remove red" onclick="deleteProgrammTime({{$programm_time['programm_time_id']}},this)" style="float: left; margin-top: 8px; margin-left: 10px;"></i>
                                                <div class="clearfloat"></div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="clearfloat"></div>
                            </div>

                            <div style="float: left; margin-left: 100px;">
                                <div style="text-align: center;">
                                    @if(strlen($row->image) > 0)
                                        <img src="/programm_photo/{{$row->image}}" width="150px" height="150px" id="programm_image">
                                    @else
                                        <img src="/css/image/no_photo.png" width="150px" height="150px" id="programm_image">
                                    @endif
                                </div>
                                <div style="text-align: center; margin: 10px 0px 0px 20px;">
                                    <input id="fileupload" type="file" name="image"><br>
                                    <p style="font-size: 14px; color: red;">Рекомендуемый размер картинки: 1920х340</p>
                                </div>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>На главную страницу</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_main" value="{{$row->is_main}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; $show="none" ?>
                                    @if($row->is_main == 1)
                                        <?php $checked1 = " checked"; $show="block" ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_main}}" onchange="changeMainCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>
                            
                            <div id="additional_info"  style="display: <?php echo $show?>;padding: 10px 50px;background-color: pink;">
                                <div class="left-block">
                                    <p>Время</p>
                                </div>
                                <div class="right-block">
                                    <input class="programm-time-input" type="text" style="float: left; width: 100px; text-align: center;" name="time" value="{{$row->time}}">
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Дни недели (Каз)</p>
                                </div>
                                <div class="right-block">
                                    <input type="text" name="day_week_kz" value="{{$row->day_week_kz}}">
                                </div>
                                <div class="clearfloat"></div>
                                
                                <div class="left-block">
                                    <p>Дни недели (Рус)</p>
                                </div>
                                <div class="right-block">
                                    <input type="text" name="day_week_ru" value="{{$row->day_week_ru}}">
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Порядковый номер на главном</p>
                                </div>
                                <div class="right-block">
                                    <input type="text" name="main_order_num" value="{{$row->main_order_num}}">
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Показать ссылку</p>
                                </div>
                                <div class="right-block">
                                    <input type="hidden" name="show_link" value="{{$row->show_link}}" class="hidden-checkbox-value">
                                    <label style="margin-top: 5px;">
                                        <?php $checked_l = " "; ?>
                                        @if($row->show_link == 1)
                                            <?php $checked_l = " checked"; ?>
                                        @endif
                                        <input class="ace ace-switch ace-switch-5 <?php echo $checked_l?>" type="checkbox" <?php echo $checked_l ?> value="{{$row->show_link}}" onchange="changeCheckboxValue(this)"/>
                                        <span class="lbl"></span>
                                    </label>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Фото на главном</p>
                                </div>
                                <div class="right-block">
                                    <div style="text-align: left;">
                                        @if(strlen($row->main_image) > 0)
                                            <img src="/programm_photo/{{$row->main_image}}" width="100" height="100">
                                        @else
                                            <img src="/css/image/no_photo.png" width="100" height="100">
                                        @endif
                                    </div>
                                    <div style="text-align: left; margin: 10px 0px 0px 20px;">
                                        <input id="fileupload3" type="file" name="main_image"><br>
                                        <p style="font-size: 14px; color: red;">Рекомендуемый размер картинки: 570х400</p>
                                    </div>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Описание программы на главном <b>(Каз)</b></p>
                                </div>
                                <div class="right-block">
                                    <textarea name="main_description_kz" style="height: 120px;width: 400px;">{{$row->main_description_kz}}</textarea>
                                    <div style="float: right;margin-left: 10px;">
	                                    <div>
	                                    	<label>Ссылка на Видеоархив(Каз):</label>
	    									<input type="text" placeholder="Ссылка" name="link_videoarchive_kz" value="{{$row->link_videoarchive_kz}}">
	    								</div>
	    								<div>
	                                    	<label>Ссылка на Программу(Каз):</label>
	    									<input type="text" placeholder="Ссылка" name="link_programm_kz" value="{{$row->link_programm_kz}}">
	    								</div>
	    							</div>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Описание программы на главном <b>(Рус)</b></p>
                                </div>
                                <div class="right-block">
                                    <textarea name="main_description_ru" style="height: 120px;width: 400px;">{{$row->main_description_ru}}</textarea>
                                    <div style="float: right;margin-left: 10px;">
                                    	<div>
	                                    	<label>Ссылка на Видеоархив(Рус):</label>
	    									<input type="text" placeholder="Ссылка" name="link_videoarchive_ru" value="{{$row->link_videoarchive_ru}}">
    									</div>
	                                    <div>
	                                    	<label>Ссылка на Программу(Рус):</label>
	    									<input type="text" placeholder="Ссылка" name="link_programm_ru" value="{{$row->link_programm_ru}}">
	    								</div>
	    							</div>
                                </div>
                                <div class="clearfloat"></div>

                            </div>

                            @if($row->programm_id > 0)
                                <div style="margin-top: 10px; float: right;">
                                    <input type="submit" class="btn btn-primary" value="Сохранить">
                                </div>
                            @else
                                <div style="margin-top: 10px; float: right;">
                                    <input type="submit" class="btn btn-primary" value="Добавить">
                                </div>
                            @endif
                            <div class="clearfloat"></div>
                        </div>

                        <div class="clearfloat"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="programm-time-item-block clone-programm-time-item-block" style="display: none; margin-bottom: 10px;">
        <select name="day_id_new[]" style="float: left; margin-right: 20px;">
            <option value="0">Выберите день</option>
            <option value="1">Понедельник</option>
            <option value="2">Вторник</option>
            <option value="3">Среда</option>
            <option value="4">Четверг</option>
            <option value="5">Пятница</option>
            <option value="6">Суббота</option>
            <option value="7">Воскресенье</option>
        </select>
        <input type="text" class="programm-time-input" style="float: left; width: 100px; text-align: center;" name="time_new[]" value="">
        <i class="icon-remove red" onclick="deleteProgrammTime(0,this)" style="float: left; margin-top: 8px; margin-left: 10px;"></i>
        <div class="clearfloat"></div>
    </div>

    <script>
        $(document).ready(function() {
            $(".programm-time-input").mask("99:99");
        });
        function addNewProgrammTime(){
            a = $(".clone-programm-time-item-block").clone();
            a.removeClass("clone-programm-time-item-block");
            a.css("display","block");
            a.find(".programm-time-input").mask("99:99");
            $(".programm-time-block").append(a);
        }

        function deleteProgrammTime(programm_time_id,ob){
            $(ob).closest("div").remove();
            if(programm_time_id > 0){
                $.ajax({
                    type: 'GET',
                    url: "/admin/delete-programm-time",
                    data: {_token: CSRF_TOKEN, programm_time_id: programm_time_id},
                    success: function(data){
                        if(data.result == false){
                            alert("Ошибка при удалении строки");
                        }
                        else{
                            showInfo("Запись #" + programm_time_id + " удалена");
                        }
                    }
                });
            }
        }

        function changeMainCheckboxValue(ob){
            if($(ob).is(":checked")){
                $(ob).closest(".right-block").find(".hidden-checkbox-value").attr("value",1);
                $('#additional_info').show(500);
            }
            else{
                $(ob).closest(".right-block").find(".hidden-checkbox-value").attr("value",0);
                $('#additional_info').hide(500);
            }
        }
    </script>
@endsection
