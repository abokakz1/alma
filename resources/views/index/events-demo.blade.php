@extends('index.layout')

@section('content')
    
    <div class="eventt">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-3 event-blog">
                    <div class="event-blog-item">
                        <div class="event-blog-item-top">
                            <h5>{{ trans('messages.calendar') }}</h5>
                        </div>
                        <div id="calendar"></div>
                    </div>
                    <button class="btn-add" data-toggle="modal" data-target="#add_event">{{ trans('messages.add_event') }}</button>
                    <div class="event-blog-item">
                        <div class="event-blog-item-top">
                            <h5>{{ trans('messages.event_category') }}</h5>
                        </div>
                        @foreach($categories as $category)
                        <div class="event-blog-item-click">
                            <div class="event-blog-item-click-icon col-xs-2">
                                <img src="{{asset ('css/images/book-with-white-bookmark.png')}}" width="15px">
                            </div>
                            <div class="event-blog-item-click-text col-xs-10">
                                <span>{{ $category['category_name_' . App::getLocale()] }}</span>
                                <span style="display: none;" class="category_id">{{ $category['category_id'] }}</span>
                                <i class="fa fa-caret-right caret-right" aria-hidden="true"></i>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 event-blog">
                    <div class="event-blog-item event-search">
                        <span class="col-sm-4 same sobyti"><strong>{{ trans('messages.search_event') }}&nbsp;&nbsp;&nbsp;|</strong></span>
                        <div class="input-group col-sm-8 same">
                            <form class="has-feedback">
                                <input type="text" name="search" class="form-control inputs" value="{{$search}}" placeholder="{{ trans('messages.search_event2')}}" aria-label="Search for...">
                                <span class="glyphicon glyphicon-search form-control-feedback mobile-search" aria-hidden="true" style="color:#ccc"></span>
                            </form>
                        </div>
                    </div>
                    <div class="loaded-more">
                    @foreach($events as $event)
                    <div class="new-event abc">
                         <div class="row help">
                         <div class="col-xs-12 col-md-4 bir">
                            @if(count($event->media)>0)
                                <img src="/event_photo/{{ $event->media[0]->link }}" class="img img-responsive">
                            @else
                                <img src="/event_photo/event_def.jpg" class="img img-responsive">
                            @endif
                            <div class="bir-text">
                            @if(count($event->categories)>0)
                                <h5 class="over-image" style="background-color: #68003d">{{$event->categories[0]->name}}</h5>
                            @endif
                            </div>
                         </div>
                         <div class="col-xs-12 col-md-8 eki">
                            <div class="col-xs-3 uw">
                                <img src="{{asset ('css/image/clock.png')}}" width="15">&nbsp;&nbsp;&nbsp;<span>{{ $event->date_start }}</span>
                            </div>
                            <div class="col-xs-5 uw">
                                <img src="{{asset ('css/image/location.png')}}" width="15">&nbsp;&nbsp;&nbsp;<span>{{ $event->address }}</span>
                            </div>
                            <div class="col-xs-3 uw">
                                <img src="{{asset ('css/image/login.png')}}" width="15">&nbsp;&nbsp;&nbsp;<span>@if($event->price==0) {{trans('messages.event_free')}} @else {{ trans('messages.event_entry')}} {{ $event->price}}тг @endif</span>
                            </div>

                            <div class="qwe">
                                <h4><a href="/events/{{ $event->event_url_name }}"><strong>{{ $event->title }}</strong></a></h4>
                                <div class="more">{!! $event->text !!}</div>
                                <span class="morecontent">
                                    <a href="/events/{{$event->event_url_name}}" class="morelink">{{trans('messages.event_more')}}</a>
                                </span>
                            </div>
                            <div class="user-blogers">
                                <div class="col-xs-10 birdei">
                                    @for( $i=0; $i<4 && $i<count($event->blogers); $i++)
                                        <img src="@if($event->blogers[$i]->image) /user_photo/{{ $event->blogers[$i]->image }} @else /user_photo/user.png @endif" title="{{ $event->blogers[$i]->fio }}" width="20">        
                                    @endfor
                                    
                                    @if( count($event->blogers)> 4)
                                        <span>+ {{ count($event->blogers)-4 }} {{trans('messages.bloggers')}}</span>
                                    @endif
                                </div>
                                <!-- <div class="col-xs-2 birdei">
                                    <i class="fa fa-share" style="line-height:1px;" aria-hidden="true"></i>
                                </div> -->
                            </div>
                         </div>
                     </div>
                    </div>
                    @endforeach
                    </div>

                    @if(count($events)>=2 && $search=="")
                        <div class="load-more">
	                        <img src="/css/image/35.gif" style="width: 20px;margin: 8px auto;display: none;"></h5>
                            <h5 id="load-more-text">{{ trans('messages.load_more')}}</h5>
                        </div>
                    @endif
                    @if(count($events)==0)
                        <p id="no_results">{{trans('messages.no_search_results')}}</p>
                    @endif
                        <p style="display: none;" id="thats_all">{{trans('messages.thats_all')}}</p>
                </div>
                <div class="col-xs-12 col-md-3 event-blog">
                    @if($top_event)
                    <div class="event-blog-item">
                        <div class="row help">
                            <div class="event-blog-item-top">
                                <h5>{{ trans('messages.top_event')}}</h5>
                            </div>
                            <div class="event-blog-item-image">
                                @if(count($top_event->media)>0)
                                    <img src="/event_photo/{{ $top_event->media[0]->link }}" class="img img-responsive">
                                @else
                                    <img src="/event_photo/event_def.jpg" class="img img-responsive">
                                @endif
                            </div>
                            <div class="event-blog-item-tekst">
                                <h5>{{ trans('messages.published_by')}}<a href="#" style="margin-left:5px;color: #808080;">{{ $top_event->organizer_name }}</a></h5>
                                <strong><a href="/events/{{ $top_event->event_url_name }}" style="color: #303030;">{{ $top_event->title }}</a></strong><!-- <span id="hot">HOT!</span> -->
                                <div class="user-blogers blogers-circle ">
                                    @for( $i=0; $i<4 && $i<count($top_event->blogers); $i++)
                                        <img src="@if($top_event->blogers[$i]->image) /user_photo/{{ $top_event->blogers[$i]->image }} @else /user_photo/user.png @endif" title="{{ $top_event->blogers[$i]->fio }}" width="20">        
                                    @endfor
                                    
                                    @if( count($top_event->blogers)> 4)
                                        <span>+ {{ count($top_event->blogers)-4 }} {{ trans('messages.bloggers')}}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="event-blog-item">
                        <div class="row help">
                            <div class="event-blog-item-top col-xs-12">
                                <div class="col-xs-9 no-padding top-different">
                                    <h5>Блог</h5>
                                </div>
                                <div class="col-xs-3 no-padding">
                                    <a href="/blogs"><h4>{{ trans('messages.all')}}&nbsp;<i class="fa fa-caret-down" style="line-height:1px" aria-hidden="true"></i></h4></a>
                                </div>
                            </div>
                            <div class="event-blog-item-tekst col-xs-12">
                                <div class="media">
                                    <div class="media-left media-middle circle-pol">
                                        <img src="{{ $blog->image }}" title="{{ $blog->author->fio }}" class="media-object">
                                    </div>
                                    <div class="media-body" style="width:inherit">
                                        <h6 class="media-heading"><a href="{{$blog->url}}" style="color: #272b27;font-size: 14px;">{{ $blog->title }}</a></h6>
                                        <a href="{{$blog->url}}" style="color:#1f5dea"><span style="font-size:12px;">{{ trans('messages.event_more')}}</span></a>&nbsp;&nbsp;<br>
                                        <span style="color:#898989">{{ $blog->date }}</span>&nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="event-blog-item">
                        <div class="row help">
                            <div class="event-blog-item-top together col-xs-12">
                                <div class="col-xs-9 no-padding top-different">
                                    <h5>{{ trans('messages.Новости') }}</h5>
                                </div>
                                <div class="col-xs-3 no-padding">
                                    <a href="/news"><h4>{{ trans('messages.all')}}&nbsp;<i class="fa fa-caret-down" style="line-height:1px" aria-hidden="true"></i></h4></a>
                                </div>
                            </div>
                            <div class="event-blog-item-image ">
                                <img src="/news_photo/{{ $news->image }}" class="img img-responsive">
                                <!-- <div class="event-blog-item-image-text">
                                    <h6 class="over-image-two">НОВОСТИ</h6>
                                </div> -->
                            </div>
                            <?php $view = App\Models\View::select('view_id')->where("news_id","=",$news['news_id'])->get(); ?>
                            <div class="dv event-blog-item-tekst">
                                <p><a href="/news/news/{{$news->news_url_name}}" style="color: #000;">{{ $news['news_title_'. App::getLocale()] }}</a></p>
                                <span class="date mr-20" style="color: #1d1f1f;">{{$news->date}}</span>
                                <span class="views mr-20" style="color: #1d1f1f;">
                                <i class="icon icon-eye-gray mr-5"></i>{{ count($view) }}
                                <!-- <i class="icon icon-video"></i></span>  -->
                            </div>
                        </div>
                    </div>
                    @if(count($live)>0)
                    <?php $live = $live[0]; ?>
                    <div class="event-blog-item">
                        <div class="row help">
                            <div class="event-blog-item-top col-xs-12">
                                <div class="col-xs-10 no-padding top-different">
                                    <h5>{{ trans('messages.live')}}</h5>
                                </div>
                                <!-- <div class="col-xs-2 no-padding">
                                    <a href="/broadcasting"><h4>{{trans('messages.all')}}&nbsp;<i class="fa fa-caret-down" style="line-height:1px" aria-hidden="true"></i></h4></a>
                                </div> -->
                            </div>
                            <div class="event-blog-item-tekst col-xs-12">
                                    <div class="media aktual" style="border: solid 1px #108d10;">
                                        <div class="media-left media-middle">
                                          <h5 class="media-object time-strong" style="margin:0 10px">{{ date('H:i', strtotime($live->time)) }}</h5>  
                                        </div>
                                    @if(App::getLocale() == 'kz')    
                                        <div class="media-body">
                                          <p style="margin:10px 0; padding-top:0;">
                                          	<a href="/broadcasting" style="color: #108d10;">
                                          		{{ $live->tv_programm_name_kz }}
                                          	</a></p>
                                          <h6>{{ $live->category_name_kz }}</h6>
                                        </div>
                                    @else
                                        <div class="media-body">
                                          <p style="margin:10px 0; padding-top:0;">
                                          	<a href="/broadcasting" style="color: #108d10;">
                                          		{{ $live->tv_programm_name_ru }}
                                          	</a></p>
                                          <h6>{{ $live->category_name_ru }}</h6>
                                        </div>
                                    @endif
                                    </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade insert-event" id="add_event" role="dialog">
    <div class="modal-dialog  modal-md">
        <!-- Modal content-->
        <div class="modal-content" style="text-align:center">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{{trans('messages.add_event')}}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{{trans('messages.step')}} <span id="theButton">1</span> {{trans('messages.of')}} 6</p>
        
            <div class="container_two">
                <div class="progress_bar">
                    <hr class="all_steps">
                    <hr class="current_steps">
                    <!--<div class="step current button-under-one" id="step1" data-step="1">
                        <span>1</span>
                    </div>-->
                    <div class="step current button-one" id="step1" data-step="1">
                        <span>1</span>
                    </div>
                    <div class="step button-under-three" id="step2" data-step="2">
                        <span>2</span>
                    </div>
                    <div class="step button-three" id="step3" data-step="3">
                        <span>3</span>
                    </div>
                    <div class="step button-four" id="step4" data-step="4">
                        <span>4</span>
                    </div>
                    <div class="step button-five" id="step5" data-step="5">
                        <span>5</span>
                    </div>
                    <!-- <div class="step button-six" id="step6" data-step="6">
                        <span>6</span>
                    </div> -->
                    <div class="step button-seven" id="step6" data-step="6">
                        <span>6</span>
                    </div>
                </div>

        <div class="modal-footer text-center" id="blocks">
            
            <!--<div class="page block" id="block1" style="left: 0%;">
                <div class="wrap">
                    <div class="top-page">
                    <h4 class="modal-title text-center">{{trans('messages.select_lang')}}</h4>
                    <hr/>      
                    </div>
                    <form class="form-wrapper-two" style="display: -webkit-inline-box;">
                        <div class="option-wrapper">
                            <input type="checkbox" class="chb-two message" name="is_active_kz" class="kaz" id="cool55" value="1">
                            <label for="cool55">{{trans('messages.kaz')}}</label>
                        </div>
                        <div class="option-wrapper">
                            <input type="checkbox" class="chb-two message" name="is_active_ru" class="rus" id="cool56" value="1">
                            <label for="cool56">{{trans('messages.rus')}}</label>
                        </div>
                    </form>
                    <br />
                    <button onclick="step_process(1, 2), myFunction('2')" id="theButton" class="butt btn-add button-one dis-button">{{trans('messages.next')}}</button>
                </div>
            </div>-->
            <div class="page block" id="block1" style="left: 0%;">
                <div class="rus wrap">
                    <div class="top-page">
                        <h4 class="modal-title text-center">{{trans('messages.select_event')}}</h4>
                        <hr/>      
                    </div>
                    <div class="form-wrapper-two">
                      <div class="option-wrapper">
                        <input type="checkbox" class="chb-three message-two" name="event_tit" id="cool71" value="1">
                        <label class="label-text eki-tilde" for="cool71">{{trans('messages.event')}}</label>
                      </div>
                      <div class="option-wrapper">
                        <input type="checkbox" class="chb-three message-two" name="event_tit" id="cool72" value="2">
                        <label for="cool72" class="eki-tilde">{{trans('messages.complaint')}}</label>
                      </div>
                      <div class="option-wrapper">
                        <input type="checkbox" class="chb-three message-two" name="event_tit" id="cool73" value="3">
                        <label for="cool73"  class="eki-tilde">{{trans('messages.offer')}}</label>
                      </div>
                      <div class="option-wrapper">
                        <input type="checkbox" class="chb-three message-two" name="event_tit" id="cool74" value="4">
                        <label for="cool74" class="eki-tilde">{{trans('messages.other')}}</label>
                      </div>
                    </div>
                    <br />
                    <button onclick="step_process(1, 2), myFunction('2')" id="theButton" class="butt btn-add button-two dis-button-two">{{trans('messages.next')}}</button>
                </div>
            </div>
            <div class="page block" id="block2"> 
                <div class="rus wrap scroll-two no-scroll">
                    <div class="top-page">
                        <h4 class="modal-title text-center">{{trans('messages.select_category')}}</h4>
                        <hr/>      
                    </div>
                    <div class="form-wrapper">
                    @foreach($categories as $category)
                      <div class="option-wrapper">
                        <input type="checkbox" class="message-three" name="category_id" id="cool1{{$category->category_id}}" value="{{$category->category_id}}">
                        <label for="cool1{{$category->category_id}}" class="eki-tilde">{{$category['category_name_'. App::getLocale()]}}</label>
                      </div>
                    @endforeach
                    </div>
                    <br/>
                    <button onclick="step_process(2, 3), myFunction('2')" id="theButton" class="butt btn-add button-three dis-button-three">{{trans('messages.next')}}</button>
                </div>
            </div>
            <div class="page block" id="block3">
                <div class="rus wrap scroll no-scroll">
                    <div class="top-page">
                        <h4 class="modal-title text-center">{{trans('messages.info_event')}}</h4>
                        <hr/>      
                    </div>
                
                    <div class="form-group check-input" style="text-align:left">
                          <label class="eki-tilde">{{trans('messages.event_name')}}</label>
                          <input type="text" class="form-control form-control-input message-four" name="event_title" value="">
                    </div>
                    <div class="col-xs-12 same">
                    <div class="form-group check-input-two col-xs-8 same" style="text-align:left">
                          <label class="eki-tilde">{{trans('messages.event_entry')}}</label>
                          <input type="number" class="form-control form-control-input message-four price" name="price" value="" id="inputone">
                    </div>
                    <div class="col-xs-4 same form-wrapper-three">
                        <div class="option-wrapper-two check-input">
                            <input type="checkbox" name="price" class="message-four-under" id="coolfree" value="1">
                            <label for="coolfree" class="eki-tilde">{{trans('messages.event_price_free')}}</label>
                        </div>
                    </div>
                    </div>
                    <div class="form-group check-input" style="text-align:left">
                      <label for="exampleFormControlTextarea1" class="eki-tilde" >{{trans('messages.event_short_desc')}}</label>
                      <textarea class="form-control form-control-textarea message-four" style="resize:vertical" id="exampleFormControlTextarea1" rows="3" name="text" value=""></textarea>
                    </div>
                    <form action="/file-upload" class="dropzone" id="my-drop" style="min-height: 100px;border: 2px dashed #b4b7c2;padding: 10px 0;border-radius: 10px;background-color: #ecedf0;">
                    	<div class="dz-message" data-dz-message><span>{{ trans('messages.drop_files') }}</span></div>
                        <div class="fallback">  
                            <input name="file" type="file"/>
                        </div>
                    </form>
                    <br />
                    <button onclick="step_process(3, 4), myFunction('3')" id="theButton" class="butt btn-add button-four dis-button-four">{{trans('messages.next')}}</button>
                </div>                   
            </div>
            <div class="page block" id="block4">
                    <div class="rus wrap">
                        <div class="top-page">
                        <h4 class="modal-title text-center">{{trans('messages.info_event')}}</h4>
                        <hr/>      
                    </div>
                    
                    <div class="form-group" style="text-align:left">
                          <label>{{trans('messages.date_start')}}</label>
                          <div class='input-group date date-one' id='datetimepicker8'>
                                <input type='text' class="form-control" name="date_start" value="" />
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar">
                                    </span>
                                </span>
                            </div>
                    </div>
                    <div class="form-group" style="text-align:left">
                          <label>{{trans('messages.date_end')}}</label>
                          <div class='input-group date' id='datetimepicker9'>
                                <input type='text' class="form-control" name="date_end" value="" />
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar">
                                    </span>
                                </span>
                            </div>
                    </div>
                    
                    <div id="map"></div>
                    <div class="form-group check-input-address" style="text-align:left;margin-top: 15px">
                          <label>{{trans('messages.address')}}</label>
                          <input type="tel" class="form-control form-control-input message-five" name="address" value="">
                    </div>
                    
                    <button onclick="step_process(4, 5), myFunction('4')" id="theButton" class="butt btn-add button-five dis-button-five">{{trans('messages.next')}}</button>
                    
                    </div>
                </div>

            <div class="page block" id="block5">
                <div class="wrap">
                <div class="top-page">
                    <h4 class="modal-title text-center">{{trans('messages.invite_jurnalists')}}</h4>
                    <hr/>      
                </div>
                <div class="form-wrapper">
                    @foreach($programs as $program)
                      <div class="option-wrapper">
                        <input type="checkbox" name="cool-checkboxes" class="programm-checkboxs" id="cool{{$program->programm_id}}" value="{{$program->programm_id}}">
                        <label for="cool{{$program->programm_id}}">{{$program->programm_name_ru}}</label>
                      </div>
                    @endforeach
                      <p style="text-align: center">{{ trans('messages.invite_jurnalists_info') }}</p>
                      <input type="checkbox" class="confi-checkbox" name="confidencial" style="display:inline-block !important;transform: translateY(2px);">
                      <a href="#" class="no-deco" style="color: #000; margin-left: 10px; font-size: 12px;">Я СОГЛАСЕН С ПОЛИТИКОЙ КОНФИДЕНЦИАЛЬНОСТИ</a> 
                </div>
                <br />
                    <button onclick="step_process(5, 6), myFunction('5')" id="theButton" class="butt btn-add button-six dis-button-six">{{trans('messages.next')}}</button>
            </div>
            </div>
