<div class="fluid-container second-header_background">
<div class="container second-header">
	<div class="row">
	@if(!Auth::check())
		<a data-toggle="modal" data-target="#login"><button class="btn btn-av-default" >{{ trans('messages.write_blog') }}</button></a>
		<!-- <a data-toggle="modal" data-target="#login"><button class="btn btn-av-default">{{ trans('messages.add_event') }}</button></a> -->

		<a data-toggle="modal" data-target="#login" class="text-right" style="float: right;"><button class="btn  btn-av-default">{{ trans('messages.login_another') }}</button></a>        
    @else
        <a href="/blogger/blog-edit/0"><button class="btn btn-av-default" >{{ trans('messages.write_blog') }}</button></a>
		<!-- <a href="#"><button class="btn btn-av-default">{{ trans('messages.add_event') }}</button></a> -->

		<!-- <a href="/admin/logout" class="text-right" style="float: right;"><button class="btn btn-av-default">{{ trans('messages.logout') }}</button></a> -->
		
		<div class="btn-group" role="group" style="float: right;">
			<a type="button" class="btn user-icon dropdown-toggle" aria-haspopup="true" aria-expanded="false"  data-toggle="dropdown">
	        	<span class="glyphicon glyphicon-user" aria-hidden="true" style="font-size: 19px;color: #fff"></span>
	        	<span class="name">{{ Auth::user()->fio }}</span>
	    	</a>
		    <ul class="dropdown-menu user-second-dropdown dropdown-menu-right" aria-labelledby="dLabel">
		        <li>
		            <a href="/bloggers/{{Auth::user()->username}}">{{trans("messages.profile")}}</a>
		        </li>
		        <li>
		            <a href="/blogger/blog-list/{{Auth::user()->user_id}}">{{trans("messages.my_blogs")}}</a>
		        </li>
		        <li>
		            <a href="/blogger/blog-edit/0">{{trans("messages.write_blog")}}</a>
		        </li>
		        <li>
		            <a href="/admin/logout">{{trans("messages.logout")}}</a>
		        </li>
		    </ul>
		</div>

    @endif
		
	</div>
</div>
</div>

<style scoped>
	.second-header_background{
		background-color: #1d1f1f;
	}
	.second-header{
		padding: 10px 20px 10px 20px;
	}	
	.btn-av-default{
		border-radius: 30px;
	    border: 2px solid #ededed;
	    color: #ffffff;
    	background-color: transparent;
		margin: 5px 15px;
		transition: 0.3s;
	}
	.btn-av-default:hover {
	    color: #69003e !important;
	    background-color: #ededed !important;
	}
	.btn-av-default:focus {
    	color: white;
	}
	.user-icon{
		float: right;
		margin-top: 3px;
	}
	.second-header .user-second-dropdown {
	    /*background-color: #950707;*/
	    background-color: #63093c;
	    border-radius: 3px;
	}
	.second-header .user-second-dropdown a{
	    color: #fff;
	}
	.name{
		color: #fff;
	    vertical-align: text-bottom;
	    margin-left: 6px;
	}
</style>