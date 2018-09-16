<div class="modal fade" id="congratulations" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <p class="text-center" style="font-size: 30px">{{ trans('messages.reg_congratulations') }}</p>
            </div>
            <div class="modal-body">
                <a href="#" class="btn btn-subscription follow" id="voiti-button" style="display: table;margin: 0 auto">{{ trans('messages.login') }}</a>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#voiti-button').click(function(){
        $('#congratulations').modal('toggle');
        $('#login').modal('show');
        activaTab('login-form');
    });
});

function activaTab(tab){
  $('#login .nav-pills a[href="#' + tab + '"]').tab('show');
  $('#login .nav-pills a[href="#' + tab + '"]').closest("ul").children().removeClass('active');
  $('#login .nav-pills a[href="#' + tab + '"]').parent().addClass('active');
};
</script>