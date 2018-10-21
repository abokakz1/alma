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
                    <form id="myform" enctype="multipart/form-data" method="post" action="/admin/news-edit/{{$row->news_id}}">
                        <div style="float: left; width: 900px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="news_id" value="{{$row->news_id}}">

                            <div class="left-block">
                                <p>Заголовок (Каз)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="news_title_kz" value="{{$row->news_title_kz}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Заголовок (Рус)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="news_title_ru" value="{{$row->news_title_ru}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Заголовок (Анг)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="news_title_en" value="{{$row->news_title_en}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>
                                    Анонс фото
                                    <span style="color: red;">Рекомендуемый размер картинки: 248х210</span>
                                </p>
                            </div>
                            <div class="right-block">
                                <div class="image-block1">
                                    <div>
                                        @if(strlen($row->image) > 0)
                                            <img src="/news_photo/{{$row->image}}" width="150px" height="150px" id="news_image">
                                        @else
                                            <img src="/css/image/no_photo.png" width="150px" height="150px" id="news_image">
                                        @endif
                                    </div>
                                    <div style="text-align: center; margin: 10px 0px 0px 20px;">
                                        <input class="upl-file" id="fileupload" type="file" name="image">
                                        <br>
                                        <input type="button" class="btn btn-primary" value="Картинки на выбор" style="width: 170px; margin-top: 10px; line-height: normal; padding: 2px 12px;" onclick="showImageBlock(1)">
                                    </div>
                                </div>
                                <div class="image-block-select1" style="display: none;">
                                    <img width="150" height="150"><br>
                                    <select name="image_select1" onchange="setImage(this)" style="margin-top: 10px;">
                                        <option value="0">Выберите картинку</option>
                                        <option value="1">Картинка 1</option>
                                        <option value="2">Картинка 2</option>
                                        <option value="3">Картинка 3</option>
                                    </select>
                                    <br>
                                    <input type="button" class="btn btn-primary" value="Загрузить свою картинку" style="width: 200px; margin-top: 10px; line-height: normal; padding: 2px 12px;" onclick="showImageUploadBlock(1)">
                                </div>
                            </div>

                            <script>
                                function showImageBlock(id){
                                    $(".image-block" + id).fadeOut(200);
                                    $(".image-block" + id).find(".upl-file").val("");
                                    $(".image-block-select" + id).fadeIn(200);
                                }
                                function showImageUploadBlock(id){
                                    $(".image-block-select" + id).fadeOut(200);
                                    $(".image-block-select" + id).find("select").val(0);
                                    $(".image-block-select" + id).find("img").removeAttr("src");
                                    $(".image-block" + id).fadeIn(200);
                                }

                                function setImage(ob){
                                    if($(ob).val() > 0){
                                        $(ob).closest("div").find("img").attr("src","/css/images/no_news_img" + $(ob).val() + ".png");
                                    }
                                    else{
                                        $(ob).closest("div").find("img").removeAttr("src");
                                    }
                                }
                            </script>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>
                                    Детальное фото "Внутренная страница новости"
                                    <span style="color: red;">Рекомендуемый размер картинки: 960х350</span>
                                </p>
                            </div>
                            <div class="right-block">
                                <div class="image-block2">
                                    <div>
                                        @if(strlen($row->image_big) > 0)
                                            <img src="/news_photo/{{$row->image_big}}" width="150px" height="150px" id="news_image_big">
                                        @else
                                            <img src="/css/image/no_photo.png" width="150px" height="150px" id="news_image_big">
                                        @endif
                                    </div>
                                    <div style="text-align: center; margin: 10px 0px 0px 20px;">
                                        <input class="upl-file" id="fileupload2" type="file" name="image_big">
                                        <br>
                                        <input type="button" class="btn btn-primary" value="Картинки на выбор" style="width: 170px; margin-top: 10px; line-height: normal; padding: 2px 12px;" onclick="showImageBlock(2)">
                                    </div>
                                </div>

                                <div class="image-block-select2" style="display: none;">
                                    <img width="150" height="150"><br>
                                    <select name="image_select2" onchange="setImage(this)" style="margin-top: 10px;">
                                        <option value="0">Выберите картинку</option>
                                        <option value="1">Картинка 1</option>
                                        <option value="2">Картинка 2</option>
                                        <option value="3">Картинка 3</option>
                                    </select>
                                    <br>
                                    <input type="button" class="btn btn-primary" value="Загрузить свою картинку" style="width: 200px; margin-top: 10px; line-height: normal; padding: 2px 12px;" onclick="showImageUploadBlock(2)">
                                </div>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Текст новости (Каз)</p>
                            </div>
                            <div class="right-block">
                                <textarea id="editor1" name="news_text_kz">{{$row->news_text_kz}}</textarea>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Текст новости (Рус)</p>
                            </div>
                            <div class="right-block">
                                <textarea id="editor2" name="news_text_ru">{{$row->news_text_ru}}</textarea>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Текст новости (Анг)</p>
                            </div>
                            <div class="right-block">
                                <textarea id="editor3" name="news_text_en">{{$row->news_text_en}}</textarea>
                            </div>
                            <div class="clearfloat"></div>

                            <div style="float: left; margin-top: 10px;">
                                <div class="left-block" style="width: 55px;">
                                    <p>Дата</p>
                                </div>
                                <div class="right-block">
                                    @if(strlen($row->date) > 0)
                                        <input type="text" name="date" value="{{substr($row->date,0,10)}}" class="datepicker" style="width: 95px; text-align: center;">
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
                                        @if(strlen($row->date) > 0)
                                            <input type="text" class="input-small timepicker" style="width: 75px;" name="time" value="{{substr($row->date,12,2)}}:{{substr($row->date,15,2)}}:{{substr($row->date,18,2)}}"/>
                                        @else
                                            <?php $offset= strtotime("+6 hours 0 minutes");  ?>
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

                            <div class="left-block">
                                <p>Активный</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_active" value="{{$row->is_active}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->is_active == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_active}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Закрепить новость</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_fix" value="{{$row->is_fix}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->is_fix == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_fix}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Новости на главную "Мини блоки"</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_almaty" value="{{$row->is_almaty}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->is_almaty == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_almaty}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Новость на слайдер</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_main_news" value="{{$row->is_main_news}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->is_main_news == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_main_news}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Наличие на Whatsapp</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_whatsapp" value="{{$row->is_whatsapp}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->is_whatsapp == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_whatsapp}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Наличие фото</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_has_foto" value="{{$row->is_has_foto}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->is_has_foto == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_has_foto}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Наличие видео</p>
                            </div>
                            <div class="right-block">
                                @if($row->news_id < 1)
                                    <? $row->is_has_video = 1; ?>
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
                                <p>На Mail.ru</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_mail_ru" value="{{$row->is_mail_ru}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->is_mail_ru == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_mail_ru}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Категория новости</p>
                            </div>
                            <div class="right-block">
                                <? use App\Models\NewsCategory;
                                    $news_category_list = NewsCategory::all();
                                ?>
                                <select name="news_category_id" >
                                    <option value="null">Выберите категорию новости</option>
                                    @if(count($news_category_list) > 0)
                                        @foreach($news_category_list as $key => $news_category_item)
                                            <option value="{{$news_category_item->news_category_id}}" @if($news_category_item->news_category_id == $row->news_category_id) selected @endif >{{$news_category_item['news_category_name_ru']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Тэг поста</p>
                            </div>
                            <div class="right-block tag-select-block" style="width: 535px;">
                                <? use App\Models\NewsTag;
                                use App\Models\Tag;
                                $tag_list = Tag::all();
                                $news_tag_list = NewsTag::select("news_tag_tab.*")->where("news_tag_tab.news_id","=",$row->news_id)->get();
                                $i = 0;
                                ?>

                                <div style="margin-bottom: 5px;">
                                    <i class="icon-add" style="float: left; margin: 7px 0px 0px 5px;" onclick="addNewTag()"></i>
                                    <div class="clearfloat"></div>
                                </div>

                                @if(count($news_tag_list) > 0)
                                    @foreach($news_tag_list as $key => $news_tag_item)
                                        <div class="tag-id-select" style="margin-bottom: 5px;">
                                            <select name="tag_id[{{$news_tag_item->news_tag_id}}]" style="float: left; width: 178px; ">
                                                <option value="0">Выберите тэг</option>
                                                @if(count($tag_list) > 0)
                                                    @foreach($tag_list as $key => $tag_item)
                                                        <option value="{{$tag_item->tag_id}}" @if($tag_item->tag_id == $news_tag_item->tag_id) selected @endif>{{$tag_item->tag_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <i class="icon-remove red" style="float: left; margin: 7px 0px 0px 8px;" onclick="deleteTag({{$news_tag_item->news_tag_id}},this)"></i>
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

                            @if($row->news_id > 0)
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

                        <select name="programm_id" style="display: none;">
                            <option value="null">Выберите категорию новости</option>
                            @if(count($programm_list) > 0)
                                @foreach($programm_list as $key => $programm_item)
                                    <option value="{{$programm_item->programm_id}}" @if($programm_item->programm_id == $row->programm_id) selected @endif >{{$programm_item['programm_name_ru']}}</option>
                                @endforeach
                            @endif
                        </select>

                        <div class="clearfloat"></div>

                        <div class="left-block">
                            <p>Описание для мета тега(Каз)</p>
                        </div>
                        <div class="right-block">
                            <textarea name="news_meta_desc_kz" style="width: 300px; height: 100px;">{{$row->news_meta_desc_kz}}</textarea>
                        </div>
                        <div class="clearfloat"></div>

                        <div class="left-block">
                            <p>Описание для мета тега(Рус)</p>
                        </div>
                        <div class="right-block">
                            <textarea name="news_meta_desc_ru" style="width: 300px; height: 100px;">{{$row->news_meta_desc_ru}}</textarea>
                        </div>
                        <div class="clearfloat"></div>
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

        function deleteTag(news_tag_id,ob){
            $(ob).closest(".tag-id-select").remove();

            if(news_tag_id > 0){
                $.ajax({
                    type: 'GET',
                    url: "/admin/delete-news-tag",
                    data: {_token: CSRF_TOKEN, id: news_tag_id},
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
