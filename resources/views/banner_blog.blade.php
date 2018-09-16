<div id="banner-blog">
<a href="/blogs">
	<div class="main_logo">
 		<img class="logo" src="{{ asset('banners/img_blogs/AlmatyTV_znak.png') }}" />
 		<div class="circle">
		  <div class="inner-circle"></div>
		  <div class="cover-circle"></div>
		</div>
	</div>
	<!-- <img class="blog_text" src="{{ asset('banners/img_blogs/blogy_title.png') }}" /> -->
	<div class="blog_text">
		<h1 @if(App::getLocale() == 'kz') style="font-size: 50px;padding-top: 23px; " @endif>{{ trans('messages.hash_blogs') }}</h1>
	</div>
	<div class="left_tags">
		<div class="tag almaty_text"><h1># Алматы</h1></div>
		<div class="tag culture_text"><h1># {{ trans('messages.hash_culture') }}</h1></div>
		<div class="tag history_text"><h1># {{ trans('messages.hash_history') }}</h1></div>
		<div class="tag my_town_text"><h1 @if(App::getLocale() == 'kz') style="font-size: 40px;" @endif># {{ trans('messages.hash_my_city') }}</h1></div>
	</div>

	<div class="right_tags">
		<div class="tag politics_text"><h1># {{ trans('messages.hash_politica') }}</h1></div>
		<div class="tag busines_text"><h1># {{ trans('messages.hash_business') }}</h1></div>
		<div class="tag almaty2_text"><h1># Almaty</h1></div>
		<div class="tag recreation_text"><h1># {{ trans('messages.hash_improvement') }}</h1></div>
	</div>
</a>
	<button class="btn-close">×</button>
</div>