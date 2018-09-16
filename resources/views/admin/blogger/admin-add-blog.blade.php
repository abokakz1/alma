<html>
<head>

  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="/css/dashboard.css" rel="stylesheet">
  <link href="/css/new-admin.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
  <script type="text/javascript" src="/js/wysiwyg/kindeditor.js"></script>
  <script src="/css/assets/fusioncharts/js/fusioncharts.js"></script>
  <script src="/css/assets/fusioncharts/js/themes/fusioncharts.theme.zune.js"></script>
  <link href="/js/wysiwyg/default.css" rel="stylesheet">
  <script type="text/javascript" src="/js/wysiwyg/ru_Ru.js"></script>
  <script src="/css/assets/js/date-time/bootstrap-timepicker.min.js"></script>
  <link rel="stylesheet" href="/css/assets/css/bootstrap-timepicker.css">
  
  <link rel="icon" href="/css/images/favicon.png" type="image/x-icon">
  <title>Телеканал "Алматы" онлайн</title>
  <meta name="description" content="Новости, аналитика, программы, кино и музыка на сайте, в социальных сетях, медиаканалах и iOS/ Android-приложениях Алматы TV ">
</head>

<body>

<nav class="navbar navbar-inverse navbar-inverse-two navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand navbar-brand-two" href="/"><img alt="AlmatyTv" width="150" class="img-responsive center-block" src="/css/img/logo.png"></a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-nav-two navbar-right navbar-right-two">
        <a href="/blogger/edit/{{ Auth::user()->user_id }}" style="color:#fff;">
          @if(!empty(Auth::user()->image))
            <img src="/user_photo/{{ Auth::user()->image }}" width="35" height="35">
          @else
            <img src="/user_photo/user.png" width="35" height="35"> 
          @endif
          &nbsp;<!-- <span>{{Auth::user()->fio}}</span> -->
    
    <div class="btn-group" role="group" style="float: right;">
      <a type="button" class="btn user-icon dropdown-toggle" aria-haspopup="true" aria-expanded="false"  data-toggle="dropdown">
        <span class="name">{{ Auth::user()->fio }}</span>
      </a>
      <ul class="dropdown-menu user-second-dropdown dropdown-menu-right" aria-labelledby="dLabel">
        <li>
          <a href="/bloggers/{{Auth::user()->username}}">{{trans("messages.profile")}}</a>
        </li>
        <li>
          <a href="/blogger/blog-list/{{Auth::user()->user_id}}">{{trans("messages.my_blogs")}}</a>
        </li>
        <li>
          <a href="/blogger/blog-edit/0">{{trans("messages.write_blog")}}</a>
        </li>
        <li>
          <a href="/admin/logout">{{trans("messages.logout")}}</a>
        </li>
      </ul>
    </div>
        </a>
      </ul>
      <ul class="nav navbar-nav navbar-right navbar-right-three">
        <li><a href="/blogger/edit/{{ Auth::user()->user_id }}"><i class="fa fa-user menu-icon" aria-hidden="true" style="font-size:20px;"></i>{{trans('messages.user')}}</a></li>
        <li class="active"><a href="/blogger/blog-list/{{ Auth::user()->user_id }}"><i class="fa fa-commenting menu-icon" aria-hidden="true"></i>{{trans('messages.my_blogs')}}</a></li>
        <li><a href="/blogs"><i class="fa fa-reply menu-icon" aria-hidden="true"></i>{{trans('messages.Назад')}}</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar sidebar-two">
      <ul class="nav nav-sidebar nav-sidebar-two">
        <li><a href="/blogger/edit/{{ Auth::user()->user_id }}"><i class="fa fa-user menu-icon" aria-hidden="true" style="font-size:20px;"></i>{{trans('messages.user')}}</a></li>
        <li class="active"><a href="/blogger/blog-list/{{ Auth::user()->user_id }}"><i class="fa fa-commenting menu-icon" aria-hidden="true"></i>{{trans('messages.my_blogs')}}</a></li>
        <li><a href="/blogs"><i class="fa fa-reply menu-icon" aria-hidden="true"></i>{{trans('messages.Назад')}}</a></li>
      </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h5>{{trans('messages.select_locale')}}</h5>
    
    @if(isset($result['status']))
      <p style="color: red; font-size: 14px; text-align: center;">
          @if(count($result['value']) > 0)
            @foreach($result['value'] as $key => $error_item)
              {{ $error_item }} <br>
            @endforeach
          @endif
      </p>
    @endif
      <form class="form-horizontal form-horizontal-two" id="myform" enctype="multipart/form-data" method="post" action="{{ route('blog-edit_new',  ['id' => $row->blog_id]) }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="blog_id" value="{{$row->blog_id}}">
          <div class="form-group">
            <label for="inputEmail3" name="email" class="col-sm-2 control-label">{{trans('messages.header')}} (Каз)</label>
            <div class="col-sm-5 col-input">
              <input type="text" class="form-control" name="blog_title_kz" value="{{ $row->blog_title_kz }}" id="inputEmail3" placeholder="{{trans('messages.header')}} (Каз)">
            </div>
          </div>
          <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">{{trans('messages.header')}} (Рус)</label>
            <div class="col-sm-5 col-input">
              <input type="text" class="form-control" name="blog_title_ru" value="{{ $row->blog_title_ru }}" id="inputPassword3" placeholder="{{trans('messages.header')}} (Рус)">
            </div>
          </div>
          @if($row->blog_id < 1)
          <div class="form-group">
            <div class="row" style="display: inline;">
              <label class="col-sm-2 control-label">{{trans('messages.picture')}}</label>
              <div class="col-sm-5 col-input picture">
                <label>
                  <input type="radio" name="def_images" value='0' checked/><img class="kitty-img" src="/blog_photo/0.jpg"/>
                </label>
                <label>
                  <input type="radio" name="def_images" value='1'/><img class="kitty-img" src="/blog_photo/1.jpg"/>
                </label>
                <label>
                  <input type="radio" name="def_images" value='2'/><img class="kitty-img" src="/blog_photo/2.jpg"/>
                </label>
                <label>
                  <input type="radio" name="def_images" value='3'/><img class="kitty-img" src="/blog_photo/3.jpg"/>
                </label>
              </div>
            </div>
            <div class="row" style="display: inline;">
              <div class="col-sm-5 col-sm-offset-2 col-input picture">
                <label>
                  <input type="radio" name="def_images" value='4'/><img class="kitty-img" src="/blog_photo/4.jpg"/>
                </label>
                <label>
                  <input type="radio" name="def_images" value='5'/><img class="kitty-img" src="/blog_photo/5.jpg"/>
                </label>
                <label>
                  <input type="radio" name="def_images" value='6'/><img class="kitty-img" src="/blog_photo/6.jpg"/>
                </label>
                <label>
                  <input type="radio" name="def_images" value='7'/><img class="kitty-img" src="/blog_photo/7.jpg"/>
                </label>
              </div>
            </div> 
            <div class="row">
              <div class="col-sm-3 col-sm-offset-2">
                <input class="upl-file" id="fileupload" type="file" name="image" />
              </div>
              <div class="col-sm-3 ">
                <!-- <span>{{trans('messages.picture_extension')}}</span> -->
              </div>
            </div>
            <p class="col-sm-offset-2" style="padding-left: 50px;">{{trans('messages.blogger_image')}}</p>
          </div>
          @else
          <div class="form-group">
            <div class="row" style="display: inline;">
              <label class="col-sm-2 control-label">{{trans('messages.picture')}}</label>
              <div class="col-sm-5 col-input picture">
                <label>
                  <img class="kitty-img" src="{{ $row->image }}"/>
                </label>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-3 col-sm-offset-2">
                <input class="upl-file" id="fileupload" type="file" name="image" />
              </div>
              <div class="col-sm-3 ">
                <!-- <span>{{trans('messages.picture_extension')}}</span> -->
              </div>
            </div>
          </div>
          @endif
          <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">{{trans('messages.text_blog')}} (Каз)</label>
            <div class="col-sm-5 col-input">
              <textarea class="tinymce" name="blog_text_kz">{{ $row->blog_text_kz }}</textarea>
            </div>
          </div>
          <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">{{trans('messages.text_blog')}} (Рус)</label>
            <div class="col-sm-5 col-input">
              <textarea class="tinymce" name="blog_text_ru">{{ $row->blog_text_ru }}</textarea>
            </div>
          </div>
          <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">{{trans('messages.data')}}</label>
            <div class="col-sm-5 col-input couple">
              
              <div id="datepicker" class="input-group date" data-date-format="dd.mm.yyyy">
                @if(strlen($row->datetime) > 0)
                    <input type="text" name="date" value="{{substr($row->datetime,0,10)}}" class="date-control form-control" readonly style="width: 150px">
                @else
                    <input type="text" name="date" value="{{date("d.m.Y")}}" class="form-control" readonly>
                @endif
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
              </div>
              <span class="odin">{{trans('messages.time')}}</span>
              <div class="input-append bootstrap-timepicker input-group" style="margin-bottom: 0px;">
                @if(strlen($row->datetime) > 0)
                  <input type="text" class="input-small timepicker form-control" name="time" value="{{substr($row->datetime,12, 8)}}"/>
                @else
                  <input type="text" class="input-small timepicker form-control" " name="time" value="{{date('H:i:s')}}"/>
                @endif

                <span class="add-on input-group-addon">
                  <i class="fa fa-clock-o icon-time" aria-hidden="true"></i>
                </span>
              </div>
          </div>
        </div>
        <div class="form-group">
          <label for="inputPassword3" class="col-sm-2 control-label">{{trans('messages.has_photo')}}</label>
          <div class="col-sm-8 col-input">
            <input type="checkbox" class="form-control tgl tgl-ios" id="cb2" value="{{$row->is_has_foto}}" name="is_has_foto" onchange="changeCheckboxValue2(this)" 
            @if($row->is_has_foto == 1)
              checked
            @endif >
            <label class="tgl-btn only-span" for="cb2"></label>
            <span>{{trans('messages.click_if_photo')}}</span>
          </div>
        </div>
        <div class="form-group">
          <label for="inputPassword3" class="col-sm-2 control-label">{{trans('messages.has_video')}}</label>
          <div class="col-sm-8 col-input">
            <input type="checkbox" class="form-control tgl tgl-ios" id="cb3" value="{{$row->is_has_video}}" name="is_has_video" onchange="changeCheckboxValue2(this)" 
            @if($row->is_has_video == 1)
              checked
            @endif >
            <label class="tgl-btn only-span" for="cb3"></label>
          </div>
        </div>
        <div class="form-group">
            <label for="inputPassword4" name="video_url" class="col-sm-2 control-label">{{trans('messages.youtube_link')}}</label>
            <div class="col-sm-5 col-input">
              <input type="text" class="form-control" id="inputPassword4" placeholder="{{trans('messages.youtube_link')}}" name="video_url" value="{{$row->video_url}}">
            </div>
        </div>
        <p class="col-sm-offset-2" style="padding-left: 40px;">{{trans('messages.blogger_link')}}</p>
          <div class="form-group tag-select-block">
            <label for="inputPassword3" class="col-sm-2 control-label">{{trans('messages.tag_post')}}</label>
            <?php use App\Models\BlogTag;
              use App\Models\Tag;
              $tag_list = Tag::all();
              $blog_tag_list = BlogTag::select("blog_tag_tab.*")->where("blog_tag_tab.blog_id","=",$row->blog_id)->get();
              $i = 0;
            ?>

            <div class="col-sm-5 col-input">
               <i class="fa fa-plus tag" aria-hidden="true" onclick="addNewTag()"></i>
                @if(count($blog_tag_list) > 0)
                  @foreach($blog_tag_list as $key => $blog_tag_item) 
                    <div class="tag-id-select" style="margin-bottom: 5px;">
                        <select class="form-control" name="tag_id[{{$blog_tag_item->blog_tag_id}}]" style="float: left; width: 178px;margin-bottom: 10px;">
                            <option value="0">{{trans('messages.select_tag')}}</option>
                            @if(count($tag_list) > 0)
                                @foreach($tag_list as $key => $tag_item)
                                    <option value="{{$tag_item->tag_id}}" @if($tag_item->tag_id == $blog_tag_item->tag_id) selected @endif>{{$tag_item->tag_name}}</option>
                                @endforeach
                            @endif
                        </select>
                        <i class="fa fa-times icon-remove red" style="float: left; color:red; margin: 10px 0px 0px 8px;cursor: pointer;" onclick="deleteTag({{$blog_tag_item->blog_tag_id}},this)" aria-hidden="true"></i>
                        <div class="clearfloat"></div>
                    </div>
                  @endforeach
                @endif

            </div>
          </div>
          
          <div class="form-group">
            <label for="inputPassword6" class="col-sm-2 control-label">{{trans('messages.add_new_tag')}}</label>
            <div class="col-sm-5 col-input">
              <input type="text" class="form-control" id="add_new_tag" value="" placeholder="{{trans('messages.add_new_tag')}}">
            </div>
            <input type="button" class="btn btn-primary btn-primary-two btn-only-two" value="{{trans('messages.add_tag')}}" onclick="addNewUserTag2()">
          </div>

          <div class="form-group">
            <label for="inputPassword9" class="col-sm-2 control-label">{{trans('messages.description_meta_tag')}}</label>
            <div class="col-sm-8 col-input">
              <textarea class="form-control" name="blog_meta_desc" rows="3" style="resize: vertical;">{{$row->blog_meta_desc}}</textarea>
            </div>
          </div>
          <div style="text-align:center">
            @if($row->blog_id > 0)
              <input type="submit" value="{{trans('messages.save_blog')}}" class="btn btn-four btn-primary btn-primary-two" />  
            @else
              <input type="submit" value="{{trans('messages.add_blog')}}" class="btn btn-four btn-primary btn-primary-two" />
              <h5>{{trans('messages.your_post_added')}}</h5>
            @endif
          </div>
      </form>
    </div>
      
  </div>