<!--             <div class="page block" id="block6">
                <div class="wrap">
                <div class="top-page">
                    <h4 class="modal-title text-center">{{trans('messages.in_calendar')}}</h4>
                    <hr/>      
                </div>
                <div class="form-wrapper-two">
                      <div class="option-wrapper">
                        <input type="checkbox" class="chb message-seven" name="in_calendar" id="cool43" value="0">
                        <label for="cool43">{{trans('messages.yes')}}</label>
                      </div>
                      <div class="option-wrapper">
                        <input type="checkbox" class="chb message-seven" name="in_calendar_no" id="cool14" value="1">
                        <label for="cool14">{{trans('messages.no')}}</label>
                      </div>
                </div>
                <br />
                    <button onclick="step_process(6, 7), myFunction('6')" id="theButton" class="butt btn-add button-seven dis-button-seven">{{trans('messages.next')}}</button>
            </div>
            </div> -->
            <div class="page block block6" id="block6">
                    <div class="rus wrap">
                        <div class="top-page">
                        <h4 class="modal-title text-center">{{trans('messages.organizer_contacts')}}</h4>
                        <hr/>
                        <p id="error-show"></p>
                    </div>
                    
                    <div class="form-group check-input-diff" style="text-align:left">
                          <label>{{trans('messages.name')}}</label>
                          <input type="text" class="form-control form-control-input message-eight" name="organizer_name" required>
                    </div>
                    <div class="form-group check-input-diff" style="text-align:left">
                          <label>{{trans('messages.email')}}</label>
                          <input type="email" class="form-control form-control-input message-eight" name="organizer_email" required>
                    </div>
                    <div class="form-group check-input-diff" style="text-align:left">
                          <label>{{trans('messages.number')}}</label>
                          <input type="tel" class="form-control form-control-input message-eight" name="organizer_number" required>
                    </div>
                    
                        <button type="submit" class="btn-add text-center submit dis-button-eight" data-toggle="modal" data-target="#myModalFinish" data-dismiss="modal" aria-label="Close">{{trans('messages.done')}}</button>
                    </div>
                </div>
                </div>      
            </div>
            </div>
        </div>
      </div>
      
    </div>
    <!-- Modal -->
        <div class="modal fade" id="myModalFinish" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body" style="text-align:center">
                <img class="logo" src="/css/image/confirm.png" style="width: 60px; margin-bottom: 20px;">
                <p>Подтвердите действие на Almaty tv:</p>
                <p>Событие сохранилось</p>
                <button type="button" class="btn btn-default btn-finish" data-dismiss="modal">ОК</button>
              </div>
            </div>
          </div>
        </div>
  </div>
   

