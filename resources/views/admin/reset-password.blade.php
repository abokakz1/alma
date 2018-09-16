<head>
    <title>Сброс пароля</title>
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

</head>

<style>
    .widget-box {
        visibility: visible !important;
        -moz-transform: none !important; /* Для Firefox */
        -ms-transform: none !important; /* Для IE */
        -webkit-transform: none !important; /* Для Safari, Chrome, iOS */
        -o-transform: none !important; /* Для Opera */
        transform: none !important;
    }
</style>

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
                                    <span class="red">Алматы Канал</span>
                                </a>
                            </h1>
                            <h4 class="blue">&copy; Алматы</h4>
                        </div>
                    </div>

                    <div class="space-6"></div>

                    <div class="row-fluid">
                        <div class="position-relative">
                            <div id="forgot-box" class="forgot-box widget-box no-border">
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h4 class="header red lighter bigger" style="text-align: center;">
                                            <i class="icon-key"></i>
                                            Восстановление пароля
                                        </h4>

                                        <div class="space-6"></div>
                                        <p>
                                            Введите ваш Email
                                        </p>

                                        <form method="post">
                                            <fieldset>
                                                <label>
                                                    <span class="block input-icon input-icon-right">
                                                        <input type="email" class="span12" placeholder="Email" name="reset_email"/>
                                                        <i class="icon-envelope"></i>
                                                    </span>
                                                </label>

                                                <div class="clearfix">
                                                    <input type="submit" class="width-35 pull-right btn btn-small btn-danger" value="Восстановить" name="reset_pass_btn">
                                                </div>

                                                <div class="space-4"></div>
                                                <label style="color: red; text-align: center; margin-bottom: 0px;">{{$error}}</label>
                                            </fieldset>
                                        </form>
                                    </div><!-- /widget-main -->

                                    <div class="toolbar center">
                                        <a href="/admin/login" class="back-to-login-link">
                                            Войти под логином
                                            <i class="icon-arrow-right"></i>
                                        </a>
                                    </div>
                                </div><!-- /widget-body -->
                            </div><!-- /forgot-box -->
                        </div><!-- /position-relative -->
                    </div>
                </div>
            </div><!-- /.span -->
        </div><!-- /.row-fluid -->
    </div>
</div><!-- /.main-container -->
</body>