</div>

<div class="clone-tag-select form-group" style="display: none; margin-bottom: 12px;">
  <div class=" col-sm-offset-2  col-sm-5 col-input">
    <select name="tag_id_new[]" class="form-control" style="float: left;">
        <option value="0">{{trans('messages.select_tag')}}</option>
        @if(count($tag_list) > 0)
            @foreach($tag_list as $key => $tag_item)
                <option value="{{$tag_item->tag_id}}">{{$tag_item->tag_name}}</option>
            @endforeach
        @endif
    </select>
    <i class="fa fa-times remove-tag-icon" aria-hidden="true" onclick="deleteTag(0,this)"></i>
  </div>
</div>

</body>
</html>
<style>
  .remove-tag-icon{
    position: absolute;
    margin: 7px 0px 0px 10px;
    color: red;font-size: 20px;
  }
</style>

<script>

$(function () {
  if (true) {
    $("#datepicker").datepicker({ 
        autoclose: true, 
        todayHighlight: true
  });
  }
});

jQuery(function($) {
            $('.timepicker').timepicker({
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false
            });
        });

</script>

<script>
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
function addNewTag(){
  a = $(".clone-tag-select").clone();
  a.removeClass('clone-tag-select');
  a.addClass('tag-id-select');
  a.css("display","block");
  $(".tag-select-block").after(a);
  return;
}

