$(document).ready(function() {
    if (document.getElementById("avatar")){
        document.getElementById("avatar").onchange = function () {
            document.getElementById("avatar_url").value = this.value;
        };
    }


    $('a[data-toggle="tab"]').click(function(){
        $('a[href!="'+ $(this).attr('href') + '"]').parent( "li" ).removeClass('active');
    });


    $('.btn-subscription').click(function(){

        var elem = $(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.post("/api/subscribe",
            {
                email: $(this).data('subscribe'),
                lang: $(this).data('lang'),

            },
            function(data, status){
                elem.toggleClass('follow');
                elem.text(data.input);
                if (data.state === 'followed'){
                    elem.closest('.card').find('.li-counter').text(parseInt(elem.closest('.card').find('.li-counter').text()) + 1);
                }
                else {
                    elem.closest('.card').find('.li-counter').text(parseInt(elem.closest('.card').find('.li-counter').text()) - 1);
                }
            });
    });

    $('#following-form').submit(function (e) {
        var form = $(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.post("/api/users",
            {
                user: $('#user').val(),
                search: $('#searchUser').val(),
            },
            function(data, status){
                console.log(data);
            });


        e.preventDefault();
    })

});


