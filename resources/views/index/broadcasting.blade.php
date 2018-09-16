@extends('index.layout')

@section('content')
    <?  $tv_broadcast_row = DB::select("
                                            SELECT t.*, t1.image as category_image, t1.category_name_kz, t1.category_name_ru, t1.category_name_en, DATE_FORMAT(t.date,'%d.%m.%Y') as date
                                            from tv_programm_tab t
                                            left join category_tab t1 on t.category_id = t1.category_id
                                            where t.date = date_format(now(), '%Y-%m-%e')
                                            and   unix_timestamp(concat(t.date,' ', t.time)) <= unix_timestamp(now())
                                            and   case when t.time_end like '00:%'
                                                      THEN
                                                        unix_timestamp(concat(t.date,' ', CASE WHEN t.time_end like '00:%' THEN concat('23',substr(t.time_end,3)) ELSE t.time_end END))+3600 >= unix_timestamp(now())
                                                      else
                                                        unix_timestamp(concat(t.date,' ', t.time_end)) >= unix_timestamp(now())
                                                      end
                                            order by unix_timestamp(concat(t.date,' ', t.time)) desc
                                            limit 1
                                           ");
    ?>

    <div class="online-header">
        <div class="col-md-3 col-sm-4 col-np">
            <div class="back link-block">
                <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
            </div>
            <div class="online-text-block">
                <h1 class="online-title">
                    @if(isset($tv_broadcast_row[0]))
                        @if(App::getLocale() == "kz")
                            {{$tv_broadcast_row[0]->category_name_kz}}
                        @elseif(App::getLocale() == "en")
                            {{$tv_broadcast_row[0]->category_name_en}}
                        @else
                            {{$tv_broadcast_row[0]->category_name_ru}}
                        @endif
                    @endif
                </h1>
                <p class="online-status">Сейчас в эфире</p>
                <p><a class="text-green" href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/programma-peredach/") }}"><i class="icon icon-schedule-green mr-10"></i>{{trans("messages.Программа телепередач")}}</a></p>
                <div class="online-desc">
                    @if(isset($tv_broadcast_row[0]))
                        @if(App::getLocale() == "kz")
                            {{$tv_broadcast_row[0]->tv_programm_description_kz}}
                        @elseif(App::getLocale() == "en")
                            {{$tv_broadcast_row[0]->tv_programm_description_en}}
                        @else
                            {{$tv_broadcast_row[0]->tv_programm_description_ru}}
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-np">
        @if(!isset($tv_broadcast_row[0]))

            @if(App::getLocale() == "kz")
                <p style="margin:20px">Бүгін бағдарлама толтырылмады</p>
            @else
                <p style="margin:20px">Сегодня программу не заполняли</p>
            @endif

        @else

            @if(strlen($tv_broadcast_row[0]->image) > 0)
                <div class="article-header-img op-darked" style="background-image: url('/tv_programm_photo/{{$tv_broadcast_row[0]->image}}'">
            @else
                <div class="article-header-img op-darked" style="background-image: url('/category_photo/{{$tv_broadcast_row[0]->category_image}}'">
            @endif
                <div class="img-item-content">
                    <h1 class="article-title">
                        @if(App::getLocale() == "kz" && isset($tv_broadcast_row[0]->tv_programm_name_kz))
                            {{$tv_broadcast_row[0]->tv_programm_name_kz}}
                        @elseif(App::getLocale() == "ru" && isset($tv_broadcast_row[0]->tv_programm_name_ru))
                            {{$tv_broadcast_row[0]->tv_programm_name_ru}}
                        @endif
						<br>
						@if(isset($tv_broadcast_row[0]->time) && isset($tv_broadcast_row[0]->time_end))
							{{$tv_broadcast_row[0]->time}} - {{$tv_broadcast_row[0]->time_end}}
						@endif
                    </h1>
                </div>
            </div>

        @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="article-content">
                <? use App\Models\TranslationLink;
                $translation_link = TranslationLink::first();
                ?>
                <div class="embed-responsive embed-responsive-16by9">
				<iframe src="//playercdn.cdnvideo.ru/aloha/players/almatytv_player.html"  frameborder="0" width="100%" height="390" scrolling="no" style="overflow:hidden;" allowfullscreen></iframe>
				</div>
            </div>
        </div>
    </div>
@endsection