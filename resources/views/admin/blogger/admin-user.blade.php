<html>
<head>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="/css/dashboard.css" rel="stylesheet">
  <link href="/css/new-admin.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/css/croppie.css">
  <script src="/js/croppie.min.js"></script>

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
            <li class="active"><a href="/blogger/edit/{{ Auth::user()->user_id }}">
              <i class="fa fa-user menu-icon" aria-hidden="true" style="font-size:20px;"></i>{{trans('messages.user')}}</a>
            </li>
            <li><a href="/blogger/blog-list/{{ Auth::user()->user_id }}"><i class="fa fa-commenting menu-icon" aria-hidden="true"></i>{{trans('messages.my_blogs')}}</a></li>
            <li><a href="/blogs"><i class="fa fa-reply menu-icon" aria-hidden="true"></i>{{trans('messages.Назад')}}</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar sidebar-two">
          <ul class="nav nav-sidebar nav-sidebar-two">
            <li class="active"><a href="/blogger/edit/{{ Auth::user()->user_id }}"><i class="fa fa-user menu-icon" aria-hidden="true" style="font-size:20px;"></i>{{trans('messages.user')}}</a></li>
            <li><a href="/blogger/blog-list/{{ Auth::user()->user_id }}"><i class="fa fa-commenting menu-icon" aria-hidden="true"></i>{{trans('messages.my_blogs')}}</a></li>
            <li><a href="/blogs"><i class="fa fa-reply menu-icon" aria-hidden="true"></i>{{trans('messages.Назад')}}</a></li>
          </ul>
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          @if(isset($result['status']))
            <p style="color: red; font-size: 14px; text-align: center;">
              @if(count($result['value']) > 0)
                @foreach($result['value'] as $key => $error_item)
                  {{ $error_item }} <br>
                @endforeach
              @endif
            </p>
          @endif

          @if($errors->any())
            <h4>{{$errors->first()}}</h4>
          @endif
		  	
        <form class="form-horizontal form-horizontal-two" method="post" enctype="multipart/form-data" action="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), '/blogger/user-edit/'.$row->user_id) }}">
			  <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="user_id" value="{{$row->user_id}}">

        <div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                  <div id="upload-demo" class="center-block"></div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('messages.close')}}</button>
                  <button type="button" id="cropImageBtn" class="btn btn-primary">{{trans('messages.crop')}}</button>
              </div>
    </div>
  </div>
</div>

        <div class="container-two">
          <!--<div id="imagePreview" style="@if(!empty($row->image)) background-image: url('/user_photo/{{ $row->image }}') @endif"><span class="span" style="display: none;">+</span></div>
          <input id="fileupload" type="file" name="image" class="img" />-->
          <label class="cabinet center-block">
            <figure>
              <img src="@if(!empty($row->image)){{url('/user_photo/'. $row->image) }}@else /css/image/user-image.png @endif" class="gambar img-responsive img-thumbnail img-thumbnail-two " id="item-img-output" />
              <span class="plus">+</span>
            </figure>
            <p style="text-align: center;"><?php echo trans("messages.image_size"); ?></p>
            <input type="file" class="item-img file center-block" name="image"/>
          </label>
        </div>

        <div class="form-group">
			    <label for="inputPassword3" class="col-sm-2 control-label">{{trans('messages.fio')}}</label>
			    <div class="col-sm-5 col-input">
			      <input type="text" class="form-control" name="fio" value="{{$row->fio}}" id="inputPassword3" placeholder="{{trans('messages.fio')}}">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
			    <div class="col-sm-5 col-input">
			      <input type="email" class="form-control" id="inputEmail3" placeholder="Email" name="email" value="{{$row->email}}">
			    </div>
			  </div>

			  <div class="form-group">
			    <label for="inputPassword3" class="col-sm-2 control-label">{{trans('messages.user')}}</label>
			    <div class="col-sm-5 col-input">
			      <input type="text" name="username" value="{{ $row->username}}" class="form-control" id="inputPassword3" placeholder="{{trans('messages.user')}}">
			    </div>
			  </div>

        <div class="form-group">
          <label for="inputPassword3" class="col-sm-2 control-label">{{trans('messages.sitata')}}</label>
          <div class="col-sm-5 col-input">
            <textarea name="quote" value="{{ $row->quote}}" class="form-control" style="resize:vertical">{{$row->quote}}</textarea>
          </div>
        </div>        

			  <div class="form-group">
			    <div class="col-sm-12">
			      <button type="submit" value="{{trans('messages.save')}}" class="btn btn-two btn-primary btn-primary-two" id="blogger-submit">{{trans('messages.save')}}</button>
			    </div>
			  </div>
			</form>
        </div>
      </div>
    </div>

</body>
</html>

<script>
	
/*$(function() {
  if($('#imagePreview').css('background-image') != 'none') {
      $("#imagePreview").on({
            "mouseover" : function() {
              $(".span").css('display','block');
            },
            "mouseout" : function() {
              $(".span").css('display','none');
            }
        });
  } else {
    $(".span").css('display','block');
  }

  $("#fileupload").on("change", function() {
    var files = !!this.files ? this.files : [];
    if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support    

    if (/^image/.test(files[0].type)) { // only image file
      var reader = new FileReader(); // instance of the FileReader
      reader.readAsDataURL(files[0]); // read the local file

      reader.onloadend = function() { // set image data as background of div
        $("#imagePreview").css("background-image", "url(" + this.result + ")");
        $(".span").css('display','none');
        $("#imagePreview").on({
            "mouseover" : function() {
              $(".span").css('display','block');
            },
            "mouseout" : function() {
              $(".span").css('display','none');
            }
        });
      }
    }
  });
  $('#imagePreview').click(function() {
    $('#fileupload').trigger('click');
  });
});*/

/*$(".gambar").attr("src", "/css/images/square.png");*/
            var $uploadCrop,
            tempFilename,
            rawImg,
            imageId;
            function readFile(input) {
              if (input.files && input.files[0]) {
                      var reader = new FileReader();
                      reader.onload = function (e) {
                  $('.upload-demo').addClass('ready');
                  $('#cropImagePop').modal('show');
                        rawImg = e.target.result;
                      }
                      reader.readAsDataURL(input.files[0]);
                  }
                  else {
                    swal("Sorry - you're browser doesn't support the FileReader API");
                }
            }

            $uploadCrop = $('#upload-demo').croppie({
              enableExif: true,
              viewport: {
                width: 170,
                height: 170
              },
            });
            $('#cropImagePop').on('shown.bs.modal', function(){
              // alert('Shown pop');
              $uploadCrop.croppie('bind', {
                    url: rawImg
                  }).then(function(){
                    console.log('jQuery bind complete');
                  });
            });

            $('.item-img').on('change', function () { imageId = $(this).data('id'); tempFilename = $(this).val();
                                                     $('#cancelCropBtn').data('id', imageId); readFile(this); });
            $('#cropImageBtn').on('click', function (ev) {
              $uploadCrop.croppie('result', {
                type: 'base64',
                format: 'jpg'
              }).then(function (resp) {
                $('#item-img-output').attr('src', resp);
                $('#cropImagePop').modal('hide');
              });
            });
        // End upload preview image



</script>
<script>
  $(document).ready(function(){
    $('#blogger-submit').on('click', function(){
      console.log('Foto salindi');
      $('.plus').html('<img src="/css/image/35.gif" style="width: 20px;margin: 8px auto">')
    });
  });
</script>