function deleteTag(blog_tag_id,ob){
  $(ob).closest(".tag-id-select").remove();

  if(blog_tag_id > 0){
      $.ajax({
          type: 'GET',
          url: "{{route('delete-blog-tag_new')}}",
          data: {_token: CSRF_TOKEN, id: blog_tag_id},
          success: function(data){
              if(data.result == false){
                  alert("{{trans('messages.error_on_removing')}}");
              }
              else{
                  showInfo("{{trans('messages.tag_deleted')}}");
              }
          }
      });
  }
}

function addNewUserTag2(){
  if($("#add_new_tag").val().length > 0) {
      $.ajax({
          type: 'GET',
          url: "{{route('add-new-user-tag')}}",
          data: {_token: CSRF_TOKEN, tag_name: $("#add_new_tag").val(), is_new: true},
          success: function (data) {
              if (data.result == false) {
                  alert("{{trans('messages.error_on_adding')}}");
              }
              else {
                  $("#add_new_tag").val("");
                  $(".tag-select-block").after(data);
              }
          }
      });
  }
}
function changeCheckboxValue2(ob){
  if($(ob).is(":checked")){
      $(ob).attr("value",1);
  }
  else{
      $(ob).attr("value",0);
  }
}
</script>
  <script src="{{ URL::to('src/js/vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
  <script src="/css/assets/js/ru.js"></script>
