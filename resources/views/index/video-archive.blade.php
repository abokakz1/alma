@extends('index.layout')

@section('content')
    <?php use App\Models\VideoArchive; ?>

    <div class="page-header">
        <div class="back-link-block">
            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i
                        class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
        </div>
        <div class="page-title-block">
            <h1 class="page-title">{{trans("messages.Видеоархив")}}</h1>
        </div>
    </div>
    <div class="v-tabs archive-tabs w-side">
        <aside>
            <ul class="v-tab-list">
                <?php
                $mn = array(trans("messages.Январь"), trans("messages.Февраль"), trans("messages.Март"), trans("messages.Апрель"), trans("messages.Май"), trans("messages.Июнь"), trans("messages.Июль"), trans("messages.Август"), trans("messages.Сентябрь"), trans("messages.Октябрь"), trans("messages.Ноябрь"), trans("messages.Декабрь")); ?>
                @for($i = 0; $i < date('m'); $i++)
                    @if(date("n")-1-$i >= 0)
                        <?php   $month = date("n") - 1 - $i;
                        $month_search = date("n") - $i;
                        if (strlen($month_search) < 2) {
                            $month_search = "0" . $month_search;
                        }
                        $day_count = date("t", strtotime(date("Y") . '-' . $month_search . '-01'));
                        $date_start = date("Y-" . $month_search . "-01");
                        $date_end = date("Y-" . $month_search . "-" . $day_count);

                        ?>
                        <li @if($i == 0) class="active" @endif>
                            <a style="cursor: pointer;"
                               onclick="setProgrammListByDate('{{$date_start}}','{{$date_end}}',this)">{{$mn[$month]}}
                                <small>{{date("Y")}}</small>
                            </a>
                        </li>
                    @else
                        <?php
                        $month = date("n") - 1 + (12 - $i);
                        $month_search = date("n") + (12 - $i);
                        if (strlen($month_search) < 2) {
                            $month_search = "0" . $month_search;
                        }
                        $day_count = date("t", strtotime(date("Y") . '-' . $month_search . '-01'));
                        $date_start = date("Y-" . $month_search . "-01", strtotime('-1 years'));
                        $date_end = date("Y-" . $month_search . "-" . $day_count, strtotime('-1 years'));
                        ?>

                        <li>
                            <a style="cursor: pointer;"
                               onclick="setProgrammListByDate('{{$date_start}}','{{$date_end}}',this)">{{$mn[$month]}}
                                <small>{{date('Y', strtotime('-1 year'))}}</small>
                            </a>
                        </li>
                    @endif
                @endfor

                @for($i = date('Y') - 1; $i >= 2016; $i--)
                    <li><a style="cursor: pointer;" onclick="setAllProgrammArchive(this, {{$i}})">{{trans("messages.АРХИВ ВИДЕО")}}
                        <small>{{trans("messages.до августа ".$i." года")}}</small>
                    </a></li>
                @endfor
            </ul>
        </aside>

        {{--/ Add code /--}}

        <div class="mob-category hidden-lg hidden-md">
            <select id="mobile_video_archive_date_select" class="select-new-type" onchange="setProgrammListByDateMobile()">
                <option data-date-start="-1">{{trans("messages.Выберите дату")}}</option>
                @for($i = 0; $i < 4; $i++)
                    @if(date("n")-1-$i >= 0)
                        <?php   $month = date("n") - 1 - $i;
                        $month_search = date("n") - $i;
                        if (strlen($month_search) < 2) {
                            $month_search = "0" . $month_search;
                        }
                        $day_count = date("t", strtotime(date("Y") . '-' . $month_search . '-01'));
                        $date_start = date("Y-" . $month_search . "-01");
                        $date_end = date("Y-" . $month_search . "-" . $day_count);
                        ?>
                        <option data-date-start="{{$date_start}}" data-date-end="{{$date_end}}">{{$mn[$month]}} <small>{{date("Y")}}</small></option>
                    @else
                        <?php
                        $month = date("n") - 1 + (12 - $i);
                        $month_search = date("n") + (12 - $i);
                        if (strlen($month_search) < 2) {
                            $month_search = "0" . $month_search;
                        }
                        $day_count = date("t", strtotime(date("Y") . '-' . $month_search . '-01'));
                        $date_start = date("Y-" . $month_search . "-01", strtotime('-1 years'));
                        $date_end = date("Y-" . $month_search . "-" . $day_count, strtotime('-1 years'));
                        ?>

                        <option data-date-start="{{$date_start}}" data-date-end="{{$date_end}}">{{$mn[$month]}} <small>{{date('Y', strtotime('-1 year'))}}</small></option>
                    @endif
                @endfor
                <option data-date-start="0" data-date-end="0">{{trans("messages.АРХИВ ВИДЕО")}} <small>{{trans("messages.до августа 2016 года")}}</small></option>>
            </select>
        </div>

        <script>
            function setProgrammListByDateMobile(){
                date_start = $("#mobile_video_archive_date_select").find(":selected").data("date-start");
                date_end = $("#mobile_video_archive_date_select").find(":selected").data("date-end");
                if(date_start != -1){
                    $(".ajax-loader").fadeIn(200);
                    $("#mobile_video_archive_programm_select").empty();
                    $("#mobile_video_archive_programm_select").load('{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/index/video-archive-programm-list-block-mobile/") }}'+"/" + date_start + "/" + date_end + "/<?php echo App::getLocale();?>");
                    $(".programm-item-list-block").empty();
                }
            }

            function setProgrammItemListByDateMobile(){
                programm_id = $("#mobile_video_archive_programm_select").val();
                date_start = $("#mobile_video_archive_date_select").find(":selected").data("date-start");
                date_end = $("#mobile_video_archive_date_select").find(":selected").data("date-end");

                $(".ajax-loader").fadeIn(200);
                $(".programm-item-list-block").empty();
                if(programm_id > 0){
                    $(".programm-item-list-block").load('{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/index/video-archive-programm-item-list-block/") }}'+ "/" + date_start + "/" + date_end + "/" + programm_id + "/<?php echo App::getLocale();?>");
                }
                else{
                    $(".programm-item-list-block").load('{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/index/all-video-archive-programm-item-list-block") }}'+"/1/<?php echo App::getLocale();?>/" + programm_id);
                }
            }
        </script>

        <div class="mob-in-category hidden-lg hidden-md">
            <select id="mobile_video_archive_programm_select" class="select-new-type" onchange="setProgrammItemListByDateMobile()">
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

        {{--  Add code --}}

        <article>
            <div class="aside">
                <ul class="archive-programs programm-list-block">

                </ul>
            </div>
            <div class="row programm-item-list-block">

            </div>
        </article>
    </div>

    <script>
        $(document).ready(function () {
        	var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();

            if (dd < 10) {
                dd = '0' + dd
            }

            if (mm < 10) {
                mm = '0' + mm
            }

            date_start = yyyy + '-' + mm + '-01';

            month_day_count = new Date(yyyy, mm, 0).getDate();
            date_end = yyyy + '-' + mm + '-' + month_day_count;

            var programm_id = {{$programm_id}};
            if(programm_id>0){
                $(".v-tab-list").find("li").first().addClass("active");
                // $(".ajax-loader").fadeIn(200);
                $(".programm-list-block").load('{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/index/video-archive-programm-list-block") }}'+ "/" + date_start + "/" + date_end + "/<?php echo App::getLocale();?>/" + programm_id);
            } else {
                $(".ajax-loader").fadeIn(200);
                // console.log('{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/index/video-archive-programm-list-block") }}'+ "/"+ date_start + "/" + date_end + "/<?php echo App::getLocale();?>");
                $(".programm-list-block").load('{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/index/video-archive-programm-list-block") }}'+ "/" + date_start + "/" + date_end + "/<?php echo App::getLocale();?>");
                $(".programm-item-list-block").empty();
            }
        });

        function setProgrammListByDate(date_start, date_end, ob) {
            $(".v-tab-list").find("li").removeClass("active");
            $(ob).closest("li").addClass("active");
            $(".ajax-loader").fadeIn(200);
            $(".programm-list-block").load('{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/index/video-archive-programm-list-block") }}'+ "/" + date_start + "/" + date_end + "/<?php echo App::getLocale();?>");
            $(".programm-item-list-block").empty();
        }

        function setProgrammItemListByPrDate(date_start, date_end, programm_id) {
            $(".ajax-loader").fadeIn(200);
            $(".programm-list-block").find("li").removeClass("active");
            $("." + programm_id).addClass("active");
            $(".programm-item-list-block").empty();
            $(".programm-item-list-block").load('{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/index/video-archive-programm-item-list-block") }}'+ "/" + date_start + "/" + date_end + "/" + programm_id + "/<?php echo App::getLocale();?>");
        }

        function setAllProgrammArchive(ob, year) {
            $(".ajax-loader").fadeIn(200);
            $(".v-tab-list").find("li").removeClass("active");
            $(ob).closest("li").addClass("active");
            $(".programm-list-block").empty();
            $(".programm-list-block").load('{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/index/all-video-archive-programm-list-block") }}'+"/<?php echo App::getLocale();?>?year="+year);
            $(".programm-item-list-block").empty();
            $(".programm-item-list-block").load('{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/index/all-video-archive-programm-item-list-block") }}'+"/1/<?php echo App::getLocale();?>/0?year="+year);
        }

        function setAllProgrammItemListByPrDate(programm_id,ob, year) {
            $(".ajax-loader").fadeIn(200);
            $(".programm-list-block").find("li").removeClass("active");
            $(ob).closest("li").addClass("active");
            $(".programm-item-list-block").empty();
            $(".programm-item-list-block").load('{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/index/all-video-archive-programm-item-list-block") }}'+"/1/<?php echo App::getLocale();?>/" + programm_id+"?year="+year);
        }

        function setAllProgrammArchive2(ob, year) {
            $(".ajax-loader").fadeIn(200);
            $(".programm-list-block").find("li").removeClass("active");
            $(ob).closest("li").addClass("active");
            $(".programm-list-block").empty();
            $(".programm-list-block").load('{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/index/all-video-archive-programm-list-block") }}'+"/<?php echo App::getLocale();?>?year="+year);
            $(".programm-item-list-block").empty();
            $(".programm-item-list-block").load('{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/index/all-video-archive-programm-item-list-block") }}'+"/1/<?php echo App::getLocale();?>/0?year="+year);
        }
    </script>

@endsection