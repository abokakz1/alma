@extends('index.layout')

@section('content')
    <div class=" page-header">
        <div class=" back-link-block">
            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class=" icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
        </div>
        <div class=" page-title-block">
            <h1 class=" page-title">{{trans("messages.О канале")}}</h1>
        </div>
    </div>
    <div class=" history">
            @if(App::getLocale() == "kz")
                <div class=" history-item">
                    <div class="history-label"></div>
                    <div class="history-desc">
                        <p>
                            «Алматы ТВ» - алматылықтар үшін өзекті болып табылатын сұрақтарды
                            көтеретін арна деген түсінік қалыптасқан. Маңызды мәселелерді шешуде
                            ақпарат көзі болып табылады, түрлі әлеуметтік топтардың өкілдерімен диалог
                            құрып, үкімет пен халықтың ортақ мақсаттарын –географиялық орнының
                            мүмкіндігін, тарихи ерекшеліктерін және қалалық бірлестіктің әлеуетін
                            қолдана отырып мегаполисті дамыту.
                        </p>

                        <p>
                            Десе де арнаның негізгі тапсырмасы – қаланың өмірі туралы объективті,
                            тексерілген, нақты ақпарат беру. Ол үшін қолда бар барлық құралдарды –
                            ақпарат қызметінің тексерілген жұмыс жүйесін, заманауи жабдықтандыру
                            және эфирге жаңалық шығара отырып, қоғамға қызмет ететін
                            журналистердің таусылмайтын энергиясын қолдану.
                        </p>
                    </div>
                </div>
            @else
                <div class=" history-item">
                    <div class="history-label"><strong>Год основания</strong></div>
                    <div class=" history-desc">
                        <p>
                            Телеканал «Алматы» был создан в апреле 2005 года на базе телевидения «Южная столица».
                            Телеканал имеет коррпункт в столице страны, Астане.
                        </p>
                    </div>
                </div>

                <div class=" history-item">

                    <div class="history-label"><strong>Наш эфир</strong></div>
                    <div class=" history-desc">
                        <p>
                            Принято считать «АлматыТВ» - каналом, который поднимает острые для всех алматинцев
                            вопросы. Является источником информации для решения насущных проблем горожан,
                            настраивает диалог между представителями различных социальных групп, объединяет
                            намерения власти и населения — развивать современный мегаполис, общими усилиями
                            использовать возможности географического расположения, исторических особенностей, и
                            несомненно — креативного потенциала городского сообщества.
                        </p>
                        <p>
                            Но, наиболее приоритетной задачей телеканала является — выдавать городу объективную,
                            проверенную, точную информацию о жизни города. Используя для этого все имеющиеся
                            инструменты — проверенную систему работы информационной службы, современное
                            оснащение и неиссякаемую энергию журналистов, настроенных служить обществу, выдавая
                            в эфир новости.
                        </p>

                        <p>
                            Собственные программы канала подстраиваются под трендовые в обществе темы и форматы.
                            Объединяет их намерение творческих команд телепроектов — отвечать меняющимся
                            запросам телеаудитории, ориентируясь на хороший и требовательный вкус изысканного
                            зрителя, а также — формируя и развивая прогрессивное мышление, патриотизм, критический
                            взгляд, стремление к успеху и настрой на совершенствование нравственной, экологической,
                            правовой, демократической, художественной, физической и коммуникативной культуры.
                        </p>

                        <p>
                            Эфирные сетки вещания каналов включают новости, сериалы, художественные,
                            документальные фильмы и программы, которые могут удовлетворить запросы самой
                            взыскательной аудитории. Не менее 55% среднесуточного вещания осуществляется на
                            государственном языке.
                        </p>
                    </div>
                </div>
            @endif

        <div class="history-item">
            <div class="history-label"><strong>Команда "Алматы тв"</strong></div>
            <div class="history-desc">
                <div class="row">
                    <div class="col-md-4 col-sm-6">
                        <div class="staff-item">
                            <div class="staff-item-img" style="background-image: url('/css/images/staff/alibek.png')"></div>
                            <div class="staff-item-title"><strong>ӘЛДЕНЕЙ ӘЛІБЕК ҮСЕНҰЛЫ</strong>
                                @if(App::getLocale() == "kz")
                                    <small>Бас директор</small>
                                @else
                                    <small>Генеральный директор</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="staff-item">
                            <div class="staff-item-img" style="background-image: url('/css/images/staff/rita.png')"></div>
                            <div class="staff-item-title">
                                @if(App::getLocale() == "kz")
                                    <strong>ЖУГУНУСОВА РИТА КЕНЖАЛОВНА</strong>
                                    <small>Бас директордың орынбасары</small>
                                @else
                                    <strong>Жүгінісова  РИТА КЕНЖАЛОВНА</strong>
                                    <small>Заместитель генерального директора</small>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="staff-item">
                            <div class="staff-item-img" style="background-image: url('/css/images/staff/ilyan.png')"></div>
                            <div class="staff-item-title"><strong>ИЛЯН ДАВИД САСУНОВИЧ</strong>
                                @if(App::getLocale() == "kz")
                                    <small>Коммерциялық директор</small>
                                @else
                                    <small>Коммерческий директор</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>

                    <div class="col-md-4 col-sm-6">
                        <div class="staff-item">
                            <div class="staff-item-img" style="background-image: url('/css/images/staff/ergen.png')"></div>
                            <div class="staff-item-title">
                                @if(App::getLocale() == "kz")
                                    <strong>ТОҚМҰРЗИН ЕРГЕН ҚҰРАЛҰЛЫ</strong>
                                    <small>Бас режиссер</small>
                                @else
                                    <strong>ТОКМУРЗИН ЕРГЕН КУРАЛОВИЧ</strong>
                                    <small>Главный режиссер </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="staff-item">
                            <div class="staff-item-img" style="background-image: url('/css/images/staff/bek.png')"></div>
                            <div class="staff-item-title">
                                @if(App::getLocale() == "kz")
                                    <strong>КЕНЖЕБАЙ БЕК МАЗДАТҰЛЫ</strong>
                                    <small>Бағдарлама директоры</small>
                                @else
                                    <strong>КЕНЖЕБАЙ БЕК МАЗДАТҰЛЫ</strong>
                                    <small>Программный директор </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="staff-item">
                            <div class="staff-item-img" style="background-image: url('/css/images/staff/zhandos.png')"></div>
                            <div class="staff-item-title">
                                @if(App::getLocale() == "kz")
                                    <strong>ҚҰРМАНБАЕВ ЖАНДОС ҚОНЫСПАЙҰЛЫ </strong>
                                    <small>Даму жөніндегі директор</small>
                                @else
                                    <strong>КУРМАНБАЕВ ЖАНДОС КОНСПАЕВИЧ</strong>
                                    <small>Директор по развитию </small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div class="col-md-4 col-sm-6">
                        <div class="staff-item">
                            <div class="staff-item-img" style="background-image: url('/css/images/staff/talgat.png')"></div>
                            <div class="staff-item-title">
                                @if(App::getLocale() == "kz")
                                    <strong>ТАЛҒАТ ҚҰСПАЕВ  </strong>
                                    <small>Ақпараттық қызмет дирекциясының жетекшісі</small>
                                @else
                                    <strong>КУСПАЕВ ТАЛГАТ ИМАНГАЛИЕВИЧ</strong>
                                    <small>Руководитель дирекции информационной службы  </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="staff-item">
                            <div class="staff-item-img" style="background-image: url('/css/images/staff/umbetali.png')"></div>
                            <div class="staff-item-title">
                                @if(App::getLocale() == "kz")
                                    <strong>УМБЕТАЛИ МЕЙМЕШ</strong>
                                    <small>Қазақ тілінде ақпарат тарату бөлімінің шеф-редакторы</small>
                                @else
                                    <strong>УМБЕТАЛИ МЕЙМЕШ</strong>
                                    <small>Шеф-редактор информационного вещания на казахском языке </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="staff-item">
                            <div class="staff-item-img" style="background-image: url('/css/images/staff/ardak.png')"></div>
                            <div class="staff-item-title">
                                @if(App::getLocale() == "kz")
                                    <strong>АРДАҚ СЕЙТҚАЗИН </strong>
                                    <small>Орыс тілінде ақпарат тарату бөлімінің шеф-редакторы</small>
                                @else
                                    <strong>АРДАК СЕЙТКАЗИН</strong>
                                    <small>Шеф-редактор информационного вещания на русском языке  </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection