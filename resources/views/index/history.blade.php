@extends('index.layout')

@section('content')
<div class="page-header">
    <div class="back-link-block">
        <a href="https://almaty.tv">
            <i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
    </div>

    <div class="page-title-block">
        <h1 class="page-title">
            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), '/about-us/') }}" style="color: #91ba42;">{{trans("messages.about_us")}}</a>
        </h1>
        <h1 class="page-title" style="margin-left: 25px;">{{trans("messages.history")}}</h1>
    </div>
</div>

<div class="v-tabs archive-tabs">
	<aside>
        <ul class="v-tab-list">                                                                                                               
          	<li class="history_li">
                <a style="cursor: pointer" onclick="showBeginHistory()">
                    НАЧАЛО
                    <small>ИСТОРИИ</small>
                </a>
            </li>

			<li class="auditory_li">
                <a style="cursor: pointer" onclick="showAuditoryChannel()">
                    АУДИТОРИЯ
                    <small>ТЕЛЕКАНАЛА</small>
                </a>
            </li>

            <li class="channel_li">
                <a style="cursor: pointer" onclick="showChannelForCity()">
                    ТЕЛЕКАНАЛ
                    <small>ДЛЯ ГОРОДА</small>
                </a>
            </li>
		</ul>
	</aside>
	<script>
        $(document).ready(function(){
            showBeginHistory();
        });
    </script>
	<script>
        function showBeginHistory(){
            $(".auditory_channel").css("display","none");
            $(".channel_for_city").css("display","none");
            $(".auditory_li").removeClass("active");
            $(".channel_li").removeClass("active");
            $(".history_li").addClass("active");
            $(".begin_history").css("display","block");
        }

        function showAuditoryChannel(){
            $(".auditory_channel").css("display","block");
            $(".channel_for_city").css("display","none");
            $(".auditory_li").addClass("active");
            $(".channel_li").removeClass("active");
            $(".history_li").removeClass("active");
            $(".begin_history").css("display","none");
        }

        function showChannelForCity(){
            $(".auditory_channel").css("display","none");
            $(".channel_for_city").css("display","block");
            $(".auditory_li").removeClass("active");
            $(".channel_li").addClass("active");
            $(".history_li").removeClass("active");
            $(".begin_history").css("display","none");
        }
    </script>


	<article>
		<div class="begin_history">
			<div class="row">
				<h3>1999 год – начало истории</h3>	
				<p>Телеканал «Алматы» -  эфирный бренд АО «ТРК «Южная Столица», создан на базе городского телевидения и ведет свою историю с 1999 года.  В 2010 году из регионального вещателя начал развиваться как республиканский канал и в феврале 2016 года вошел в список национального цифрового вещания с охватом на все регионы страны. Среднесуточное время вещания – 17 часов. Телеканал имеет коррпункт в столице страны, Астане.</p>
			</div>
		</div>
		<div class="auditory_channel">
			<div class="row">
				<h3>Аудитория телеканала</h1>
				<p>Как гид по экономическим, политическим, культурным и социально-значимым событиям, телеканал «Алматы» представляет объективную, проверенную, точную информацию о жизни города. Используя для этого все имеющиеся инструменты — проверенную систему работы информационной службы, современное оснащение и неиссякаемую энергию журналистов, настроенных служить обществу, выдавая в эфир новости. Нас можно смотреть в Алматы и Алматинской области. А также через республиканское спутниковое телевидение “Отау ТВ” в любом уголке Казахстана.

				“Каждому алматинцу по программе”
				Основу вещания телеканала составляют ежедневные новости в прямом включении о событиях в южной столице, стране и мире, еженедельные информационно-аналитические программы, познавательные программы, удовлетворяющие запросы самой взыскательной аудитории («Айтарым бар», «Есть мнение»,  «Алматинские истории», «Тарихқа толы Алматы», «Мегаполис молодых», «Жастар қаласы»), социальные ток-шоу, затрагивающие острые и наболевшие женские вопросы («Тумарым») журналистские расследования, экспертные оценки событий, обзор спортивной жизни города и страны, а также программы с нужной и полезной для зрителя информацией о жилищно-коммунальном хозяйстве («Обо всем без купюр», «Байлам»).
				Любителям кино телеканал «Алматы» предлагает остросюжетные и любовные коллизии, которые разворачиваются в сериалах казахстанского, российского, американского, китайского и турецкого производства. По выходным дням представляем любимые картины из золотых коллекций советского, казахстанского, американского кино. Не менее 55% среднесуточного вещания осуществляется на государственном языке.</p>
			</div>
		</div>
		<div class="channel_for_city">
			<div class="row">
				<h3>Телеканал для города</h3>
				<p>Команда телеканала «Алматы» - это профессионалы, работающие над созданием контента для ежедневного эфира, специалисты, умеющие передать главные информационные тренды общества, найти ракурс для полноценной подачи остросоциальных и исторически значимых вопросов.
				Телеканал «Алматы» является источником информации для решения насущных проблем горожан, настраивает диалог между представителями различных социальных групп, объединяет намерения власти и населения — развивать современный мегаполис, общими усилиями использовать возможности географического расположения, исторических особенностей, и, несомненно — креативного потенциала городского сообщества.
				Телеканал «Алматы» ставит перед собой задачу стать мобильной, технологичной и инновационной телекомпанией, поставщиком деловых новостей, глубокой аналитики, качественных культурно-познавательных и развлекательных программ.</p>
			</div>
		</div>
	</article>
</div>
@endsection