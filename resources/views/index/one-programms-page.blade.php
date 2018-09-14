@extends('index.layout')

@section('content')
    <? use App\Models\VideoArchive; ?>
    @if(strlen($row['image']) > 0)
        <div class="tv-project-header" style="min-height: initial;"><img class="img-responsive" src='/programm_photo/{{$row['image']}}' alt=""></div>
    @endif

    <div class="tv-project v-tabs w-side">
        <aside>
            <ul class="v-tab-list">
                <li class="active"><a>Выпуски программ</a></li>
            </ul>
        </aside>

        <article>
            <div class="aside">
                <div id="releases-archive">
        <?      $monthes = array(
                            1 => trans("messages.Январь"), 2 => trans("messages.Февраль"), 3 => trans("messages.Март"), 4 => trans("messages.Апрель"),
                            5 => trans("messages.Май"), 6 => trans("messages.Июнь"), 7 => trans("messages.Июль"), 8 => trans("messages.Август"),
                            9 => trans("messages.Сентябрь"), 10 => trans("messages.Октябрь"), 11 => trans("messages.Ноябрь"), 12 => trans("messages.Декабрь"));
                $archive_years = DB::select("SELECT DISTINCT YEAR(video_archive_date) as year FROM video_archive_tab where programm_id = " . $row['programm_id'] . " order by YEAR(video_archive_date) desc");
                $i = 0;
        ?>
                @if(count($archive_years) > 0)
                    @foreach($archive_years as $key => $archive_year)
                        <?  $i++; ?>
                        <div class="panel">
                            <a href="#archive-{{$archive_year->year}}" role="button" data-toggle="collapse" data-parent="#releases-archive" aria-expanded="true" aria-controls="archive-{{$archive_year->year}}">{{$archive_year->year}}</a>
                            <div class="collapse @if($i == 1) in @endif" id="archive-{{$archive_year->year}}">
                                <ul class="v-tab-list">
                            <?      $archive_months = DB::select("SELECT DISTINCT MONTH(video_archive_date) as month FROM video_archive_tab where programm_id = " . $row['programm_id'] . " and year(video_archive_date) = " . $archive_year->year . " order by MONTH(video_archive_date)"); ?>
                                    @if(count($archive_months) > 0)
                                        @foreach($archive_months as $key => $archive_month)
                                        <?  $date_begin =  date("Y-m-d", strtotime($archive_year->year . "-" . $archive_month->month . "-01"));
                                            $date_end =  date("Y-m-d", strtotime($archive_year->year . "-" .$archive_month->month . "-" . date("t", strtotime($date_begin))));
                                        ?>
                                            @if($i == 1)
                                                <script>
                                                    $(document).ready(function(){
                                                        setProgrammArchiveByDate('{{$date_begin}}','{{$date_end}}', {{$row['programm_id']}},1);
                                                    });
                                                </script>
                                            @endif
                                            <li>
                                                <a style="cursor: pointer;" onclick="setProgrammArchiveByDate('{{$date_begin}}','{{$date_end}}', {{$row['programm_id']}},1)">{{$monthes[$archive_month->month]}}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endforeach
                @endif
                </div>
            </div>
            <div class="row programm-archive-list">

            </div>

            <div class="tv-project-text">
                <?php echo $row['programm_description_' . App::getLocale()]; ?>
            </div>
        </article>
    </div>

    <script>
        function setProgrammArchiveByDate(date_begin, date_end,programm_id,page){
            $(".ajax-loader").fadeIn(200);
            $(".programm-archive-list").empty();
            $(".programm-archive-list").load("/index/set-programm-archive-by-date/" + date_begin + "/" + date_end + "/<?php echo App::getLocale();?>/" + programm_id + "/" + page);
        }
    </script>
@endsection