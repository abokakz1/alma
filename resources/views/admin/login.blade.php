<head>
    <title>Вход в личный кабинет</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link href="/css/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/css/assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/css/assets/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/css/assets/css/ace-fonts.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="/css/assets/css/ace.min.css" />
    <link rel="stylesheet" href="/css/assets/css/ace-responsive.min.css" />
    <link rel="stylesheet" href="/css/assets/css/ace-skins.min.css" />

    <!--[if IE 7]>
    <link rel="stylesheet" href="/css/assets/css/font-awesome-ie7.min.css" />
    <![endif]-->

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="/css/assets/css/ace-ie.min.css" />
    <![endif]-->

    <script src="/jquery/js/jquery-1.7.2.min.js"></script>
</head>

<script type="text/javascript">
    function show_box(id) {
        jQuery('.widget-box.visible').removeClass('visible');
        jQuery('#'+id).addClass('visible');
    }
</script>
<body class="login-layout">
<div class="main-container container-fluid">
    <div class="main-content">
        <div class="row-fluid">
            <div class="span12">
                <div class="login-container">
                    <div class="row-fluid">
                        <div class="center">
                            <h1>
                                <a href="/" style="text-decoration: none;">
                                    <i class="icon-leaf green"></i>
                                    <span class="red">Алматы канал</span>
                                </a>
                            </h1>
                            <h4 class="blue">&copy; АЛМАТЫ</h4>
                        </div>
                    </div>

                    <div class="space-6"></div>
                    <div class="row-fluid">
                        <div class="position-relative">
                            <div id="login-box" class="login-box visible widget-box no-border">
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h4 class="header blue lighter bigger" style="text-align: center;">
                                            <i class="icon-coffee green"></i>
                                            Введите ваши данные
                                        </h4>
                                        <div class="space-6"></div>
                                        <form method="post" action="/admin/login">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <fieldset>
                                                <label>
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="span12" placeholder="Email" name="email" value="{{ $email }}"/>
                                                            <i class="icon-envelope"></i>
                                                        </span>
                                                </label>

                                                <label>
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" class="span12" placeholder="Пароль" name="password"/>
                                                            <i class="icon-lock"></i>
                                                        </span>
                                                </label>

                                                <div class="space"></div>

                                                <div class="clearfix">
                                                    <!--                                                        <label class="inline">-->
                                                    <!--                                                            <input type="checkbox" class="ace" />-->
                                                    <!--                                                            <span class="lbl"> Remember Me</span>-->
                                                    <!--                                                        </label>-->

                                                    <input type="submit" class="width-35 pull-right btn btn-small btn-primary" value="Войти" name="login_btn">
                                                </div>

                                                <div class="space-4"></div>
                                                <label style="color: red; text-align: center;">{{ $error }}</label>
                                            </fieldset>
                                        </form>

                                    </div><!-- /widget-main -->

                                    <div class="toolbar clearfix" style="text-align: center;">
                                        <div style="text-align: center; float: none;">
                                            <a href="/admin/reset-password" class="forgot-password-link">
                                                <i class="icon-arrow-left"></i>
                                                Забыли пароль?
                                            </a>
                                        </div>

                                        <!--                                            <div>-->
                                        <!--                                                <a href="#" onclick="show_box('signup-box'); return false;" class="user-signup-link">-->
                                        <!--                                                    Регистрация-->
                                        <!--                                                    <i class="icon-arrow-right"></i>-->
                                        <!--                                                </a>-->
                                        <!--                                            </div>-->
                                    </div>
                                </div><!-- /widget-body -->
                            </div><!-- /login-box -->

                            <div id="signup-box" class="signup-box widget-box no-border">
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h4 class="header green lighter bigger">
                                            <i class="icon-group blue"></i>
                                            Регистрация нового пользователя
                                        </h4>

                                        <div class="space-6"></div>
                                        <p> Введите ваши данные: </p>

                                        <form>
                                            <fieldset>
                                                <label>
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="span12" placeholder="Имя" />
                                                            <i class="icon-user"></i>
                                                        </span>
                                                </label>

                                                <label>
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="email" class="span12" placeholder="Email"/>
                                                            <i class="icon-envelope"></i>
                                                        </span>
                                                </label>

                                                <label>
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" class="span12" placeholder="Пароль" />
                                                            <i class="icon-lock"></i>
                                                        </span>
                                                </label>

                                                <label>
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" class="span12" placeholder="Повторите пароль" />
                                                            <i class="icon-retweet"></i>
                                                        </span>
                                                </label>

                                                <!--                                                    <label>-->
                                                <!--                                                        <input type="checkbox" class="ace" />-->
                                                <!--                                                        <span class="lbl">-->
                                                <!--                                                            I accept the-->
                                                <!--                                                            <a href="#">User Agreement</a>-->
                                                <!--                                                        </span>-->
                                                <!--                                                    </label>-->

                                                <div class="space-24"></div>

                                                <div class="clearfix">
                                                    <button type="reset" class="width-32 pull-left btn btn-small">
                                                        <i class="icon-refresh"></i>
                                                        Очистить
                                                    </button>

                                                    <button onclick="return false;" class="width-65 pull-right btn btn-small btn-success">
                                                        Зарегистрироваться
                                                        <i class="icon-arrow-right icon-on-right"></i>
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>

                                    <div class="toolbar center">
                                        <a href="#" onclick="show_box('login-box'); return false;" class="back-to-login-link">
                                            <i class="icon-arrow-left"></i>
                                            Войти под логином
                                        </a>
                                    </div>
                                </div><!-- /widget-body -->
                            </div><!-- /signup-box -->
                        </div><!-- /position-relative -->
                    </div>
                </div>
            </div><!-- /.span -->
        </div><!-- /.row-fluid -->
    </div>
</div><!-- /.main-container -->
</body>