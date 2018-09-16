@extends('index.layout')

@section('content')
    <div class="page-header">
        <div class="back-link-block">
            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
        </div>
        <div class="page-title-block">
            <h1 class="page-title">{{trans("messages.Вакансии")}}</h1>
        </div>
    </div>
    <div class="vacancies v-tabs pr-lg">
        <aside>
            <ul class="v-tab-list red">
                <? $vacancy_id = 0; ?>
                @if(count($vacancy_row) > 0)
                    <?php  $i = 0; ?>
                    @foreach($vacancy_row as $key => $vacancy_item)
                <?php   $i++; ?>
                        @if($i == 1)
                            <?php $vacancy_id = $vacancy_item['vacancy_id']?>
                        @endif
                        <li @if($i == 1) class="active" @endif>
                            <a onclick="showVacancyBlock({{$vacancy_item['vacancy_id']}})" data-toggle="tab" href="#tab-{{$i}}" data-id="{{$vacancy_id}}">{{$vacancy_item['vacancy_position_name_' . App::getLocale()]}}</a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </aside>

        <article>
            <div class="tab-content">
                @if(count($vacancy_row) > 0)
                    <?php  $i = 0;?>
                    @foreach($vacancy_row as $key => $vacancy_item)
                    <?php       $i++; ?>
                        @if($i == 1)
                            <input type="hidden" value="{{$vacancy_item['vacancy_id']}}" id="cur_vacancy_id">
                        @endif

                        <h3 class="v-tab-title"><a data-toggle="tab" href="#tab-{{$vacancy_item['vacancy_id']}}">{{$vacancy_item['vacancy_position_name_' . App::getLocale()]}}</a></h3>

                        <div class="tab-pane @if($i == 1) active @endif" id="tab-{{$i}}">
                            <?php echo $vacancy_item['vacancy_description_' . App::getLocale()];?>
                        </div>
                    @endforeach
                @else
                    <h3 class="v-tab-title"><a data-toggle="tab" href="#tab-1"></a></h3>
                    <div class="tab-pane active" id="tab-1">
                        @if(App::getLocale() == "kz")
                            Қазіргі таңда телеарнамызда бос жұмыс орны жоқ. Жаңалықтардан тыс қалмаңыздар.
                        @else
                            В настоящее время открытых вакансий на телеканале нет. Следите за обновлениями.
                        @endif
                    </div>
                @endif

                <div class="vacancies-form">
                    @if(count($vacancy_row) > 0)
                        <form id="myform" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" value="{{$vacancy_id}}" id="vacancy_id">
                            <div class="form-group"><label class="text-green mr-10">{{trans("messages.Прикрепить файл")}}:</label>
                                <button class="file-select-btn btn btn-default btn-sm mr-10" type="button">{{trans("messages.Выбрать файл")}}
                                </button>
                                <input class="hidden" id="response_file" type="file" name="response_file"><span class="help-block">{{trans("messages.(в формате .doc, .docx, .pdf.) Размер файла не должно быть выше 2 мб")}}</span>
                            </div>
                            <button class="btn btn-primary vak-button" type="submit"><i class="icon icon-paper-white"></i>{{trans("messages.Откликнуться на вакансию")}}</button>
                        </form>
                    @endif
                </div>
            </div>
        </article>
    </div>

    <script>
        $(document).ready(function () {
            $('.vacancies-form').on('click', '.file-select-btn', function () {
                $(this).next('input[type="file"]').click();
            });
        });
        function showVacancyBlock(vacancy_id){
            $(".vacancy-item").css("display","none");
            $(".vacancy-item" + vacancy_id).fadeIn(200);
            $("#cur_vacancy_id").attr("value",vacancy_id);
            $("#vacancy_id").attr("value",vacancy_id);
        }

        $("#myform").submit(function(event){
            if($("#vacancy_id").val() < 1){
                alert("Вакансия не выбрана");
                return;
            }
            //disable the default form submission
            event.preventDefault();

            //grab all form data
            var formData = new FormData($(this)[0]);

            $.ajax({
                url:'/index/send-vacancy-response/' + $("#vacancy_id").val(),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if(data.success == true){
                        $("#response_file").val('');
                        alert("Ваш отклик успешно отправлен");
                    }
                    else if(data.success == "not_file"){
                        alert("Прикрепите файл");
                    }
                    else{
                        alert("Ошибка при отправке отклика");
                    }
                }
            });
        });
    </script>
@endsection