<div class="col-sm-3 cool-md-3 sidebar-two">
    <ul>
        @foreach($main as $m)
        <li>
            <a class="perehod" @if($m->type != "empty") href="/about/{{$m->url}}" @endif>
                <span class="accordion-toggle collapsed" data-toggle="collapse" href="#collapse{{$m->id}}"></span>
                <span class="menu-text button1" data-toggle="collapse" href="#collapse{{$m->id}}">{{$m->name}}</span>
            </a>
        </li>
        <div id="collapse{{$m->id}}" class="panel-collapse collapse">
            <?php 
            $subitems = $row->filter(function ($value, $key) use($m){
                return $value->parent_id == $m->id;
            });?>
            @if(count($subitems)>0)
            <ul class="list-group">
                @foreach($subitems as $s)
                <li class="list-group-item @if($current->url == $s->url) active @endif">
                    <a @if($s->type != "empty") href="/about/{{$s->url}}" @endif>{{$s->name}}</a>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
        @endforeach
    </ul>
</div>
<script>
$(".list-group li a").click(function() {
  $(".list-group li").removeClass('active');
    $(this).parent().addClass('active');
});
$(document).ready(function(){
	$('.row-two li.active').closest('div.panel-collapse').removeClass('collapse').addClass('in');//.toggle();

    if (window.location == 'https://almaty.tv/about/o-nas') {
        $('#collapse28').removeClass('collapse').addClass('in')
    }


});

$('.perehod').click(function() {
    $(this)[0].hasAttribute("href") ? window.location = $(this).attr('href') : null;
});

// jQuery('.button1').click(function() {
//     if(jQuery(this).attr("href")=="#collapse28"){
//             window.location = "https://almaty.tv/about/o-nas";
//             alert('msg');  
//     }
// });
</script>
