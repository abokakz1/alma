@extends('index.layout')

@section('content')
    <div class="page-header">
        <div class="back-link-block">
            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
        </div>
        <div class="page-title-block">
            <h1 class="page-title">{{trans("messages.Телепроекты")}}</h1>
        </div>
    </div>
    <div class="tv-projects v-tabs">
        <aside>
            <ul class="v-tab-list">
                <?php
                $days = array(
                        1 => trans("messages.Понедельник"), 2 => trans("messages.Вторник"), 3 => trans("messages.Среда"), 4 => trans("messages.Четверг"),
                        5 => trans("messages.Пятница"), 6 => trans("messages.Суббота"), 7 => trans("messages.Воскресенье"));
                ?>
                <li class="active">
                    <a onclick="setAllProgramms(this)" style="cursor: pointer;">{{trans("messages.ВСЕ ПРОГРАММЫ")}}</a>
                </li>

                <li>
                    <a onclick="showProgrammByLang(this,1)" style="cursor: pointer;">{{trans("messages.ПРОГРАММЫ НА КАЗАХСКОМ ЯЗЫКЕ")}}</a>
                </li>

                <li>
                    <a onclick="showProgrammByLang(this,2)" style="cursor: pointer;">{{trans("messages.ПРОГРАММЫ НА РУССКОМ ЯЗЫКЕ")}}</a>
                </li>

                <li>
                    <a onclick="showProgrammByLang(this,3)" style="cursor: pointer;">{{trans("messages.ПРОГРАММЫ ТЕКУЩЕГО СЕЗОНА")}}</a>
                </li>

                <li>
                    <a onclick="showProgrammByLang(this,4)" style="cursor: pointer;">{{trans("messages.ПРОГРАММЫ В АРХИВЕ")}}</a>
                </li>

                <li>
                    <a onclick="showProgrammByLang(this,5)" style="cursor: pointer;">{{trans("messages.СПЕЦПРОЕКТЫ")}}</a>
                </li>
            </ul>
        </aside>

         {{--Add code --}}

        <div class="mob-category hidden-lg hidden-md">
            <select onchange="showProgrammByLangMobile(this)" class="select-new-type">
                <option value="0">{{trans("messages.ВСЕ ПРОГРАММЫ")}}</option>
                <option value="1">{{trans("messages.ПРОГРАММЫ НА КАЗАХСКОМ ЯЗЫКЕ")}}</option>
                <option value="2">{{trans("messages.ПРОГРАММЫ НА РУССКОМ ЯЗЫКЕ")}}</option>
                <option value="3">{{trans("messages.ПРОГРАММЫ ТЕКУЩЕГО СЕЗОНА")}}</option>
                <option value="4">{{trans("messages.ПРОГРАММЫ В АРХИВЕ")}}</option>
                <option value="5">{{trans("messages.СПЕЦПРОЕКТЫ")}}</option>
            </select>
        </div>
        <style>
            .mob-category .select-new-type{
                background: #e6e6e6;
                height: 40px;
                border:0;
                outline:none;
                width: 100%;
                padding: 0 15px;
                -moz-appearance: none;
                -webkit-appearance: none;
                appearance: none;
                background-image: url(/css/images/arrow-down.png);
                background-position: center right 15px;
                background-repeat: no-repeat;
                color: #91ba42;
                background-size: 18px;

            }
            .select-new-type option:checked {
                color: #91ba42;
            }

        </style>

        {{--Add code--}}

        <article>
            <div class="row ContentArchive">

            </div>
        </article>
    </div>

    <script>
        $(document).ready(function(){
            $(".ajax-loader").fadeIn(200);
            $(".ContentArchive").empty();
            $(".ContentArchive").load("/index/all-programms-block/{{App::getLocale()}}");
        });
    </script>

    <script>
        function setProgrammsByTime(day_id,ob){
            $(".ajax-loader").fadeIn(200);
            $(".v-tab-list").find("li").removeClass("active");
            $(ob).closest("li").addClass("active");
            $(".ContentArchive").empty();
            $(".ContentArchive").load("/index/programm-by-day/" + day_id + "/{{App::getLocale()}}");
        }

        function setAllProgramms(ob){
            $(".ajax-loader").fadeIn(200);
            $(".v-tab-list").find("li").removeClass("active");
            $(ob).closest("li").addClass("active");
            $(".ContentArchive").empty();
            $(".ContentArchive").load("/index/all-programms-block/{{App::getLocale()}}");
            $(".ajax-loader").fadeOut(200);
        }

        function showProgrammByLang(ob,type){
            $(".ajax-loader").fadeIn(200);
            $(".v-tab-list").find("li").removeClass("active");
            $(ob).closest("li").addClass("active");
            $(".programm-div-list").css("display","none");

            if(type == 1){
                $(".kaz-programm").css("display","block");
            }
            else if(type == 2){
                $(".rus-programm").css("display","block");
            }
            else if(type == 3){
                $(".is-cur-season").css("display","block");
            }
            else if(type == 4){
                $(".is-archive").css("display","block");
            }
            else if(type == 5){
                $(".is-spec-project").css("display","block");
            }
            $(".ajax-loader").fadeOut(200);
        }

        function showProgrammByLangMobile(ob){
            $(".ajax-loader").fadeIn(200);
            type = $(ob).val();
            $(".programm-div-list").css("display","none");

            if(type == 1){
                $(".kaz-programm").css("display","block");
            }
            else if(type == 2){
                $(".rus-programm").css("display","block");
            }
            else if(type == 3){
                $(".is-cur-season").css("display","block");
            }
            else if(type == 4){
                $(".is-archive").css("display","block");
            }
            else if(type == 5){
                $(".is-spec-project").css("display","block");
            }
            else{
                $(".ContentArchive").empty();
                $(".ContentArchive").load("/index/all-programms-block/{{App::getLocale()}}");
            }
            $(".ajax-loader").fadeOut(200);
        }
    </script>
@endsection