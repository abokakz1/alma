@extends('index.layout')

@section('content')
	<div class="page-header">
        <div class="back-link-block">
            <a href="https://almaty.tv">
                <i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
        </div>

        <div class="page-title-block">
            <h1 class="page-title">{{ trans("messages.about_us") }}</h1>
            <h1 class="page-title" style="margin-left: 25px;">
            	<a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), '/history/') }}" style="color: #91ba42;">{{trans("messages.history")}}</a>
            </h1>
        </div>
    </div>

    <div class="v-tabs archive-tabs">
    	<aside>
            <ul class="v-tab-list">
            @foreach($job_positions as $key=>$jb)                                
	          	<li @if($key==0)class="active"@endif>
	                <?php 
                        $pieces = explode(" ", $jb['name']);
                        $first_word = array_shift($pieces);
                        $other_word = implode(" ",$pieces);
                    ?>
	                <a style="cursor: pointer" onclick="changeJobPosition({{$jb->id}}, this)">
	                    {{ $first_word }}
	                    <small>{{ $other_word }}</small>
	                </a>
	            </li>
	        @endforeach
			</ul>
		</aside>


		<article>
			<div class="row">
			@foreach($employers as $employer)
				<div class="col-sm-4 fixed-height">
               		<div class="employee_description">
						<div class="avatar">
						@if($employer->image)
							<img src="/blog_photo/{{$employer->image}}" />
						@else
							<img src="/css/image/avatar.png" />
						@endif
						</div>
						<p class="username">
							{{$employer->name}}
						</p>
						<p class="position">
							{{$employer->position}}
						</p>
						<p class="description">
							{{$employer->description}}
						</p>
						<p class="email">
							{{$employer->mail}}
						</p>
						<p class="phone">
							{{$employer->number}}
						</p>
						<div class="row">
							<div class="row_mod">
							@if($employer->fb)
								<div class="about_soc_netw_left"><a href="{{$employer->fb}}"><i class="fa fa-facebook facebook_color" aria-hidden="true"></i></a></div>
							@endif
							@if($employer->insta)
								<div class="about_soc_netw_center"><a href="{{$employer->insta}}"><i class="fa fa-instagram instagram_color" aria-hidden="true"></i></a></div>
							@endif
							@if($employer->twit)
								<div class="about_soc_netw_right"><a href="{{$employer->twit}}"><i class="fa fa-twitter twitter_color" aria-hidden="true"></i></a></div>
							@endif
							@if($employer->vk)
								<div class="about_soc_netw_right"><a href="{{$employer->vk}}"><i class="fa fa-vk twitter_color" aria-hidden="true"></i></a></div>
							@endif
							@if($employer->lin)
								<div class="about_soc_netw_right"><a href="{{$employer->lin}}"><i class="fa fa-linkedin twitter_color" aria-hidden="true"></i></a></div>
							@endif
							</div>
						</div>
               		</div>
                </div>
            @endforeach
			</div>
		</article>
    </div>
<script>
function changeJobPosition(id, obj){
	$(".ajax-loader").fadeIn();
	$("article>.row").empty();
	$(obj).closest("ul").find('li').removeClass("active");
	$(obj).closest("li").addClass("active");
	let message = '{{trans("messages.no_employers")}}';
	$.ajax({
        type: 'GET',
        url: "{{ LaravelLocalization::getLocalizedURL(App::getLocale(), '/about-us') }}",
        data: {id: id},
        success: function(data){
        	console.log(data);
            if(data.employers.length>0){
            	for (var i = 0; i < data.employers.length; i++) {
            		let img = "/css/image/avatar.png";
            		let soc_net = "";
            		if(data.employers[i].image){
            			img = '/blog_photo/'+data.employers[i].image;
            		} if(data.employers[i].fb){
            			soc_net+= "<div class='about_soc_netw_center'><a href='"+data.employers[i].fb+"''><i class='fa fa-facebook instagram_color' aria-hidden='true'></i></a></div>";
            		} if(data.employers[i].insta){
            			soc_net+= "<div class='about_soc_netw_center'><a href='"+data.employers[i].insta+"''><i class='fa fa-instagram instagram_color' aria-hidden='true'></i></a></div>";
            		} if(data.employers[i].twit){
            			soc_net+= "<div class='about_soc_netw_center'><a href='"+data.employers[i].twit+"''><i class='fa fa-twitter instagram_color' aria-hidden='true'></i></a></div>";
            		} if(data.employers[i].vk){
            			soc_net+= "<div class='about_soc_netw_center'><a href='"+data.employers[i].vk+"''><i class='fa fa-vk instagram_color' aria-hidden='true'></i></a></div>";
            		} if(data.employers[i].lin){
            			soc_net+= "<div class='about_soc_netw_center'><a href='"+data.employers[i].lin+"''><i class='fa fa-linkedin instagram_color' aria-hidden='true'></i></a></div>";
            		}
            		$("article>.row").append("<div class='col-sm-4 fixed-height'><div class='employee_description'><div class='avatar'><img src='"+img+"'></div><p class='username'>"+data.employers[i].name+"</p><p class='position'>"+data.employers[i].position+"</p><p class='description'>"+data.employers[i].description+"</p><p class='email'>"+data.employers[i].mail+"</p><p class='phone'>"+data.employers[i].number+"</p><div class='row'><div class='row_mod'>"+soc_net+"</div></div></div></div>");
            		// console.log(data.employers[i]);
            	}
            }
            else{
                $("article>.row").html("<p>"+message+"</p>");
            }
        }
    });
    $(".ajax-loader").fadeOut();
}
</script>
@endsection