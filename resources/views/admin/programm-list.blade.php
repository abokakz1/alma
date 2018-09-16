@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <a href="/admin/programm-edit/0">
                <input type="button" class="btn btn-primary" value="Добавить программу" style="margin: 0px 0px 15px 0px;">
            </a>

            <form method="post" id="myform" action="/admin/programm-list">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="left-block" style="width: 100px;">
                    <p>Язык телепроекта</p>
                </div>
                <div class="right-block">
                    <select name="programm_lang_id">
                        <option value="">Выберите язык</option>
                        <option value="1" @if($programm_lang_id == 1) selected @endif>Казахский</option>
                        <option value="2" @if($programm_lang_id == 2) selected @endif>Русский</option>
                    </select>
                </div>
                <div class="clearfloat"></div>

                <div class="left-block" style="width: 100px;">
                    <p>Наименование</p>
                </div>
                <div class="right-block">
                    <input type="text" value="@if(strlen($name) > 0){{$name}}@endif" name="name">
                </div>
                <div class="clearfloat"></div>

                <div class="left-block">
                    <p>Категория программы</p>
                </div>
                <div class="right-block">
                    <?  use App\Models\Category;
                        $category_list = Category::all();

                    ?>
                    <select name="category_id">
                        <option value="0">Выберите категорию</option>
                        @if(count($category_list) > 0)
                            @foreach($category_list as $key => $category_item)
                                <option value="{{$category_item->category_id}}" @if($category_item->category_id == $category_id) selected @endif>{{$category_item->category_name_ru}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="clearfloat"></div>

                <div class="left-block" style="width: 100px !important;">
                    <input type="submit" class="btn btn-primary" value="Поиск" style="line-height: normal; padding: 2px 15px;">
                </div>

                <div class="clearfloat"></div>
            </form>
            <div class="clearfloat"></div>
            <input type="button" class="btn btn-primary" value="Удалить отмеченные" style="line-height: normal; padding: 2px 15px; float: right; margin-bottom: 10px; margin-top: 0px;" onclick="deleteSelectedRows('programm-list')">
            <div class="clearfloat"></div>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 50px;"><input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addAllToArray(this)">ID</th>
                        <th class="center" style="width: 400px;">Заголовок</th>
                        <th class="center" style="width: 50px;">Картинка</th>
                        <th class="center" style="width: 50px;">Язык программы</th>
                        <th class="center" style="width: 100px;">Тип программы</th>
                        <th class="center" style="width: 100px;">Порядковый номер</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $programm_item)
                        <tr class="row_{{$programm_item->programm_id}}">
                            <td class="center">
                                <input type="hidden" value="{{$programm_item->programm_id}}" class="hidden-id-input">
                                <input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addToArray({{$programm_item->programm_id}},this)" class="checkbox-item-list">
                                <a href="/admin/programm-edit/{{$programm_item->programm_id}}">{{$programm_item->programm_id}}</a>
                            </td>
                            <td><a href="/admin/programm-edit/{{$programm_item->programm_id}}">{{$programm_item->programm_name_ru}}</a></td>
                            <td>{{$programm_item->image}}</td>
                            <td>@if($programm_item->pr_lang_id == 1) Казахский @elseif($programm_item->pr_lang_id == 2) Русский @endif</td>
                            <td>
                                <?
                                $programm_type_name = "";
                                if($programm_item->is_archive == 1){
                                    echo " Архив ";
                                }
                                if($programm_item->is_spec_project == 1){
                                    echo " Спецпроект ";
                                }

                                if($programm_item->is_spec_project != 1 && $programm_item->is_archive != 1){
                                    echo " Программа текущего сезона ";
                                }
                                ?>
                            </td>
                            <td>{{$programm_item->order_num}}</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0px 0px 5px 0px;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0px 20px;" onclick="deleteProgramm({{$programm_item->programm_id}},this)" value="Удалить">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>

    <script>
        function deleteProgramm(programm_id,ob){
            if (!confirm('Вы действительно хотите удалить программу №' + programm_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-programm",
                data: {_token: CSRF_TOKEN, programm_id: programm_id},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при удалении программы");
                    }
                    else{
                        showInfo("Программа #"+programm_id+" удалена");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

