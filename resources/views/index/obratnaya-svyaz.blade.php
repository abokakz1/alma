@extends('index.layout')

@section('content')
    <div class="page-header">
        <div class="back-link-block">
            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
        </div>
        <div class="page-title-block">
            <h1 class="page-title">{{trans("messages.Контакты")}}</h1>
        </div>
    </div>
    <div class="content-block">
        @if(App::getLocale() == "kz")
            <p><strong>Мекен-жайы:</strong> Қазақстан Республикасы, Алматы қаласы, Ғабдуллин көшесі,88А</p>
            <p><br></p>
            <p><strong class="text-primary">Редакция:</strong></p>
            <p><strong>Whatsapp:</strong> +7 (747) 750 25 02</p>
            <p><strong>Жаңалықтар қызметі:</strong> +7 (727) 266 39 67, e-mail: koordinator@almaty.tv</p>
            <p><strong>Бағдарлама бөлімі:</strong> +7 (727) 275 30 10</p>
            <p><strong>Қабылдау бөлімі:</strong> +7 (727) 275 01 01</p>
            <p><strong>Интернет жобалар редакциясы:</strong> +7 (727) 274 25 37</p>
            <p><strong>Бухгалтерия:</strong> +7 (727) 275 20 22</p>
            <p><strong>Жарнама бөлімі:</strong> +7 (727) 275 22 22, +7 707 104 15 00</p>
        @else
            <p><strong>Адрес:</strong> Казахстан, г. Алматы, ул. Габдуллина 88А.</p>
            <p><br></p>
            <p><strong class="text-primary">Редакция:</strong></p>
            <p><strong>Whatsapp:</strong> +7 (747) 750 25 02</p>
            <p><strong>Новостная служба:</strong> +7 (727) 266 39 67, e-mail: koordinator@almaty.tv</p>
            <p><strong>Программный отдел:</strong> +7 (727) 275 30 10</p>
            <p><strong>Приемная:</strong> +7 (727) 275 01 01</p>
            <p><strong>Редакция интернет-проектов:</strong> +7 (727) 274 25 37</p>
            <p><strong>Бухгалтерия:</strong> +7 (727) 275 20 22</p>
            <p><strong>Рекламный отдел:</strong> +7 (727) 275 22 22, +7 707 104 15 00</p>
        @endif
        <p><br></p>
        <p><br></p>

        <div class="feedback-form">
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <h4 class="text-red">{{trans("messages.Обратная связь")}}:</h4>
                    <form id="myform" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control request-name-input" type="text" name="name" placeholder="{{trans("messages.Ваше имя")}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control request-email-input" type="text" name="email" placeholder="{{trans("messages.Ваш e-mail")}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control request-mess-input" name="mess" rows="5" placeholder="{{trans("messages.Ваше сообщение")}}"></textarea>
                        </div>
                        <button class="btn btn-primary" type="button" onclick="sendRequest()"><i class="icon icon-envelope-white"></i>{{trans("messages.Отправить")}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sendRequest(){
            if($(".request-name-input").val().trim().length > 0 && $('.request-email-input').val().trim().length > 0 && $(".request-mess-input").val().trim().length > 0){
                $.ajax({
                    type: 'GET',
                    url: "/index/send-request",
                    data: $("#myform").serialize(),
                    success: function(data){
                        if(data.result == "true"){
                            alert("Ваше сообщение отправлено");
                            document.getElementById("myform").reset();
                        }
                        else{
                            alert("Ошибка при отправке");
                        }
                    }
                });
                return false;
            }
            else{
                alert("Введите все данные");
            }
        }
        String.prototype.trim = function() {
            return this.replace(/^\s+|\s+$/g, "");
        };
    </script>
<!--END_CONTENT_CONTACTS--->
@endsection