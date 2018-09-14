@extends('admin.layout')

@section('content')
    <p>Добро пожаловать в Личный Кабинет!</p>

    <? use App\Models\TranslationLink;
        $translation_link = TranslationLink::first();
    ?>
    <div class="left-block">
        <p>Ссылка на трансляцию </p>
    </div>
    <div class="right-block">
        <input type="text" id="link" value="{{$translation_link->link}}">
    </div>

    <div style="margin-top: 10px; float: left;">
        <input type="button" class="btn btn-primary" value="Сохранить" onclick="saveTranslationLink({{$translation_link->translation_link_id}})" style="line-height: normal;">
    </div>
    <div class="clearfloat"></div>

    <script>
        function saveTranslationLink(translation_link_id){
            $.ajax({
                type: 'GET',
                url: "/admin/save-translation-link",
                data: {_token: CSRF_TOKEN, translation_link_id: translation_link_id, link: $("#link").val()},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при сохранении ссылки");
                    }
                    else{
                        showInfo("Ссылка успешно сохранена");
                    }
                }
            });
        }
    </script>
@endsection