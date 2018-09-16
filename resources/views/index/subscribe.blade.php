<div class="full-container bg-container">
    <div class="container">
        <form class="row subscribe" id="delivery_form2" method="POST" onsubmit="addDelivery2()">
        {{ csrf_field() }}
            <div class="col-xs-12 col-md-3">
                @if(App::getLocale() == 'kz')
                    <p class="text-left">
                        <i class="fa fa-envelope"></i>
                        <br>
                        {{ trans('messages.subscribed_news') }}
                        <br><strong>{{ trans('messages.subscribe_to') }}</strong>
                    </p>
                @else
                    <p class="text-left">
                        <i class="fa fa-envelope"></i>
                        <br>
                        {{ trans('messages.subscribe_to') }}
                        <br><strong>{{ trans('messages.subscribed_news') }}</strong>
                    </p>
                @endif

            </div>
            <div class="col-xs-12 col-md-9 subscribe-input">
                <div class="input-group">
                    <input type="email" class="delivery_email form-control input-lg" name="delivery_email" placeholder="{{ trans('messages.enter_email') }}">
                    <span class="input-group-btn">
                        <button type="button" onclick="addDelivery2()" class="btn btn-default btn-lg">{{ trans('messages.subscribe') }}</button>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ADDED FROM EVENTS MODIFICATION -->
<script>
    function addDelivery2(){
        if($(".delivery_email").val().length > 0){
            $.ajax({
                type: 'POST',
                url: "/index/send-delivery",
                data: $("#delivery_form2").serialize(),
                success: function(data){
                    if(data.result == "true"){
                        alert("Вы успешно подписаны на новости");
                        document.getElementById("delivery_form").reset();
                    }
                    else{
                        alert(data.value);
                    }
                }
            });
            return false;
        }
        else{
            alert("Введите email");
        }
        return false;
    }
</script>