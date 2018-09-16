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
                    <p style="font-size: 14px;">Выберите язык, на котором вы пишете пост. Можно написать пост на двух языках. Тогда после одобрения модератора он появится в двух языковых версиях сайта. Можно выбрать также только один язык</p>
                    <form id="myform" enctype="multipart/form-data" method="post" action="/admin/blog-edit/{{$row->blog_id}}">
                        <div style="float: left; width: 900px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="blog_id" value="{{$row->blog_id}}">

                            <div class="left-block">
                                <p>Заголовок (Каз)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="blog_title_kz" value="{{$row->blog_title_kz}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Заголовок (Рус)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="blog_title_ru" value="{{$row->blog_title_ru}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Заголовок (Анг)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="blog_title_en" value="{{$row->blog_title_en}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>
                                    Картинка
                                </p>
                            </div>
                            <div class="right-block">
                                <input class="upl-file" id="fileupload" type="file" name="image"> 
                                <!-- if (! row->image) required  endif -->
                            </div>
                            <div class="clearfloat"></div>


                            <div class="left-block">
                                <p>Текст блога (Каз)</p>
                            </div>
                            <div class="right-block">
                                <textarea id="editor1" name="blog_text_kz">{{$row->blog_text_kz}}</textarea>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Текст блога (Рус)</p>
                            </div>
                            <div class="right-block">
                                <textarea id="editor2" name="blog_text_ru">{{$row->blog_text_ru}}</textarea>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Текст блога (Анг)</p>
                            </div>
                            <div class="right-block">
                                <textarea id="editor3" name="blog_text_en">{{$row->blog_text_en}}</textarea>
                            </div>
                            <div class="clearfloat"></div>

                            <div style="float: left; margin-top: 10px;">
                                <div class="left-block" style="width: 55px;">
                                    <p>Дата</p>
                                </div>
                                <div class="right-block">
                                    @if(strlen($row->datetime) > 0)
                                        <input type="text" name="date" value="{{substr($row->datetime,0,10)}}" class="datepicker" style="width: 95px; text-align: center;">
                                    @else
                                        <input type="text" name="date" value="{{date("d.m.Y")}}" class="datepicker" style="width: 95px; text-align: center;">
                                    @endif
                                </div>
                                <div class="clearfloat"></div>
                            </div>

                            <div style="float: left; margin-left: 50px; margin-top: 10px;">
                                <div class="left-block" style="width: 55px;">
                                    <p>Время</p>
                                </div>
                                <div class="right-block">
                                    <div class="input-append bootstrap-timepicker" style="margin-bottom: 0px;">
                                        @if(strlen($row->datetime) > 0)
                                            <input type="text" class="input-small timepicker" style="width: 75px;" name="time" value="{{substr($row->datetime,12,8)}}"/>
                                        @else
                                            <input type="text" class="input-small timepicker" style="width: 75px;" name="time" value="{{date("H:i:s")}}"/>
                                        @endif

                                        <span class="add-on">
                                            <i class="icon-time"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="clearfloat"></div>
                            </div>
                            <div class="clearfloat"></div>

                            @if(Auth::user()->role_id == 1)
                                <div class="left-block">
                                    <p>Активный (Рус)</p>
                                </div>
                                <div class="right-block">
                                    <input type="hidden" name="is_active_ru" value="{{$row->is_active_ru}}" class="hidden-checkbox-value">
                                    <label style="margin-top: 5px;">
                                        <?php $checked1 = " "; ?>
                                        @if($row->is_active_ru == 1)
                                            <?php $checked1 = " checked"; ?>
                                        @endif
                                        <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_active_ru}}" onchange="changeCheckboxValue(this)"/>
                                        <span class="lbl"></span>
                                    </label>
                                </div>
                                <div class="clearfloat"></div>

                                <div class="left-block">
                                    <p>Активный (Каз)</p>
                                </div>
                                <div class="right-block">
                                    <input type="hidden" name="is_active_kz" value="{{$row->is_active_kz}}" class="hidden-checkbox-value">
                                    <label style="margin-top: 5px;">
                                        <?php $checked1 = " "; ?>
                                        @if($row->is_active_kz == 1)
                                            <?php $checked1 = " checked"; ?>
                                        @endif
                                        <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_active_kz}}" onchange="changeCheckboxValue(this)"/>
                                        <span class="lbl"></span>
                                    </label>
                                </div>
                                <div class="clearfloat"></div>
                            

                            
                            <div class="left-block">
                                <p>Блог на слайдер</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_main_blog" value="{{$row->is_main_blog}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->is_main_blog == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_main_blog}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>
                            @endif


                            <div class="left-block">
                                <p>Наличие фото</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_has_foto" value="{{$row->is_has_foto}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px; float: left;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->is_has_foto == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_has_foto}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label><p style="margin-left: 10px;padding-top: 5px;width: 480px">Жмите, если ваш материал можно отнести к категории "Фото материал"</p>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Наличие видео</p>
                            </div>
                            <div class="right-block">
                                @if($row->blog_id < 1)
                                    <?php $row->is_has_video = 0 ?>
                                @endif
                                <input type="hidden" name="is_has_video" value="{{$row->is_has_video}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->is_has_video == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_has_video}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>

                            <div class="clearfloat"></div>


                            <div class="left-block">
                                <p>Youtube ссылка</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="video_url" value="{{$row->video_url}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Тэг поста</p>
                            </div>
                            <div class="right-block tag-select-block" style="width: 535px;">
                                <?php use App\Models\BlogTag;
                                use App\Models\Tag;
                                $tag_list = Tag::all();
                                $blog_tag_list = BlogTag::select("blog_tag_tab.*")->where("blog_tag_tab.blog_id","=",$row->blog_id)->get();
                                $i = 0;
                                ?>

                                <div style="margin-bottom: 5px;">
                                    <i class="icon-add" style="float: left; margin: 7px 0 0 5px;" onclick="addNewTag()"></i>
                                    <div class="clearfloat"></div>
                                </div>

                                @if(count($blog_tag_list) > 0)
                                    @foreach($blog_tag_list as $key => $blog_tag_item)
                                        <div class="tag-id-select" style="margin-bottom: 5px;">
                                            <select name="tag_id[{{$blog_tag_item->blog_tag_id}}]" style="float: left; width: 178px; ">
                                                <option value="0">Выберите тэг</option>
                                                @if(count($tag_list) > 0)
                                                    @foreach($tag_list as $key => $tag_item)
                                                        <option value="{{$tag_item->tag_id}}" @if($tag_item->tag_id == $blog_tag_item->tag_id) selected @endif>{{$tag_item->tag_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <i class="icon-remove red" style="float: left; margin: 7px 0px 0px 8px;" onclick="deleteTag({{$blog_tag_item->blog_tag_id}},this)"></i>
                                            <div class="clearfloat"></div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Добавление новых тэгов</p>
                            </div>
                            <div class="right-block">
                                <input type="text" id="add_new_tag" value="">
                                <input type="button" class="btn btn-primary" value="Добавить тэг" style="width: 120px; line-height: normal; padding: 2px 12px; margin-left: 5px;" onclick="addNewUserTag()">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Push уведомление</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_push" value="" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <input class="ace ace-switch ace-switch-5" type="checkbox" value="" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>


                            <div class="left-block">
                                <p>Описание (для мета тега)</p>
                            </div>
                            <div class="right-block">
                                <textarea name="blog_meta_desc" style="width: 300px; height: 100px;">{{$row->blog_meta_desc}}</textarea>
                            </div>
                            <div class="clearfloat"></div>

                            @if($row->blog_id > 0)
                                <div style="margin-top: 10px; float: right;">
                                    <input type="submit" class="btn btn-primary" value="Сохранить">
                                </div>
                            @else
                                <div style="margin-top: 10px; float: right;">
                                    <input type="submit" class="btn btn-primary" value="Добавить">
                                    <p style="width: 200px;float: right;margin-left: 10px;">Ваш блог появится на сайте сразу после проверки модератором</p>
                                </div>
                            @endif
                            <div class="clearfloat"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="clone-tag-select" style="display: none; margin-bottom: 5px;">
        <select name="tag_id_new[]" style="float: left; width: 178px; ">
            <option value="0">Выберите тэг</option>
            @if(count($tag_list) > 0)
                @foreach($tag_list as $key => $tag_item)
                    <option value="{{$tag_item->tag_id}}">{{$tag_item->tag_name}}</option>
                @endforeach
            @endif
        </select>

        <i class="icon-remove red" style="float: left;margin: 7px 0px 0px 8px;" onclick="deleteTag(0,this)"></i>
        <div class="clearfloat"></div>
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
