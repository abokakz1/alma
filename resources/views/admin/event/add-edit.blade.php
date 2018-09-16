@extends('admin.layout')

@section('content')
    <style>
        select,input{
            margin-bottom: 0 !important;
        }
        .right-block{
            width: auto;
            text-align: center;
        } #my-drop{
            min-height: 100px;
            border: 2px dashed #b4b7c2;
            padding: 10px 0;
            border-radius: 10px;
            background-color: #ecedf0;
            width: 400px;
            cursor: pointer;
        } .dropzone a.dz-remove, .dropzone a.dz-remove:hover{
            background-color: #fff;
            color: #000;
        }
    </style>
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
    <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet">
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
                    <form id="myform" enctype="multipart/form-data" method="post" action="/admin/event-edit/{{$row->event_id}}">
                        <div style="float: left; width: 900px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="event_id" value="{{$row->event_id}}">

                            <div class="left-block">
                                <p>Заголовок</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="event_title" value="{{$row->event_title}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Текст блога</p>
                            </div>
                            <div class="right-block">
                                <textarea id="editor1" name="event_text">{{$row->event_text}}</textarea>
                                <!-- <textarea name="event_text" style="width: 500px;height: 150px;margin: 0px 0px 10px;">{{$row->event_text}}</textarea> -->
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p><b>Дата начало:</b> </p>
                            </div>
                            <div class="right-block" style="float: left; margin-top: 10px;">
                                <div class="left-block" style="width: 150px;">
                                    <p>Дата</p>
                                </div>
                                <div class="right-block">
                                    @if(strlen($row->date_start) > 0)
                                        <input type="text" name="date_start" value="{{substr($row->date_start,0,10)}}" class="datepicker" style="width: 95px; text-align: center;">
                                    @else
                                        <input type="text" name="date_start" value="{{date("d.m.Y")}}" class="datepicker" style="width: 95px; text-align: center;">
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
                                        @if(strlen($row->date_start) > 0)
                                            <input type="text" class="input-small timepicker" style="width: 75px;" name="time" value="{{substr($row->date_start,11,-6)}}:{{substr($row->date_start,14,2)}}:{{substr($row->date_start,17,2)}}"/>
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

                            <div class="left-block">
                                <p><b>Дата окончание:</b></p>
                            </div>
                            <div class="right-block" style="float: left; margin-top: 10px;">
                                <div class="left-block" style="width: 150px;">
                                    <p>Дата</p>
                                </div>
                                <div class="right-block">
                                    @if(strlen($row->date_end) > 0)
                                        <input type="text" name="date_end" value="{{substr($row->date_end,0,10)}}" class="datepicker" style="width: 95px; text-align: center;">
                                    @else
                                        <input type="text" name="date_end" value="" class="datepicker" style="width: 95px; text-align: center;">
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
                                        @if(strlen($row->date_end) > 0)
                                            <input type="text" class="input-small timepicker" style="width: 75px;" name="time_end" value="{{substr($row->date_end,11,-6)}}:{{substr($row->date_end,14,2)}}:{{substr($row->date_end,17,2)}}"/>
                                        @else
                                            <input type="text" class="input-small timepicker" style="width: 75px;" name="time_end" value="00:00"/>
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
                                <p>Закрепить как Топ событие</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_main_event" value="{{$row->is_main_event}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->is_main_event == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_main_event}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Отображать на календарь событий</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="in_calendar" value="{{$row->in_calendar}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->in_calendar == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->in_calendar}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Цена входа</p>
                            </div>
                            <div class="right-block">
                                <?php $checked1 = " "; $disabled = " " ?>
                                @if($row->price == 0)
                                    <?php $checked1 = " checked"; $disabled = " disabled" ?>
                                @endif
                                <input type="number" name="price" class="hidden-checkbox-value" style="float: left;width: 60px;" <?php echo $disabled?> value="@if(strlen($disabled)<3){{$row->price}}@endif" >
                                <span style="float: left;margin-left: 5px;line-height: 28px;"> ТГ |</span>
                                <label style="margin-top: 3px;float: left;">
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->price}}" onchange="changeCheckboxPriceValue(this)"/>
                                    <span class="lbl"></span>
                                    <span style="margin-left: 5px">Бесплатный</span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Опубликовать</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="published" value="{{$row->published}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                    @if($row->published == 1)
                                        <?php $checked1 = " checked"; ?>
                                    @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->published}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Адрес</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="address" value="{{$row->address}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Категория событий</p>
                            </div>
                            <div class="right-block tag-select-block" style="width: 535px;">
                                <?php
                                use App\Models\EvCategory;
                                $category_list = EvCategory::all();
                                $event_cat_list = DB::table('event_category_tab')->where('event_id', $row->event_id)->get();
                                // $event_cat_list = $row->categories()->get();
                                // $blog_tag_list = BlogTag::select("blog_tag_tab.*")->where("blog_tag_tab.blog_id","=",$row->blog_id)->get();
                                $i = 0;
                                ?>

                                <div style="margin-bottom: 5px;">
                                    <i class="icon-add" style="float: left; margin: 7px 0 0 5px;" onclick="addNewCategory()"></i>
                                    <div class="clearfloat"></div>
                                </div>

                                @if(count($event_cat_list) > 0)
                                    @foreach($event_cat_list as $key => $event_cat_item)
                                        <div class="tag-id-select" style="margin-bottom: 5px;">
                                            <select name="category_id[{{$event_cat_item->category_id}}]" style="float: left; width: 178px; ">
                                                <option value="0">Выберите категорию</option>
                                                @if(count($category_list) > 0)
                                                    @foreach($category_list as $key => $category_item)
                                                        <option value="{{$category_item->category_id}}" @if($category_item->category_id == $event_cat_item->category_id) selected @endif>{{$category_item->category_name_ru}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <i class="icon-remove red" style="float: left; margin: 7px 0px 0px 8px;" onclick="deleteCategory({{$event_cat_item->category_id}},this)"></i>
                                            <div class="clearfloat"></div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Описание (для мета тега)</p>
                            </div>
                            <div class="right-block">
                                <textarea name="event_meta_desc" style="width: 300px; height: 100px;">{{$row->event_meta_desc}}</textarea>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p><b>Организатор:</b> Имя</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="organizer_name" value="{{$row->organizer_name}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p><b>Организатор:</b> Email</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="organizer_email" value="{{$row->organizer_email}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p><b>Организатор:</b> Номер</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="organizer_number" value="{{$row->organizer_number}}">
                            </div>
                            <div class="clearfloat"></div>

                            @if($row->event_id > 0)
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
                    </form>

                            <div class="left-block">
                                <p>Медия файлы<br>
                                    <!-- <div style="width: 20px;height: 20px;border: 2px solid #000;float: left;margin-right: 10px;"></div>
                                    главное фото -->
                                </p>
                            </div>
                            <div class="right-block">

                            <form action="/admin/file-upload/{{$row->event_id}}" class="dropzone" id="my-drop">
                                <div class="dz-message" data-dz-message><span>{{ trans('messages.drop_files') }}</span></div>
                                <div class="fallback">  
                                    <input name="file" type="file"/>
                                </div>
                            </form>
                            </div>
                            <div class="clearfloat"></div>

                </div>
            </div>
        </div>
    </div>

    <div class="clone-tag-select" style="display: none; margin-bottom: 5px;">
        <select name="category_id_new[]" style="float: left; width: 178px; ">
            <option value="0">Выберите категорию</option>
            @if(count($category_list) > 0)
                @foreach($category_list as $key => $category_item)
                    <option value="{{$category_item->category_id}}">{{$category_item->category_name_ru}}</option>
                @endforeach
            @endif
        </select>

        <i class="icon-remove red" style="float: left;margin: 7px 0px 0px 8px;" onclick="deleteCategory(0,this)"></i>
        <div class="clearfloat"></div>
    </div>

    <script>
        jQuery(function($) {
            $(".datepicker").datepicker("setDate", new Date());
        });
    </script>

    <script>
        function addNewCategory(){
            a = $(".clone-tag-select").clone();
            a.removeClass('clone-tag-select');
            a.addClass('tag-id-select');
            a.css("display","block");
            $(".tag-select-block").append(a);
            return;
        }

        function deleteCategory(category_id,ob){
            $(ob).closest(".tag-id-select").remove();

            if(category_id > 0){
                $.ajax({
                    type: 'GET',
                    url: "/admin/delete-event-category",
                    data: {_token: CSRF_TOKEN, id: category_id, event_id: {{$row->event_id}} },
                    success: function(data){
                        if(data.result == false){
                            alert("Ошибка при удалении записи");
                        }
                        else{
                            showInfo("Категория удален");
                        }
                    }
                });
            }
        }

        function changeCheckboxPriceValue(ob){
            if($(ob).is(":checked")){
                $(ob).closest(".right-block").find(".hidden-checkbox-value").attr("disabled",true);
            }
            else{
                $(ob).closest(".right-block").find(".hidden-checkbox-value").attr("disabled",false);
            }
        }
    </script>
<script>
Dropzone.options.myDrop = {
        acceptedFiles: 'image/png, .jpg, .jpeg',
        paramName: 'file',
        maxFilesize: 100,
        addRemoveLinks: true,
        dictRemoveFile: '{{trans("messages.remove")}}',
        dictCancelUpload: '{{trans("messages.cancel_upload")}}',
        dictCancelUploadConfirmation: '{{trans("messages.cancel_upload_confirm")}}',
        init: function() {

            <?php use App\Models\Media;
                $media_list = Media::select('*')->where('event_id', $row->event_id)->get();
            ?>
            @if(count($media_list)>0)
                @for($i=0; $i< count($media_list); $i++)
            //JS code  
            let mockFile_{{$i}} = { name: "filename", size: 12345, id:{{$media_list[$i]->media_id}} };
            this.emit("addedfile", mockFile_{{$i}});
            // mockFile_{{$i}}.previewElement.addEventListener("click", function() {
            //     changeAvatar(mockFile_{{$i}});
            // });
            this.emit("thumbnail", mockFile_{{$i}}, "/event_photo/{{$media_list[$i]->link }}");
            this.emit("complete", mockFile_{{$i}});
            this.files.push(mockFile_{{$i}});
            // End of - JS code 
                {{-- @if($media_list[$i]->media_id == $row->image_id) changeAvatar(mockFile_{{$i}}); @endif --}}
                @endfor
            @endif
            this.on('success', function( file, resp ){
                console.log(resp);
                file.id = resp.media_id;
                console.log(file.id);
                console.log(this.files);
            });

            this.on("removedfile", function(file) {
                console.log(this.files);
                console.log(file.id);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('file_delete')}}",
                    data: {media_id: file.id},
                    success: function(data){
                        if(data.result === false){
                            console.log('File Dont Deleted');   
                        } else{
                            console.log('File Deleted');
                        }
                    }
                });
            });
            // this.on("addedfile", function(file) {
            //   file.previewElement.addEventListener("click", function() {
            //     changeAvatar(file);
            //   });
            // });

        }
}
function changeAvatar(file){
    console.log(file.id);
    $('.dz-preview').each(function(){ $(this).css('border', 'initial');});
    file.previewElement.style.border = "2px #000 solid";
    $.ajax({
        type: 'POST',
        url: "{{ route('event_avatar')}}",
        data: {media_id: file.id, event_id: {{$row->event_id}}},
        success: function(data){
            if(!data.result){
                console.log('Avatar Dont Updated');   
            } else{
                console.log('Avatar Updated');
                showInfo("Главное фото обнавлен");
            }
        }
    });
}
</script>
@endsection