<script>
  var editor_config = {
    path_absolute : "{{ URL::to('/') }}/",
    selector: "textarea.tinymce",
    width : "648",
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
    relative_urls: false,
    file_browser_callback : function(field_name, url, type, win) {
      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
      var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

      var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
      if (type == 'image') {
        cmsURL = cmsURL + "&type=Images";
      } else {
        cmsURL = cmsURL + "&type=Files";
      }

      tinyMCE.activeEditor.windowManager.open({
        file : cmsURL,
        title : 'Filemanager',
        width : x * 0.8,
        height : y * 0.8,
        resizable : "yes",
        close_previous : "no"
      });
    }
  };

  tinymce.init(editor_config);

</script>


<!-- <script>
$(document).ready(function() {
  tinymce.init({
    imageupload_url: "/blog_text_image",
    selector: "textarea.tinymce", 
    language_url : "/css/assets/js/ru.js",
    width : "648",
    theme: "modern",
    paste_data_images: true,
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    file_picker_callback: function(callback, value, meta) {
      if (meta.filetype == 'image') {
        $('#upload').trigger('click');
        $('#upload').on('change', function() {
          var file = this.files[0];
          var reader = new FileReader();
          reader.onload = function(e) {
            callback(e.target.result, {
              alt: ''
            });
          };
          reader.readAsDataURL(file);
        });
      }
    },
    templates: [{
      title: 'Test template 1',
      content: 'Test 1'
    }, {
      title: 'Test template 2',
      content: 'Test 2'
    }]
  });
});
</script> -->
