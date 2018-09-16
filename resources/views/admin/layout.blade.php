@extends('admin.top')

@section('layout')
    <?php  $user = Auth::user();
            $action_name = "";
            $controller_name = "";
            $current_path_parts = explode("/",Route::getFacadeRoot()->current()->uri());
            if(isset($current_path_parts[0])){
                $controller_name = $current_path_parts[0];
            }
            if(isset($current_path_parts[1])){
                $action_name = $current_path_parts[1];
            }
    ?>

    <div class="ui-widget-overlay ui-front"></div>
    <body>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <div class="ajax-loader"></div>
        <div class="navbar" id="navbar">
            <script type="text/javascript">
                try{ace.settings.check('navbar' , 'fixed')}catch(e){}
            </script>

            <div class="navbar-inner">
                <div class="container-fluid">
                    <a href="/" class="brand">
                        <small>
                            <i class="icon-leaf"></i>
                            Алматы канал
                        </small>
                    </a><!-- /.brand -->
                    <ul class="nav ace-nav pull-right">
                        <li class="light-blue">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                <img class="nav-user-photo" src="/css/assets/avatars/user.jpg" alt="Jason's Photo" />
                                <span class="user-info" style="max-width: 160px;">
                                    <small>Welcome,</small>
                                    {{ $user->fio }}
                                </span>
                                <i class="icon-caret-down"></i>
                            </a>

                            <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
                                <li>
                                    <a class="button" href="/admin/change-password-edit">
                                        <i class="icon-cog"></i>
                                        Изменить пароль
                                    </a>
                                </li>
                                <li>
                                    <a class="button" href="/admin/logout">
                                        <i class="icon-off"></i>
                                        Выход
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul><!-- /.ace-nav -->
                </div><!-- /.container-fluid -->
            </div><!-- /.navbar-inner -->
        </div>

        <div class="main-container container-fluid">
            <a class="menu-toggler" id="menu-toggler" href="#">
                <span class="menu-text"></span>
            </a>

            <div class="sidebar" id="sidebar">
                <script type="text/javascript">
                    try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
                </script>

                <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                    <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                        <button class="btn btn-small btn-success">
                            <i class="icon-signal"></i>
                        </button>

                        <button class="btn btn-small btn-info">
                            <i class="icon-pencil"></i>
                        </button>

                        <button class="btn btn-small btn-warning">
                            <i class="icon-group"></i>
                        </button>

                        <button class="btn btn-small btn-danger">
                            <i class="icon-cogs"></i>
                        </button>
                    </div>

                    <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                        <span class="btn btn-success"></span>

                        <span class="btn btn-info"></span>

                        <span class="btn btn-warning"></span>

                        <span class="btn btn-danger"></span>
                    </div>
                </div><!-- #sidebar-shortcuts -->

                <?php
                    $news_list_list_active = "";
                    $news_archive_kz_list_active = "";
                    $news_archive_ru_list_active = "";
                    $blog_list_active = "";
                    $event_list_active = "";
                    $programm_list_list_active = "";
                    $category_list_list_active = "";
                    $news_category_list_list_active = "";
                    $tv_programm_list_list_active = "";
                    $const_tv_programm_list_list_active = "";
                    $vacancy_list_active = "";
                    $video_archive_list_active = "";
                    $user_list_active = "";
                    $job_response_list_active = "";
                    $tag_list_active = "";
                    $advertisement_list_active = "";
                    $menu_list_active = "";
                    $delivery_list_active = "";
                    $employer_active = "";
                    $fav_active = "";
                    $footer_active = "";
                    $about_active = "";
                    $page_active = "";
                    $employer_active = "";
                    $document_active = "";
                    $ads_active = "";
                    if($controller_name == "admin" && $action_name == "news-list"){
                        $news_list_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "news-archive-list/kz"){
                        $news_archive_kz_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "news-archive-list/ru"){
                        $news_archive_ru_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "blog-list"){
                        $blog_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "event-list"){
                        $event_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "programm-list"){
                        $programm_list_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "category-list"){
                        $category_list_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "news-category-list"){
                        $news_category_list_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "tv-programm-list"){
                        $tv_programm_list_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "const-tv-programm-list"){
                        $const_tv_programm_list_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "vacancy-list"){
                        $vacancy_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "video-archive-list"){
                        $video_archive_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "user-list"){
                        $user_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "job-response-list"){
                        $job_response_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "tag-list"){
                        $tag_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "advertisement-list"){
                        $advertisement_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "menu-list"){
                        $menu_list_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "delivery-list"){
                        $delivery_list_active = " class = 'active'";
                    } 
                    else if($controller_name == "admin" && $action_name == "employer-list"){
                        $employer_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "fav-list"){
                        $fav_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "footer-list"){
                        $footer_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "about-list"){
                        $about_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "page-list"){
                        $page_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "employer-list"){
                        $employer_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "document-list"){
                        $document_active = " class = 'active'";
                    }
                    else if($controller_name == "admin" && $action_name == "ads-list"){
                        $ads_active = " class = 'active'";
                    }
                ?>
                <ul class="nav nav-list">
                    @if(Auth::user()->role_id == 1)
                        <li <?=$news_list_list_active?>>
                            <a href="/admin/news-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Новости </span>
                            </a>
                        </li>

                        <li <?=$news_archive_kz_list_active?>>
                            <a href="/admin/news-archive-list/kz">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Архив новостей (Каз) </span>
                            </a>
                        </li>

                        <li <?=$news_archive_ru_list_active?>>
                            <a href="/admin/news-archive-list/ru">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Архив новостей (Рус) </span>
                            </a>
                        </li>

                        
                        <li <?=$blog_list_active?>>
                            <a href="/admin/blog-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Блог </span>
                            </a>
                        </li>
                        <li <?=$event_list_active?>>
                            <a href="/admin/event-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Событие </span>
                            </a>
                        </li>
                        <li <?=$programm_list_list_active?>>
                            <a href="/admin/programm-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Телепроекты </span>
                            </a>
                        </li>

                        <li <?=$category_list_list_active?>>
                            <a href="/admin/category-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Категории </span>
                            </a>
                        </li>

                        <li <?=$news_category_list_list_active?>>
                            <a href="/admin/news-category-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Категории новостей</span>
                            </a>
                        </li>

                        <li <?=$tv_programm_list_list_active?>>
                            <a href="/admin/tv-programm-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> ТВ программа </span>
                            </a>
                        </li>

                        <li <?=$const_tv_programm_list_list_active?>>
                            <a href="/admin/const-tv-programm-list" style="height: auto; line-height: 22px; padding-top: 5px; padding-bottom: 5px;">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Постоянные ТВ программы </span>
                            </a>
                        </li>

                        <li <?=$vacancy_list_active?>>
                            <a href="/admin/vacancy-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Вакансии </span>
                            </a>
                        </li>

                        <li <?=$video_archive_list_active?>>
                            <a href="/admin/video-archive-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Видеоархив </span>
                            </a>
                        </li>

                        <li <?=$tag_list_active?>>
                            <a href="/admin/tag-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Список тэгов </span>
                            </a>
                        </li>

                        <li <?=$job_response_list_active?>>
                            <a href="/admin/job-response-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Отклики на вакансию </span>
                            </a>
                        </li>

                        <li <?=$user_list_active?>>
                            <a href="/admin/user-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Пользователи </span>
                            </a>
                        </li>

                        <li <?=$advertisement_list_active?>>
                            <a href="/admin/advertisement-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Рекламные баннера </span>
                            </a>
                        </li>

                        <li <?=$menu_list_active?>>
                            <a href="/admin/menu-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Пункты меню </span>
                            </a>
                        </li>

                        <li <?=$delivery_list_active?>>
                            <a href="/admin/delivery-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Подписчики </span>
                            </a>
                        </li>

                        <!-- <li <?=$employer_active?>>
                            <a href="/admin/employer-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Сотрудники </span>
                            </a>
                        </li> -->

                        <li <?=$fav_active?>>
                            <a href="/admin/fav-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Выбор редакции </span>
                            </a>
                        </li>
                        <li <?=$footer_active?>>
                            <a href="/admin/footer-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Нижнее меню </span>
                            </a>
                        </li>
                        <li <?=$about_active?>>
                            <a href="/admin/about-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> "О нас" меню </span>
                            </a>
                        </li>
                        <li <?=$page_active?>>
                            <a href="/admin/page-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Page </span>
                            </a>
                        </li>
                        <li <?=$employer_active?>>
                            <a href="/admin/employer-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Employer </span>
                            </a>
                        </li>
                        <li <?=$document_active?>>
                            <a href="/admin/document-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Document </span>
                            </a>
                        </li>
                        <li <?=$ads_active?>>
                            <a href="/admin/ads-list">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Рекламодателям</span>
                            </a>
                        </li>
                    @else
                        <li <?=$user_list_active?>>
                            <a href="{{ route('user-edit', ['user_id' => Auth::user()->user_id]) }}">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Пользователь </span>
                            </a>
                        </li>
                        <li <?=$blog_list_active?>>
                            <a href="{{ route('blog-list', ['user_id' => Auth::user()->user_id]) }}">
                                <i class="icon-list"></i>
                                <span class="menu-text"> Блог </span>
                            </a>
                        </li>
                    @endif


                    {{--<li>--}}
                        {{--<a href="#" class="dropdown-toggle">--}}
                            {{--<i class="icon-list"></i>--}}
                            {{--<span class="menu-text"> Заказы</span>--}}
                            {{--<b class="arrow icon-angle-down" style="margin-top: 3px;"></b>--}}
                        {{--</a>--}}

                        {{--<ul class="submenu">--}}
                            {{--<li>--}}
                                {{--<a href="">--}}
                                    {{--<i class="icon-double-angle-right"></i>--}}
                                    {{--Новости--}}
                                {{--</a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                </ul><!-- /.nav-list -->

                <div class="sidebar-collapse" id="sidebar-collapse">
                    <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
                </div>

                <script type="text/javascript">
                    try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
                </script>
            </div>

            <div class="main-content">
                <div class="breadcrumbs" id="breadcrumbs">
                    <script type="text/javascript">
                        try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                    </script>

                    <ul class="breadcrumb" style="padding-top: 7px;">
                        <li>
                            <i class="icon-home home-icon"></i>
                            <a href="/admin/index">Home</a>
                            <span class="divider">
                                <i class="icon-angle-right arrow-icon"></i>
                            </span>
                        </li>
                        <!--                    <li class="active">Товары</li>-->
                    </ul><!-- .breadcrumb -->
                </div>

                <div class="page-content">
                    <div class="page-header position-relative">
                        @yield('content')
                    </div><!-- /.page-header -->
                </div><!-- /.page-content -->
            </div><!-- /.main-content -->
        </div><!-- /.main-container -->

        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-small btn-inverse">
            <i class="icon-double-angle-up icon-only bigger-110"></i>
        </a>
    </body>

    <script>
        jQuery(function($) {
            $( ".datepicker").datepicker({
                showOtherMonths: true,
                selectOtherMonths: false,
                dateFormat: 'dd.mm.yy',
                monthNames : ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
                dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб']
            });
            $('.timepicker').timepicker({
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false
            });
        });

        KindEditor.ready(function(K) {
            K.create('#editor1, #editor2, #editor3, #video_archive_editor1, #video_archive_editor2', {

                cssPath : [''],
                autoHeightMode : true, // это автоматическая высота блока
                afterCreate : function() {
                    this.loadPlugin('autoheight');
                },
                allowFileManager : true,
                items : [// Вот здесь задаем те кнопки которые хотим видеть
                    'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
                    'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                    'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                    'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                    'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
                    'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons','pagebreak',
                    'anchor', 'link',  'unlink','map', '|', 'about'
                ]
            });
            //Ниже инициализируем доп. например выбор цвета или загрузка файла
            var colorpicker;
            K('#colorpicker').bind('click', function(e) {
                e.stopPropagation();
                if (colorpicker) {
                    colorpicker.remove();
                    colorpicker = null;
                    return;
                }
                var colorpickerPos = K('#colorpicker').pos();
                colorpicker = K.colorpicker({
                    x : colorpickerPos.x,
                    y : colorpickerPos.y + K('#colorpicker').height(),
                    z : 19811214,
                    selectedColor : 'default',
                    noColor : 'Очистить',
                    click : function(color) {
                        K('#color').val(color);
                        colorpicker.remove();
                        colorpicker = null;
                    }
                });
            });
            K(document).click(function() {
                if (colorpicker) {
                    colorpicker.remove();
                    colorpicker = null;
                }
            });

            var editor = K.editor({
                allowFileManager : true
            });
        });

        function changeCheckboxValue(ob){
            if($(ob).is(":checked")){
                $(ob).closest(".right-block").find(".hidden-checkbox-value").attr("value",1);
            }
            else{
                $(ob).closest(".right-block").find(".hidden-checkbox-value").attr("value",0);
            }
        }
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');


        var arr = [];
        function addToArray(id,ob){
            if($(ob).prop("checked") == true){
                arr.push(id);
            }
            else{
                arr.splice(arr.indexOf(id), 1);
            }
            console.log(arr);
        }

        function deleteSelectedRows(table_name){
            if(arr.length < 1){
                showInfo("Отметьте записи");
                return;
            }

            if (!confirm('Вы действительно хотите удалить отмеченные записи?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/multiple-delete",
                data: {_token: CSRF_TOKEN, table_name: table_name, arr: arr},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при удалении записей");
                    }
                    else{
                        showInfo("Отмеченные записи удалены");
                        arr.forEach(function(item, i, arr) {
                            $(".row_" + item).remove();
                        });

                        arr = [];
                    }
                }
            });
        }

        function addAllToArray(ob){
            arr = [];
            if($(ob).prop("checked") == false){
                $(".checkbox-item-list").prop("checked",false);
            }
            else{
                $( ".hidden-id-input" ).each(function() {
                    val = parseInt($( this).val());
                    arr.push(val);
                });

                $(".checkbox-item-list").prop("checked",true);
            }
        }
    </script>
@endsection
