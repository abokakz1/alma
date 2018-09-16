@extends('admin.layout')

@section('content')
    <style>
        select,input{
            margin-bottom: 0 !important;
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
                    <form id="myform" enctype="multipart/form-data" method="post" action="/admin/employer-edit/{{$row->id}}">
                        <div style="float: left; width: 900px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{$row->id}}">

                            <div class="left-block">
                                <p>Имя</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="name" value="{{$row->name}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Позиция (Каз)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="position_kz" value="{{$row->position_kz}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Позиция (Рус)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="position_ru" value="{{$row->position_ru}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Фото</p>
                            </div>
                            <div class="right-block">
                                <input class="upl-file" id="fileupload" type="file" name="image"> 
                                <span style="color: red;">150x150 картинка</span>
                            </div>
                            <div class="clearfloat"></div>


                            <div class="left-block">
                                <p>Описание (Каз)</p>
                            </div>
                            <div class="right-block">
                                <textarea name="description_kz" style="min-height: 100px;min-width: 400px">{{$row->description_kz}}</textarea>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Описание (Рус)</p>
                            </div>
                            <div class="right-block">
                                <textarea name="description_ru" style="min-height: 100px;min-width: 400px">{{$row->description_ru}}</textarea>
                            </div>
                            <div class="clearfloat">

                            <div class="left-block">
                                <p>Порядок</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="order" value="{{$row->order}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Email</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="mail" value="{{$row->mail}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Номер</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="number" value="{{$row->number}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Меню</p>
                            </div>
                            <div class="right-block tag-select-block" style="width: 535px;">
                            <div class="tag-id-select" style="margin-bottom: 5px;">
                                <select name="menu_item_id" style="float: left; width: 178px">
                                    <option value="">Выберите меню</option>
                                    @if(count($items) > 0)
                                        @foreach($items as $key => $item)
                                        <option value="{{$item->id}}" @if($item->id == $row->menu_item_id) selected @endif>{{$item->name_ru}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="clearfloat"></div>
                            </div>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Facebook ссылка</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="fb" value="{{$row->fb}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Instagram ссылка</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="insta" value="{{$row->insta}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Telegram ссылка</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="telegram" value="{{$row->telegram}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Vkontakte ссылка</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="vk" value="{{$row->vk}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Youtube ссылка</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="youtube" value="{{$row->youtube}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div style="margin-top: 10px; float: right;">
                                <input type="submit" class="btn btn-primary" value="Сохранить">
                            </div>
                            <div class="clearfloat"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(function($) {
            $(".datepicker").datepicker("setDate", new Date());
        });
    </script>

    <script>
        function addNewTag(){
            a = $(".clone-tag-select").clone();
            a.removeClass('clone-tag-select');
            a.addClass('tag-id-select');
            a.css("display","block");
            $(".tag-select-block").append(a);
            return;
        }

        function deleteTag(blog_tag_id,ob){
            $(ob).closest(".tag-id-select").remove();

            if(blog_tag_id > 0){
                $.ajax({
                    type: 'GET',
                    url: "/admin/delete-blog-tag",
                    data: {_token: CSRF_TOKEN, id: blog_tag_id},
                    success: function(data){
                        if(data.result == false){
                            alert("Ошибка при удалении записи");
                        }
                        else{
                            showInfo("Тэг удален");
                        }
                    }
                });
            }
        }

        function addNewUserTag(){
            if($("#add_new_tag").val().length > 0) {
                $.ajax({
                    type: 'GET',
                    url: "/admin/add-new-user-tag",
                    data: {_token: CSRF_TOKEN, tag_name: $("#add_new_tag").val()},
                    success: function (data) {
                        if (data.result == false) {
                            alert("Ошибка при добавлении нового тэга");
                        }
                        else {
                            $("#add_new_tag").val("");
                            $(".tag-select-block").append(data);
                        }
                    }
                });
            }
        }
    </script>
@endsection
