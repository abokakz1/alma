@extends('index.layout')

@section('content')
    <?php
    $monthes = array(
            1 => trans("messages.Января"), 2 => trans("messages.Февраля"), 3 => trans("messages.Марта"), 4 => trans("messages.Апреля"),
            5 => trans("messages.Мая"), 6 => trans("messages.Июня"), 7 => trans("messages.Июля"), 8 => trans("messages.Августа"),
            9 => trans("messages.Сентября"), 10 => trans("messages.Октября"), 11 => trans("messages.Ноября"), 12 => trans("messages.Декабря"));
    $days = array(
            1 => trans("messages.Понедельник"), 2 => trans("messages.Вторник"), 3 => trans("messages.Среда"), 4 => trans("messages.Четверг"),
            5 => trans("messages.Пятница"), 6 => trans("messages.Суббота"), 0 => trans("messages.Воскресенье"));
    ?>

    <div class="page-header">
        <div class="back-link-block">
            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}">
                <i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span>
            </a>
        </div>

        <div class="page-title-block">
            <h1 class="page-title">{{trans("messages.Программа передач")}}</h1>
        </div>
    </div>


    <div class="v-tabs archive-tabs">
        <aside>
            <ul class="v-tab-list">
                {{--<li class="active">--}}
                    {{--<a style="cursor: pointer;" onclick="setWeekProgramm(this)">--}}
                        {{--программы--}}
                        {{--<small>на неделю</small>--}}
                    {{--</a>--}}
                {{--</li>--}}

                @for($i = 1; $i < 8; $i++)
                    <li @if(date("Y-m-d") == date('Y-m-d', strtotime(date('Y').'W'.date('W').$i))) class="active" @endif>
                        <a style="cursor: pointer" onclick="setProgrammTimesByDate('{{date('Y-m-d', strtotime(date('Y').'W'.date('W').$i))}}',this)" class="d{{date('Y-m-d', strtotime(date('Y').'W'.date('W').$i))}}">
                            {{date('j', strtotime(date('Y').'W'.date('W').$i))}} {{$monthes[date('n', strtotime(date('Y').'W'.date('W').$i))]}}
                            <small>{{$days[date('w', strtotime(date('Y').'W'.date('W').$i))]}}</small>
                        </a>
                    </li>
                @endfor
            </ul>
        </aside>

        <script>
            $(document).ready(function(){
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1; //January is 0!
                var yyyy = today.getFullYear();

                if(dd<10) {
                    dd='0'+dd
                }

                if(mm<10) {
                    mm='0'+mm
                }

                today = yyyy+'-'+mm+'-'+dd;
                $(".ajax-loader").fadeIn(200);
                $(".time-programm-list").empty();
                $(".time-programm-list").load("/index/set-programm-times-by-date/" + today + "/1/{{App::getLocale()}}");
                {{--$(".time-programm-list").load("/index/set-week-programm/{{App::getLocale()}}");--}}
                $(".list_program").empty();
                $(".ajax-loader").fadeOut(200);
            });
            function setProgrammTimesByDate(date,ob){
                $(".ajax-loader").fadeIn(200);
                $(".v-tab-list").find("li").removeClass("active");
                $(ob).closest("li").addClass("active");
                $(".time-programm-list").empty();
                $(".time-programm-list").load("/index/set-programm-times-by-date/" + date + "/1/{{App::getLocale()}}");
                $(".list_program").empty();
                {{--$(".list_program").load("/index/set-programm-times-by-date/" + date + "/2/{{App::getLocale()}}");--}}
            }

            function setWeekProgramm(ob){
                $(".ajax-loader").fadeIn(200);
                $(".v-tab-list").find("li").removeClass("active");
                $(ob).closest("li").addClass("active");
                $(".time-programm-list").empty();
                $(".time-programm-list").load("/index/set-week-programm/{{App::getLocale()}}");
                $(".list_program").empty();
                $(".ajax-loader").fadeOut(200);
            }

            function setProgrammPeredach(ob){
                date = $(ob).find(":selected").data("date");

                if(date != 0){
                    $(".ajax-loader").fadeIn(200);
                    $(".time-programm-list").empty();
                    $(".time-programm-list").load("/index/set-programm-times-by-date/" + date + "/1/{{App::getLocale()}}");
                    $(".list_program").empty();
                }
            }
        </script>

        {{--Add code--}}

        <div class="mob-category hidden-lg hidden-md">
            <select onchange="setProgrammPeredach(this)" class="select-new-type">
                <option value="0">Выберите дату</option>
                @for($i = 1; $i < 8; $i++)
                    <option data-date="{{date('Y-m-d', strtotime(date('Y').'W'.date('W').$i))}}" value="" @if(date("Y-m-d") == date('Y-m-d', strtotime(date('Y').'W'.date('W').$i))) selected @endif> {{date('j', strtotime(date('Y').'W'.date('W').$i))}} {{$monthes[date('n', strtotime(date('Y').'W'.date('W').$i))]}} <small>({{$days[date('w', strtotime(date('Y').'W'.date('W').$i))]}})</small></option>
                @endfor
            </select>
        </div>
        <style>
            .mob-in-category .select-new-type{
                color: #9fa0a0;
                background: #e6e6e6;
                height: 40px;
                border:0;
                outline:none;
                width: 100%;
                padding: 0 15px;
                -moz-appearance: none;
                -webkit-appearance: none;
                appearance: none;
                background-image: url(/css/images/arrow-down2.png);
                background-position: center right 15px;
                background-repeat: no-repeat;
                background-size: 18px;
            }
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

            .select-new-type small{
                color: #666;
            }
        </style>

        {{--Add code --}}

        <article class="time-programm-list">

        </article>
    </div>
@endsection