<style>
    #calendar .day-number {
        font-size: 12px;
        width: 31px;
        height: 30px;
        line-height: 31px;
        border-radius: 0;
    }
    #calendar .day {
        padding: 0;
    }
    #calendar .header h1{
        font-size: 14px;
    }
    #calendar .day.today .day-number{
        /* background-color: #68003d; */
        border-radius: 50%;
    }
    #calendar .day.other{
        color: rgba(114, 0, 69, 0.2);
    }
    .btn-finish{
        margin-top: 15px;
        padding-left: 40px;
        padding-right: 40px;
        color: #fff;
        background-color: #1667b1;
        border-color: #1667b1; 
        border-radius: 50px;
    }
    .btn-finish:hover{
        color: #fff;
        background-color: #1667b1;
        border-color: #1667b1;
    }
</style>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
<script>

$(function() {

    moment.locale('ru');
    if('{{ App::getLocale() }}' == 'kz'){
        moment.locale('kk');
    }
    var today = moment();

    function Calendar(selector, events, date, category) {
        this.el = document.querySelector(selector);
        this.events = events;
        this.current = moment(date).date(1);
        this.category = category;
        this.draw();
    }

    Calendar.prototype.draw = function() {
        //Create Header
        this.drawHeader();

        //Draw Month
        this.drawMonth();
    }

    Calendar.prototype.drawHeader = function() {
        var self = this;
        if(!this.header) {
            //Create the header elements
            this.header = createElement('div', 'header');
            this.header.className = 'header';

            this.title = createElement('h1');
            // console.log(this.title);

            var right = createElement('div', 'right');
            right.addEventListener('click', function() { self.nextMonth(); });

            var left = createElement('div', 'left');
            left.addEventListener('click', function() { self.prevMonth(); });

            //Append the Elements
            this.header.appendChild(this.title);
            this.header.appendChild(right);
            this.header.appendChild(left);
            this.el.appendChild(this.header);
        }

        this.title.innerHTML = this.current.format('MMMM YYYY');
        // console.log(this.current.format('MMMM YYYY'));
    }

    Calendar.prototype.drawMonth = function() {
        var self = this;

        //this.events.forEach(function(ev) {
          //  ev.date = self.current.clone().date(Math.random() * (29 - 1) + 1);
        //});


        if(this.month) {
            this.oldMonth = this.month;
            this.oldMonth.className = 'month out ' + (self.next ? 'next' : 'prev');
            this.oldMonth.addEventListener('webkitAnimationEnd', function() {
                self.oldMonth.parentNode.removeChild(self.oldMonth);
                self.month = createElement('div', 'month');
                self.backFill();
                self.currentMonth();
                self.fowardFill();
                self.el.appendChild(self.month);
                window.setTimeout(function() {
                    self.month.className = 'month in ' + (self.next ? 'next' : 'prev');
                }, 16);
            });
        } else {
            this.month = createElement('div', 'month');
            this.el.appendChild(this.month);
            this.backFill();
            this.currentMonth();
            this.fowardFill();
            this.month.className = 'month new';
        }
    }

    Calendar.prototype.backFill = function() {
        var clone = this.current.clone();
        var dayOfWeek = clone.day();

        if(!dayOfWeek) { return; }

        // clone.subtract('days', dayOfWeek+1);
        clone.subtract(dayOfWeek+1, 'days');

        for(var i = dayOfWeek; i > 0 ; i--) {
            // this.drawDay(clone.add('days', 1));
            this.drawDay(clone.add(1,'days'));
        }
    }

    Calendar.prototype.fowardFill = function() {
        var clone = this.current.clone().add('months', 1).subtract( 1, 'days',);
        var dayOfWeek = clone.day();

        if(dayOfWeek === 6) { return; }

        for(var i = dayOfWeek; i < 6 ; i++) {
            this.drawDay(clone.add(1,'days'));
        }
    }

    Calendar.prototype.currentMonth = function() {
        var clone = this.current.clone();

        while(clone.month() === this.current.month()) {
            this.drawDay(clone);
            clone.add(1,'days');
        }
    }

    Calendar.prototype.getWeek = function(day) {
        if(!this.week || day.day() === 0) {
            this.week = createElement('div', 'week');
            this.month.appendChild(this.week);
        }
    }

    Calendar.prototype.drawDay = function(day) {
        var self = this;
        this.getWeek(day);

        //Outer Day
        var outer = createElement('div', this.getDayClass(day));
        outer.addEventListener('click', function() {
            self.openDay(this);
        });

        //Day Number
        var number = createElement('div', 'day-number', day.format('DD'));


        //Events
        var events = createElement('div', 'day-events');
        this.drawEvents(day, events);

        outer.appendChild(number);
        outer.appendChild(events);
        this.week.appendChild(outer);
    }

    Calendar.prototype.drawEvents = function(day, element) {
        if(day.month() === this.current.month()) {
            var todaysEvents = this.events.reduce(function(memo, ev) {
                if(ev.date.isSame(day, 'day')) {
                    memo.push(ev);
                }
                return memo;
            }, []);

            todaysEvents.forEach(function(ev) {
                var evSpan = createSpan(ev.color);
                element.appendChild(evSpan);
            });
        }
    }

    Calendar.prototype.getDayClass = function(day) {
        classes = ['day'];
        if(day.month() !== this.current.month()) {
            classes.push('other');
        } else if (today.isSame(day, 'day')) {
            classes.push('today');
        }
        return classes.join(' ');
    }

    Calendar.prototype.openDay = function(el) {
        var details, arrow;
        var dayNumber = +el.querySelectorAll('.day-number')[0].innerText || +el.querySelectorAll('.day-number')[0].textContent;
        var day = this.current.clone().date(dayNumber);

        var todaysEvents = this.events.reduce(function(memo, ev) {
          if(ev.date.isSame(day, 'day')) {
            memo.push(ev);
          }
          return memo;
        }, []);

        var todaysEventsIds = this.events.reduce(function(memo, ev) {
          if(ev.date.isSame(day, 'day')) {
            memo.push(ev.event_id);
          }
          return memo;
        }, []);
    
        var currentOpened = document.querySelector('.details');
        if(todaysEvents.length>0){
            getByDayAndCategory(day.format(), this.category, todaysEventsIds);

            console.log(todaysEventsIds);
            //Check to see if there is an open detais box on the current row
            if(currentOpened && currentOpened.parentNode === el.parentNode) {
              details = currentOpened;
              arrow = document.querySelector('.arrow');
            } else {
              //Close the open events on differnt week row
              //currentOpened && currentOpened.parentNode.removeChild(currentOpened);
              if(currentOpened) {
                currentOpened.addEventListener('webkitAnimationEnd', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener('oanimationend', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener('msAnimationEnd', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener('animationend', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.className = 'details out';
              }

              //Create the Details Container
              details = createElement('div', 'details in');

              //Create the arrow
              var arrow = createElement('div', 'arrow');

              //Create the event wrapper

              details.appendChild(arrow);
              el.parentNode.appendChild(details);
            }

            this.renderEvents(todaysEvents, details);

            arrow.style.left = el.offsetLeft - el.parentNode.offsetLeft + 27 + 'px';
        } else{
            if(currentOpened) {
                currentOpened.addEventListener('webkitAnimationEnd', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener('oanimationend', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener('msAnimationEnd', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener('animationend', function() {
                  currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.className = 'details out';
              }
        }
        
    }

    Calendar.prototype.renderEvents = function(events, ele) {
        //Remove any events in the current details element
        var currentWrapper = ele.querySelector('.events');
        var wrapper = createElement('div', 'events in' + (currentWrapper ? ' new' : ''));

        events.forEach(function(ev) {
            var div = createElement('div', 'event');
            var square = createElement('div', 'event-category ' + ev.color);
            var span = createElement('span', '', ev.eventName);

            div.appendChild(square);
            div.appendChild(span);
            wrapper.appendChild(div);
        });

        if(!events.length) {
        }

        if(currentWrapper) {
            currentWrapper.className = 'events out';
            currentWrapper.addEventListener('webkitAnimationEnd', function() {
                currentWrapper.parentNode.removeChild(currentWrapper);
                ele.appendChild(wrapper);
            });
            currentWrapper.addEventListener('oanimationend', function() {
                currentWrapper.parentNode.removeChild(currentWrapper);
                ele.appendChild(wrapper);
            });
            currentWrapper.addEventListener('msAnimationEnd', function() {
                currentWrapper.parentNode.removeChild(currentWrapper);
                ele.appendChild(wrapper);
            });
            currentWrapper.addEventListener('animationend', function() {
                currentWrapper.parentNode.removeChild(currentWrapper);
                ele.appendChild(wrapper);
            });
        } else {
            ele.appendChild(wrapper);
        }
    }


    Calendar.prototype.nextMonth = function() {
        // this.current.add('months', 1);
        this.current.add(1,'months');
        this.next = true;
        this.draw();

        getByMonthAndCategory(this.current.format(), this.category);
    }

    Calendar.prototype.prevMonth = function() {
        // this.current.subtract('months', 1);
        this.current.subtract(1,'months');
        this.next = false;
        this.draw();

        getByMonthAndCategory(this.current.format(), this.category);
    }

    window.Calendar = Calendar;

    function createElement(tagName, className, innerText) {
        var ele = document.createElement(tagName);
        if(className) {
            ele.className = className;
        }
        if(innerText) {
            ele.innderText = ele.textContent = innerText;
        }
        return ele;
    }

    function createSpan(color) {
        var ele = document.createElement('span');
        color.padStart(6, '0');
        ele.style.backgroundColor = '#' + color.padStart(6, '0');

        return ele;
    }

    getByMonthAndCategory(today.format());


});

function getByMonthAndCategory (date, category) {
    $.ajax({
        type: 'GET',
        url: "{{route('get_dates')}}",
        data: {date: date, category_id: category},
        async: false,
        success: function(data){
            events = data.events;

            Object.keys(events).map(function(key, index) {
            	// let d= new Date(events[index]['date']);
             //    events[index]['date'] = moment(date).date( d.toISOString());
                events[index]['date'] = moment(date).date(events[index]['date']);
            });

            if (events.length > 0) {
                $('#calendar').empty();
                new Calendar('#calendar', events, moment(date), category);
            };
        }
    });
}
function getByDayAndCategory (date, category, ids) {
    $('.ajax-loader').fadeIn();
    $.ajax({
        type: 'GET',
        url: "{{route('get_dates')}}",
        data: {date: date, category_id: category, ids: ids, type: 2},
        async: true,
        success: function(data){
            // console.log(events);
            if(data.events.length>0){
                $('.loaded-more').empty();
                $('.load-more').css('display', 'none');
                $('#thats_all').css('display', 'none');
                $('#no_results').css('display', 'none');
                addEvents(data.events);
            }
        }
    });
    $('.ajax-loader').fadeOut();
}
function addEvents(events){
    let locale = '{{App::getLocale()}}';
    if(events.length>0){
        var showChar = 300;
        var ellipsestext = "...";
        var more = 'Подробнее';
        if(locale=="kz"){
            more = 'Толығырақ';
        }
        for(let i=0; i < events.length; i++){
            let image = (!events[i].media[0] ? 'event_def.jpg' : events[i].media[0].link);

            var content = events[i].text;
            var show_more_text = 'none';
            let blogers_text = "";
            if(content.length > showChar) {
                content = content.substr(0, showChar) + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span>';
                show_more_text = 'block';
            }
            var cat_name = "";
            if(events[i].categories.length>0){
                cat_name = '<h5 class="over-image" style="background-color: #68003d">'+events[i].categories[0].name+'</h5>';
            } if(events[i].blogers_text){
                blogers_text = '<div class="user-blogers"><div class="col-xs-10 birdei">'+events[i].blogers_text + '</div><div class="col-xs-2 birdei"></div></div>';
            }

            $('.loaded-more').append('<div class="new-event abc"><div class="row help"><div class="col-xs-12 col-md-4 bir"><img src="/event_photo/'+image+'" class="img img-responsive"><div class="bir-text">'+cat_name+'</div></div><div class="col-xs-12 col-md-8 eki"><div class="col-xs-3 uw"><img src="https://almaty.tv/css/image/clock.png" width="15">&nbsp;&nbsp;&nbsp;<span>'+events[i].date_start+'</span></div><div class="col-xs-5 uw"><img src="https://almaty.tv/css/image/location.png" width="15">&nbsp;&nbsp;&nbsp;<span>'+events[i].address+'</span></div><div class="col-xs-3 uw"><img src="https://almaty.tv/css/image/login.png" width="15">&nbsp;&nbsp;&nbsp;<span>'+events[i].price+'</span></div><div class="qwe"><h4><a href="/events/'+events[i].event_url_name+'"><strong>'+events[i].title+'</strong></a></h4><p class="more">'+content+'</p><span class="morecontent" style="display: '+show_more_text+'"><a href="/events/'+events[i].event_url_name+'" class="morelink">'+more+'</a></span></div>'+blogers_text+'</div></div></div>');
        }
    }
}
</script>

<script type="text/javascript">
$(document).ready(function(){
    $(".butt").click(function(){
        if($('#rus').is(':checked') && $('#kaz').is(':checked')) {
            $(".rus").show();
        } else {
            if($('#rus').is(':checked')) {
                $('.eki-tilde').each(function(){ 
                    var t = $(this).text().split("/")[0]; 
                    $(this).text(t);console.log(t);
                });
            } else if($('#kaz').is(':checked')) {
                $('.eki-tilde').each(function(){ 
                    var t = $(this).text().split("/")[1]; 
                    $(this).text(t);console.log(t);
                });
            }
            $(".rus").show();
        }
    });
});
</script>

<script>
var media_id = [];
Dropzone.options.myDrop = {
        acceptedFiles: 'image/*',
        paramName: 'file',
        maxFilesize: 100,
        addRemoveLinks: true,
        dictRemoveFile: '{{trans("messages.remove")}}',
        dictCancelUpload: '{{trans("messages.cancel_upload")}}',
        dictCancelUploadConfirmation: '{{trans("messages.cancel_upload_confirm")}}',
        init: function() {
        
            this.on('success', function( file, resp ){
                console.log(resp);
                media_id.push(resp);
                // file.id = resp.media_id;
                // console.log(file);
            });

            this.on("removedfile", function(file) {
                var media_del = 0;
                // console.log(file.id);
                for(let i=0; i<media_id.length; i++){
                    if(file.name == media_id[i].media_name){
                        media_del = media_id[i].media_id;
                        media_id.splice(i, 1);
                    }
                }
                $.ajax({
                    type: 'POST',
                    url: "{{ route('file_delete')}}",
                    data: {media_id: media_del},
                    success: function(data){
                        if(data.result === false)
                        {
                            console.log('File Dont Deleted');   
                        } else{
                            console.log('File Deleted');
                        }
                    }
                });
            });
        }
}
</script>


<script>
  
  function step_process(from, to, dir) {

    if (typeof(dir) === 'undefined') dir = 'asc';
    var old_move = '';
    var new_start = '';

    var speed = 500;

    if (dir == 'asc') {
        old_move = '-';
        new_start = '';
    } else if (dir == 'desc') {
        old_move = '';
        new_start = '-';
    }

    $('#block'+from).animate({left: old_move+'100%'}, speed, function() {
        $(this).css({left: '100%', 'background-color':'transparent', 'z-index':'2'}); 
    });
    $('#block'+to).css({'z-index': '3', left:new_start+'100%'}).animate({left: '0%'}, speed, function() {
        $(this).css({'z-index':'2'}); 
    });

    if (Math.abs(from-to) === 1) {
        // Next Step
        if (from < to) $("#step"+from).addClass('complete').removeClass('current');
        else $("#step"+from).removeClass('complete').removeClass('current');
        var width = (parseInt(to) - 1) * 11;
        $(".progress_bar").find('.current_steps').animate({'width': width+'%'}, speed, function() {
            $("#step"+to).removeClass('complete').addClass('current');
        });
    } else {
        // Move to Step
        var steps = Math.abs(from-to);
        var step_speed = speed / steps;
        if (from < to) {
            move_to_step(from, to, 'asc', step_speed);
        } else {
            move_to_step(from, to, 'desc', step_speed);
        }
    }
}
  
function move_to_step(step, end, dir, step_speed) {
    if (dir == 'asc') {
        $("#step"+step).addClass('complete').removeClass('current');
        var width = (parseInt(step+1) - 1) * 11;
        $(".progress_bar").find('.current_steps').animate({'width': width+'%'}, step_speed, function() {
            $("#step"+(step+1)).removeClass('complete').addClass('current');
            if (step+1 < end) move_to_step((step+1), end, dir, step_speed);
        });
    } else {
        $("#step"+step).removeClass('complete').removeClass('current');
        var width = (parseInt(step-1) - 1) * 11;
        $(".progress_bar").find('.current_steps').animate({'width': width+'%'}, step_speed, function() {
            $("#step"+(step-1)).removeClass('complete').addClass('current');
            if (step-1 > end) move_to_step((step-1), end, dir, step_speed);
        });
    }
}

$(document).ready(function() {
    $("body").on("click", ".progress_bar .step.complete", function() {
        var from = $(this).parent().find('.current').data('step');
        var to = $(this).data('step');
        var dir = "desc";
        if (from < to) dir = "asc";

        step_process(from, to, dir);
    });
});

</script>


<script>
ymaps.ready(init);
var coords = [];
function init() {
    var myPlacemark,
        myMap = new ymaps.Map('map', {
            center: [43.244897, 76.934504],
            zoom: 11
        }, {
            searchControlProvider: 'yandex#search'
        });

    // Слушаем клик на карте.
    myMap.events.add('click', function (e) {
        coords = e.get('coords');

        // Если метка уже создана – просто передвигаем ее.
        if (myPlacemark) {
            myPlacemark.geometry.setCoordinates(coords);
        }
        // Если нет – создаем.
        else {
            myPlacemark = createPlacemark(coords);
            myMap.geoObjects.add(myPlacemark);
            // Слушаем событие окончания перетаскивания на метке.
            myPlacemark.events.add('dragend', function () {
                getAddress(myPlacemark.geometry.getCoordinates());
            });
        }
        getAddress(coords);
    });

    // Создание метки.
    function createPlacemark(coords) {
        return new ymaps.Placemark(coords, {
            iconCaption: 'поиск...'
        }, {
            preset: 'islands#violetDotIconWithCaption',
            draggable: true
        });
    }

    // Определяем адрес по координатам (обратное геокодирование).
    function getAddress(coords) {
        myPlacemark.properties.set('iconCaption', 'поиск...');
        ymaps.geocode(coords).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);

            myPlacemark.properties
                .set({
                    // Формируем строку с данными об объекте.
                    iconCaption: [
                        // Название населенного пункта или вышестоящее административно-территориальное образование.
                        firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                        // Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
                        firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                    ].filter(Boolean).join(', '),
                    // В качестве контента балуна задаем строку с адресом объекта.
                    balloonContent: firstGeoObject.getAddressLine()
                });
            changeAddress(firstGeoObject.getAddressLine());
        });
    }
}
function changeAddress(address){
	$('input[name=address]').val(address);	
}
</script>
<script>
    function myFunction(name){
        document.getElementById("theButton").innerHTML = name;
    }

    $(".chb").change(function() {
    $(".chb").prop('checked', false);
    $(this).prop('checked', true);
});
    $(".chb-two").change(function() {
    $(".chb-two").prop('checked', false);
    $(this).prop('checked', true);
});
    $(".chb-three").change(function() {
    $(".chb-three").prop('checked', false);
    $(this).prop('checked', true);
});

$(function () {
        $('#datetimepicker8').datetimepicker({
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            locale: 'ru'
        });
    });
    $(function () {
        $('#datetimepicker9').datetimepicker({
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            locale: 'ru'
        });
    });
    $(".chb").change(function() {
    $(".chb").prop('checked', false);
    $(this).prop('checked', true);
});
</script>
<script>
$(document).ready(function(){
$('.submit').click(function(){
    let price = $(".price").val();
    if ($(".message-four-under").is(":checked")){  
        price=0;
    }
    let data = {is_active_kz: ($("input[name='is_active_kz']").is(":checked") ? 1 : 0), is_active_ru: ($("input[name='is_active_ru']").is(":checked") ? 1 : 0),
               category_id: $('.message-three:checked').serializeArray(), event_title: $("input[name='event_title']").val(),
               price: price, event_text: $("textarea[name='text']").val(), date_start: $("input[name='date_start']").val(),
               date_end: $("input[name='date_end']").val(),address: $("input[name='address']").val(), lat: coords[0], lng: coords[1],
               programs: $('.programm-checkboxs:checked').serializeArray(), 
               in_calendar: $("input[name='in_calendar']").is(":checked"),organizer_name: $("input[name='organizer_name']").val(),
               organizer_email: $("input[name='organizer_email']").val(),organizer_number: $("input[name='organizer_number']").val(),
               files:  $("input[name='files[]']").val(), media_id: media_id
            };

    $.ajax({
        type: 'POST',
        url: "{{route('save_event')}}",
        data: data,
        success: function(data){
            
            if(!data.result.status){
                console.log(data.result.value);
                $("#add_event").modal("show").on('shown.bs.modal', function () {
                    $("#add_event").css('display', 'block');
                });
                $("#error-show").empty();
                for (i = 0; i < data.result.value.length; i++) { 
                    $("#error-show").append("<span style='color:red'>"+data.result.value[i]+"</span><br>");
                }
            }
            else{
                $(".block6").css('left', '100%');
            	myFunction(1);
                step_process(7, 1);
                $('#blocks').animate({height:'380px'}, 500);
                $('.dis-button').attr('disabled',true);
                $('.dis-button-two').attr('disabled',true);
                $('.dis-button-three').attr('disabled',true);
                // $('.dis-button-six').attr('disabled',true);
                $('.dis-button-seven').attr('disabled',true);
                $('.dis-button-eight').attr('disabled',true);
                $('.form-group input[type="text"]').val('');
                $('div.dz-success').remove();
                $('.form-group input[type="tel"]').val('');
                $('.form-group input[type="email"]').val('');
                $('.form-group input[type="number"]').val('');
                $('.form-group textarea').val('');
                $('.option-wrapper input[type="checkbox"]').each(function() { 
                    this.checked = false; 
                });
                $('.option-wrapper-two input[type="checkbox"]').each(function() { 
                    this.checked = false; 
                });
                console.log(data);
            }
        }
    });    
});
});
</script>
<script>
var check = document.getElementById('coolfree');
check.addEventListener('click', toggleDisabled, false );

function toggleDisabled(evt) {
    var checked = evt.target.checked;
    if(checked){
        document.getElementById('inputone').disabled = "disabled";
    }else{
        document.getElementById('inputone').disabled = "";            
    }
}
</script>
<script>
$(document).ready(function(){
    $('.dis-button').attr('disabled',true);
    $('.dis-button-two').attr('disabled',true);
    $('.dis-button-three').attr('disabled',true);
    $('.dis-button-six').attr('disabled',true);
    $('.dis-button-seven').attr('disabled',true);
    $('.dis-button-eight').attr('disabled',true);

    $('.dis-button').click(function(){
            if($(this).attr('disabled') == true){
                $(".input-message").css("display", "block");    
        } 
    });
    
        $('.message').click(function(){
            if($(this).attr('checked') == false){
                $('.dis-button').attr("disabled","disabled");   
        } else {
                $('.dis-button').removeAttr('disabled');
        }
    });
        $('.message-two').click(function(){
            if($(this).attr('checked') == false){
                $('.dis-button-two').attr("disabled","disabled");   
        } else {
                $('.dis-button-two').removeAttr('disabled');
        }
    });
        $('.message-three').click(function(){
            if($(this).attr('checked') == false){
                $('.dis-button-three').attr("disabled","disabled");   
        } else {
                $('.dis-button-three').removeAttr('disabled');
        }
    });    
    var $submitt = $(".dis-button-four"),
        $inputs = $('[myc="blue"]').filter(function() {
            var myIds = $(this).attr('myid');   
            return (myIds == '1' || myIds == '2');
        });
        $inputs = $('.check-input input[type=text],.check-input input[type=file],.check-input textarea');

    function checkEmpty() {

        // filter over the empty inputs

        return $inputs.filter(function() {
            return !$.trim(this.value);
        }).length === 0;
    }

    $inputs.on('blur', function() {
        $submitt.prop("disabled", !checkEmpty());
    }).blur(); // trigger an initial blur

    //     $('.message-six').click(function(){
    //         if($(this).attr('checked') == false){
    //             $('.dis-button-six').attr("disabled","disabled");   
    //     } else {
    //             $('.dis-button-six').removeAttr('disabled');
    //     }
    // });
        $('.confi-checkbox').click(function(){
                if($(this).attr('checked') == false){
                    $('.dis-button-six').attr("disabled","disabled");   
            } else {
                    $('.dis-button-six').removeAttr('disabled');
            }
        });
        $('.message-seven').click(function(){
            if($(this).attr('checked') == false){
                $('.dis-button-seven').attr("disabled","disabled");   
        } else {
                $('.dis-button-seven').removeAttr('disabled');
        }
    });
});
</script>
<script>
$(document).ready(function() {
    var $submitt = $(".dis-button-five"),
        $inputs = $('.date-one input[type=text]');

    function checkEmpty() {

        return $inputs.filter(function() {
            return !$.trim(this.value);
        }).length === 0;
    }

    $inputs.on('blur', function() {
        $submitt.prop("disabled", !checkEmpty());
    }).blur(); // trigger an initial blur
});
</script>
<script>
$(document).ready(function() {
    var $submitt = $(".dis-button-eight"),
        $inputs = $('.check-input-diff input[type=tel]');

    function checkEmpty() {

        return $inputs.filter(function() {
            return !$.trim(this.value);
        }).length === 0;
    }

    $inputs.on('blur', function() {
        $submitt.prop("disabled", !checkEmpty());
    }).blur(); // trigger an initial blur
});
</script>
<script>
// $(document).ready(function() {
//     // Configure/customize these variables.
//     var showChar = 300;  // How many characters are shown by default
//     var ellipsestext = "...";
    

//     $('.more').each(function() {
//         var content = $(this).html();
 
//         if(content.length > showChar) {
 
//             var c = content.substr(0, showChar);
            
//             var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span>';
//             $(this).next().css({'display':'block'});

//             $(this).html(html);
//         }
 
//     });
// });
</script>
<script>
var last=3;
var locale = '{{App::getLocale()}}';
$(function () {
    $(".load-more").on('click', function (e) {
        // e.preventDefault();
        $('.load-more #load-more-text').css('display', 'none');
        $('.load-more img').css('display', 'block');
        
        $.ajax({
        type: 'GET',
        url: "{{route('load_more')}}",
        data: {last: last},
        success: function(data){
            if(data.result === false){
                alert("Ошибка при загрузки");
                $('.load-more img').css('display', 'none');
		        $('.load-more #load-more-text').css('display', 'block');
            }
            else{
                console.log(data);
                if(data.events.length>0){
                    addEvents(data.events);
                    if(data.events.length<3){
                        $('.load-more').css('display','none');
                        $('#thats_all').css('display', 'block');                        
                    } else {
                        $('.load-more img').css('display', 'none');
                        $('.load-more #load-more-text').css('display', 'block');
                    }
                } else{
                    $('.load-more').css('display','none');
                    $('#thats_all').css('display', 'block');
                }
                last = data.last;
            }
        }
        }); 
    });

    $(".event-blog-item-click-text").on('click', function (){
        var category_id = $(this).find("span.category_id").text();
        getByMonthAndCategory(moment().format(), category_id);
    });
});

</script>
<script>
$('.button-one').click(function(){
     if ($(this).hasClass("complete") || $(this).hasClass("butt")) {
        myFunction(1);
        $('#blocks').animate({height:'380px'}, 500);
    }
});
$('.button-two').click(function(){
    if ($(this).hasClass("complete") || $(this).hasClass("butt")) {
        myFunction(2);
        $('#blocks').animate({height:'450px'}, 450);
    }
});
$('.button-three').click(function(){
    if ($(this).hasClass("complete") || $(this).hasClass("butt")) {
        myFunction(3);
        $('#blocks').animate({height:'565px'}, 500);
    }
});
$('.button-four').click(function(){
    if ($(this).hasClass("complete") || $(this).hasClass("butt")) {
        myFunction(4);
        $('#blocks').animate({height:'800px'}, 500);
    }
});
$('.button-five').click(function(){
    if ($(this).hasClass("complete") || $(this).hasClass("butt")) {
        myFunction(5);
        $('#blocks').animate({height:'600px'}, 500);
    }
});
$('.button-six').click(function(){
    if ($(this).hasClass("complete") || $(this).hasClass("butt")) {
        myFunction(6);
        $('#blocks').animate({height:'400px'}, 500);
    }
});
$('.button-seven').click(function(){
    if ($(this).hasClass("complete") || $(this).hasClass("butt")) {
        myFunction(7);
        $('#blocks').animate({height:'400px'}, 500);
    }
});
$('.button-under-one').click(function(){
    if ($(this).hasClass("complete") || $(this).hasClass("butt")) {
        myFunction(1);
        $('#blocks').animate({height:'240px'}, 500);
    }
});
$('.button-under-three').click(function(){
    if ($(this).hasClass("complete") || $(this).hasClass("butt")) {
        myFunction(2);
        $('#blocks').animate({height:'450px'}, 500);
    }
});
</script>
<script>
var $Divs = $(".event-blog-item-image");
$Divs.each(function(i) {
      if ($(this).find("img").length > 0) {
            //do something
      } else {
            $(".event-blog-item-image-text").css("padding-top", "55px");
     }
});
</script>
<script>
$(document).ready(function(){
    var show_modal = {{$show_modal}};
    if(show_modal == 1){
        $("#add_event").modal('show');
    }
});
</script>
@endsection