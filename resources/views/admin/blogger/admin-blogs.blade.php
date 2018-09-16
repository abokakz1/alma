<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="/css/dashboard.css" rel="stylesheet">
    <link href="/css/new-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

    <link href="/hot_snackbar/hotsnackbar.css" rel="stylesheet">
    <script src="/hot_snackbar/hotsnackbar.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
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
        <a href="/blogger/edit/{{ Auth::user()->user_id }}" style="color:#fff;">@if(!empty(Auth::user()->image))
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
        <li><a href="/blogger/edit/{{ Auth::user()->user_id }}">
          <i class="fa fa-user menu-icon" aria-hidden="true" style="font-size:20px;">{{trans("messages.user")}}</i></a>
        </li>
        <li class="active"><a href="/blogger/blog-list/{{ Auth::user()->user_id }}"><i class="fa fa-commenting menu-icon" aria-hidden="true"></i>{{trans("messages.my_blogs")}}</a></li>
        <li><a href="/blogs"><i class="fa fa-reply menu-icon" aria-hidden="true"></i>{{trans("messages.Назад")}}</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar sidebar-two">
      <ul class="nav nav-sidebar nav-sidebar-two">
        <li><a href="/blogger/edit/{{ Auth::user()->user_id }}"><i class="fa fa-user menu-icon" aria-hidden="true" style="font-size:20px;"></i>{{trans("messages.user")}}</a></li>
        <li class="active"><a href="/blogger/blog-list/{{ Auth::user()->user_id }}"><i class="fa fa-commenting menu-icon" aria-hidden="true"></i>{{trans("messages.my_blogs")}}</a></li>
        <li><a href="/blogs"><i class="fa fa-reply menu-icon" aria-hidden="true"></i>{{trans("messages.Назад")}}</a></li>
      </ul>
    </div>

    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div style="text-align:center">
        <a style="color:#fff" href="/blogger/blog-edit/0"><button type="submit" class="btn btn-three btn-primary btn-primary-two" value="Добавить блог">{{trans("messages.add_blog")}}</button></a>
        </div>
        <form class="form-horizontal form-horizontal-two" method="post" id="myform" action="{{ route('blog-list_new') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
          
          <div class="form-group">
            <label for="inputPassword3" name="fio" class="col-sm-2 control-label">{{trans("messages.data")}}</label>
            <div class="col-sm-5 col-input couple">
              <span>{{trans("messages.from")}}</span>
              <div id="datepicker" class="input-group date" data-date-format="dd.mm.yyyy">
                <input class="form-control" name="date" type="text" readonly value="@if(strlen($blog_date) > 0) {{date("d.m.Y",strtotime($blog_date))}} @endif" />
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
              </div>
              <span class="odin">{{trans("messages.to")}}</span>
              <div id="datepicker-two" class="input-group date" data-date-format="dd.mm.yyyy">
                <input class="form-control" name="date_to" type="text" readonly value="@if(strlen($blog_date_to) > 0) {{date("d.m.Y",strtotime($blog_date_to))}} @endif" class="datepicker selected-date"/>
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail3" name="email" class="col-sm-2 control-label">{{trans("messages.lang_blog")}}</label>
            <div class="col-sm-5 col-input">
              <select name="blog_lang_id" title="" class="form-control"> 
                <option value="0">{{trans('messages.select_lang')}}</option>
                <option value="1" @if($blog_lang_id == 1) selected @endif>{{trans('messages.kaz')}}</option>
                <option value="2" @if($blog_lang_id == 2) selected @endif>{{trans('messages.rus')}}</option>
              </select>
              <!-- <input type="email" class="form-control" name="blog_lang_id" id="inputEmail3" placeholder="Язык блога"> -->
            </div>
          </div>

          <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">{{trans('messages.search_name')}}</label>
            <div class="col-sm-5 col-input">
              <input type="text" class="form-control" value="@if(strlen($name) > 0){{$name}}@endif" name="name" id="inputPassword3" placeholder="{{trans('messages.search_name')}}">
            </div>
          </div>

          <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">{{trans('messages.tag_blog')}}</label>
            <div class="col-sm-5 col-input">
              <?php use App\Models\Tag;
                $tag_list = Tag::all();
              ?>
              <select name="tag_id" title="" class="form-control">
                  <option value="">{{trans('messages.select_tag')}}</option>
                  @if(count($tag_list) > 0)
                      @foreach($tag_list as $key => $tag_item)
                          <option value="{{$tag_item->tag_id}}" @if($tag_item->tag_id == $tag_id) selected @endif >{{$tag_item['tag_name']}}</option>
                      @endforeach
                  @endif
              </select>
              <!-- <input type="password" class="form-control" name="tag_id" id="inputPassword3" placeholder="Тэг блога"> -->
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              <button type="submit" value="Поиск" style="margin:0" class="btn btn-two btn-primary btn-primary-two">{{trans('messages.search_blog')}}</button>
            </div>
          </div>
        </form>
        <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr style="font-size:13px;">
              <th>ID</th>
              <th>{{trans('messages.header')}} (Каз)</th>
              <th>{{trans('messages.header')}} (Рус)</th>
              <th>{{trans('messages.data')}}</th>
              <th>{{trans('messages.picture')}}</th>
              <th>{{trans('messages.preview')}}</th>
              <th>{{trans('messages.active')}} (Каз)</th>
              <th>{{trans('messages.active')}} (Рус)</th>
            </tr>
          </thead>
          <tbody>
            @if(count($row) > 0)
                @foreach($row as $key => $blog_item)
                    <tr class="row_{{$blog_item->blog_id}}" style="font-size:12px;">
                        <td class="center">
                            <input type="hidden" value="{{$blog_item->blog_id}}" class="hidden-id-input">
                            <input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addToArray({{$blog_item->blog_id}},this)" class="checkbox-item-list" title="">
                            <a href="/admin/blog-edit/{{$blog_item->blog_id}}">{{$blog_item->blog_id}}</a>
                        </td>
                        <td><a href="/blogger/blog-edit/{{$blog_item->blog_id}}">{{$blog_item->blog_title_kz}}</a></td>
                        <td><a href="/blogger/blog-edit/{{$blog_item->blog_id}}">{{$blog_item->blog_title_ru}}</a></td>
                        <td>{{$blog_item->date}}</td>
                        <td><img class="kitty-img" src="{{ $blog_item->image }}"/></td>
                        <td class="center">
                          <a target="_blank" href="/blogs/{{ $blog_item->blog_url_name }}">
                            {{trans('messages.go_to')}}
                          </a>
                        </td>
                        <td>
                            @if($blog_item->is_active_kz == 1)
                                {{trans('messages.yes')}}
                            @else
                                {{trans('messages.no')}}
                            @endif
                        </td>
                        <td>
                            @if($blog_item->is_active_ru == 1)
                                {{trans('messages.yes')}}
                            @else
                                {{trans('messages.no')}}
                            @endif
                        </td>
                        <td style="padding-left: 20px;">
                            <div style="margin: 0 0 5px 0;">
                                <input type="button" class="btn btn-small btn-danger operation-classes"  onclick="deleteBlog({{$blog_item->blog_id}},this)" value="{{trans('messages.remove')}}">
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif

          </tbody>
        </table>
      </div>
      <div class="form-group">
          <div class="col-sm-12 no-padding">
              <button type="submit" value="{{trans('messages.delete_selected')}}" style="margin:0" class="btn btn-two btn-primary btn-primary-two">{{trans('messages.delete_selected')}}</button>
          </div>
      </div>
    </div>
  </div>
</div>


</body>
</html>

<script>
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

$(function () {
  $("#datepicker").datepicker({ 
        autoclose: true, 
        todayHighlight: true
  })
});

$(function () {
  $("#datepicker-two").datepicker({ 
        autoclose: true, 
        todayHighlight: true
  })
});

function deleteBlog(blog_id,ob){
  if (!confirm('{{trans("messages.really_want_remove")}}?')) {
    return false;
  }

  $.ajax({
      type: 'GET',
      url: "{{route('delete-blog_new')}}",
      data: {_token: CSRF_TOKEN, blog_id: blog_id},
      success: function(data){
          if(data.result === false){
              // alert("");
              hotsnackbar('hserror', '{{trans("messages.error_on_removing")}}');
          }
          else{
            hotsnackbar('hsdone', "{{trans('messages.blog_removed')}}");
            $(ob).closest("tr").remove();
          }
      }
  });
}
</script>