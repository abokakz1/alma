@extends('index.layout')

@section('content')

    @include('index.header_second')
    @if($featured_blogs && $featured_blogs->count())
        <div class="container">
            <div class="row">
                <div id="custom_carousel" class="carousel slide" data-ride="carousel" data-interval="0">
                    <div class="row clearfix" style="margin-top: 20px">
                        <div class="col-md-9 col-xs-12 column">
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                @foreach($featured_blogs as $index => $blog)
                                    <div class="item @if ($index == 0) active @endif">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    @if($blog->video)
                                                        <div class="graph graph-video">
                                                            <div class="graph-content">
                                                                <iframe width="100%" height="500" src="https://youtube.com/embed/{{ $blog->video }}" frameborder="0" allowfullscreen>
                                                                </iframe>
                                                            </div>
                                                            <div class="title-block">
                                                                <h2 class="title">{{ $blog->title }}</h2>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="graph">
                                                            <div class="graph-content">
                                                                <img src="{{ asset($blog->image) }}">
                                                            </div>
                                                            <a href="{{ url($blog->url) }}">
                                                                <div class="title-block">
                                                                    <span class="title">{{ $blog->title }}</span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        <div class="col-md-3 col-xs-12 column-controls">
                            <div class="controls">
                                <h5 class="title text-center">
                                    {{ trans_choice('messages.blog.featured', $featured_blogs->count()) . " " .trans_choice('messages.blog.title', $featured_blogs->count()) }}
                                </h5>
                                <ul class="list-unstyled scrollable-content">
                                    @foreach($featured_blogs as $index => $blog)
                                        <li data-target="#custom_carousel" data-slide-to="{{ $index }}">
                                            <div class="media">
                                                @if($blog->image)
                                                    <div class="media-left media-middle">
                                                        <div style="width: 80px"></div>
                                                        <img src="{{ asset($blog->images['cropped']) }}" class="media-object">
                                                    </div>
                                                @endif
                                                <div class="media-body">
                                                    <a class="media-heading" href="{{ url($blog->url) }}">{{ $blog->title }}</a>
                                                    <p class="author">{{ $blog->author->fio }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="container mp-top-container">
        <div class="row">
            <div class="col-xs-12 col-md-3">
                <div class="row banner-av">
                    @if($ads1)
                        <div class="col-xs-12">
                            <div class="card media-block">
                                <a href="{{ $ads1->link }}" target="_blank">
                                    <div class="media-block-img" style="background-image: url('/adv/{{$ads1->image}}')"></div>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if($ads2)
                        <div class="col-xs-12">
                            <div class="card media-block">
                                <a href="{{ $ads2->link }}" target="_blank">
                                    <div class="media-block-img" style="background-image: url('/adv/{{$ads2->image}}')"></div>
                                </a>
                            </div>
                        </div>
                    @endif
                   <div class="col-xs-12">
                       <div class="bg-container bg-small">
                           <form class="subscribe subscribe-sidebar">
                               @if(App::getLocale() == 'kz')
                                   <p class="text-left">
                                       {{ trans('messages.subscribed_news') }}
                                       <br><strong>{{ trans('messages.subscribe_to') }}</strong>
                                   </p>
                               @else

                                   <p class="text-left">
                                       {{ trans('messages.subscribe_to') }}
                                       <br><strong>{{ trans('messages.subscribed_news') }}</strong>
                                   </p>
                               @endif
                               <input type="text" class="form-control" placeholder="{{ trans('messages.enter_email') }}">
                               <button type="button" class="btn btn-default">{{ trans('messages.subscribe') }}</button>
                           </form>
                       </div>
                   </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-9 sw">
                @if($popular_blogs && $popular_blogs->count())
                    <div class="swiper-container swiper-popular">
                        <div class="swiper-heading">
                            <h4 class="swiper-title-hidden">{{ trans_choice('messages.blog.popular', $popular_blogs->count()) }}</h4>
                            <h4 class="swiper-title">{{ trans_choice('messages.blog.popular', $popular_blogs->count()) }}</h4>

                            <div class="swiper-controls">
                                <button class="swiper-popular-prev"><i class="fa fa-angle-left"></i></button>
                                <button class="swiper-popular-next"><i class="fa fa-angle-right"></i></button>
                            </div>
                        </div>
                        <div class="swiper-wrapper">
                            @foreach($popular_blogs as $blog)
                                <div class="swiper-slide">
                                    @include('index.blog-card', ['blog'=>$blog])
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($new_blogs && $new_blogs->count())
                    <div class="swiper-container swiper-new">
                        <div class="swiper-heading">
                            <h4 class="swiper-title-hidden">{{ trans_choice('messages.blog.new', $new_blogs->count()) }}</h4>
                            <h4 class="swiper-title">{{ trans_choice('messages.blog.new', $new_blogs->count()) }}</h4>

                            <div class="swiper-controls">
                                <button class="swiper-new-prev"><i class="fa fa-angle-left"></i></button>
                                <button class="swiper-new-next"><i class="fa fa-angle-right"></i></button>
                            </div>
                        </div>
                        <div class="swiper-wrapper">
                            @foreach($new_blogs as $blog)
                                <div class="swiper-slide">
                                    @include('index.blog-card', ['blog'=>$blog])
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <div class="container mp-bottom-container">
        <div class="row">
            <div class="col-xs-12 sw">
                <div class="swiper-container swiper-bloggers">
                    <div class="swiper-heading">
                        <h4 class="swiper-title-hidden">{{ trans('messages.bloggers') }}</h4>
                        <h4 class="swiper-title">{{ trans('messages.bloggers') }}</h4>

                        <div class="swiper-controls">
                            <button class="swiper-bloggers-prev"><i class="fa fa-angle-left"></i></button>
                            <button class="swiper-bloggers-next"><i class="fa fa-angle-right"></i></button>
                        </div>
                    </div>
                    <div class="swiper-wrapper">
                        @foreach($bloggers as $blogger)
                            <div class="swiper-slide">
                                @include('index.blogger-card', ['blogger'=>$blogger])
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="swiper-container swiper-news">
                    <div class="swiper-heading">
                        <h4 class="swiper-title-hidden">{{ trans('messages.events_and_news') }}</h4>
                        <h4 class="swiper-title">{{ trans('messages.events_and_news') }}</h4>

                        <div class="swiper-controls">
                            <a href="" class="all-link" onclick="get_list(2, event)">{{ trans('messages.all_events') }}</a><br id="enter_mobile">
                            <a href="" class="all-link" onclick="get_list(1, event)">{{ trans('messages.all_news') }}</a>
                        </div>
                    </div>
                    <div class="swiper-wrapper list-slider">
                    @foreach($kz_news_row as $key => $main_news_item)   
                        <div class="swiper-slide">
                            <div class="card card-md card-fixed">
                                <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), $main_news_item->url) }}" style="text-decoration: none;">
                                @if($main_news_item->which==1)
                                    <div class="card-block">
                                        <div class="card-title cat-red">
                                        {{trans('messages.Новости')}}
                                        </div>
                                    </div>
                                    <div class="card-img" style="background-image: url('/news_photo/{{$main_news_item->image}}')">
                                    </div>
                                @else
                                    <div class="card-block">
                                        <div class="card-title cat-red">
                                        {{trans('messages.slider_events')}}
                                        </div>
                                    </div>
                                    <div class="card-img" style="background-image: url('/event_photo/{{$main_news_item->image}}')">
                                    </div>
                                @endif
                                </a>
                                <div class="card-body">
                                    <h3 class="card-title">
                                        <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), $main_news_item->url) }}">
                                            {{$main_news_item->title}}
                                        </a>
                                    </h3>

                                    <div class="dv">
                                        <span class="date">{{$main_news_item->date}}</span>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    </div>
                </div>
                <div class="infopovod" style="margin-top: 20px">
                    <a href="/events?from_blog=1" class="btn add_infopovod">
                        {{ trans('messages.add_infopovod') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('index.subscribe')

    <div class="container mp-container">
        <div class="row">
            <div class="col-xs-12 col-md-7">
                <p><a href="/events" style="text-decoration: underline;">{{ trans('messages.all_events') }}</a></p>
                <div class="calendar-slider">
                    <div class="eventss">
                    @foreach($events as $event)
                        <div class="media">
                            <div class="media-left media-middle">
                                <p class="date date-p">
                                    <strong class="time">{{$event->time_start}}</strong>
                                    <br>
                                    {{$event->date_start}}
                                </p>
                            </div>
                            @if(count($event->media)>0)
                            <div class="media-body">
                                <img src="/event_photo/{{$event->media[0]->link}}" class="media-object">
                            </div>
                            @endif
                            <div class="media-right">
                                <a href="/events/{{$event->event_url_name}}" style="color: #4c0404;">
                                    <h5 class="media-heading">{{$event->title}}</h5>
                                </a>
                                <p>{{$event->address}}</p>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-5">
                <div id="calendar"></div>
            </div>
            <style>
/*event in*/
.details {
  position: relative;
  width: 272px;
  /*height: 75px;*/
  background: #730046;
  margin-top: 5px;
  /*border-radius: 4px;*/
}

.details.in {
  -webkit-animation: moveFromTopFade .5s ease both;
  -moz-animation: moveFromTopFade .5s ease both;
  animation: moveFromTopFade .5s ease both;
}

.details.out {
  -webkit-animation: moveToTopFade .5s ease both;
  -moz-animation: moveToTopFade .5s ease both;
  animation: moveToTopFade .5s ease both;
}

.arrow {
  position: absolute;
  top: -5px;
  left: 50%;
  margin-left: -13px;
  width: 0px;
  height: 0px;
  border-style: solid;
  border-width: 0 5px 5px 5px;
  border-color: transparent transparent rgba(115,0,70,1) transparent;
  transition: all 0.7s ease;
}

.events {
/*  height: 75px;*/
  padding: 7px 0;
  overflow-y: auto;
  overflow-x: hidden;
}

.events.in {
  -webkit-animation: fadeIn .3s ease both;
  -moz-animation: fadeIn .3s ease both;
  animation: fadeIn .3s ease both;
}

.events.in {
  -webkit-animation-delay: .3s;
  -moz-animation-delay: .3s;
  animation-delay: .3s;
}

.details.out .events {
  -webkit-animation: fadeOutShrink .4s ease both;
  -moz-animation: fadeOutShink .4s ease both;
  animation: fadeOutShink .4s ease both;
}

.events.out {
  -webkit-animation: fadeOut .3s ease both;
  -moz-animation: fadeOut .3s ease both;
  animation: fadeOut .3s ease both;
}

.event {
  font-size: 16px;
  line-height: 22px;
  letter-spacing: .5px;
  padding: 2px 16px;
  vertical-align: top;
}

.event.empty {
  color: #eee;
}

.event-category {
  height: 10px;
  width: 10px;
  display: inline-block;
  margin: 6px 0 0;
  vertical-align: top;
}

.event span {
  display: inline-block;
  padding: 0 0 0 7px;
  color: #fff
}
.date-p{
    margin-bottom: 0;
}
/*event in*/
    .details{
        width: 100%;
    }
    .arrow{
        margin-left: 1px;
    }
            </style>
        </div>
    </div>

    <script>
        var swiper = new Swiper('.swiper-container.swiper-popular', {
            slidesPerView: 3,
            spaceBetween: 30,
            freeMode: true,
            nextButton: '.swiper-popular-next',
            prevButton: '.swiper-popular-prev',
            breakpoints: {
                768: {
                    slidesPerView: 1,
                }
            }
        });

        var swiperNew = new Swiper('.swiper-container.swiper-new', {
            slidesPerView: 3,
            spaceBetween: 30,
            freeMode: true,
            nextButton: '.swiper-new-next',
            prevButton: '.swiper-new-prev',
            breakpoints: {
                768: {
                    slidesPerView: 1,
                }
            }
        });

        var swiperBloggers = new Swiper('.swiper-container.swiper-bloggers', {
            slidesPerView: 4,
            spaceBetween: 30,
            freeMode: true,
            nextButton: '.swiper-bloggers-next',
            prevButton: '.swiper-bloggers-prev',
            breakpoints: {
                768: {
                    slidesPerView: 1,
                }
            }
        });

        var swiperNews = new Swiper('.swiper-container.swiper-news', {
            slidesPerView: 4,
            spaceBetween: 30,
            freeMode: true,
            breakpoints: {
                768: {
                    slidesPerView: 1,
                }
            }
        });
        var counters = {
            'popular': 4,
            'new': 4,
            'bloggers': 5,
        };

        function getPost(type) {
            $.ajax({
                type: 'GET',
                url: "{{route('post')}}",
                data: {type: type, count: counters[type]},
                success: function(data){

                    if (type === 'popular') {
                        swiper.appendSlide('<div class="swiper-slide">'+data+'</div>');
                    }
                    else if (type === 'new') {
                        swiperNew.appendSlide('<div class="swiper-slide">'+data+'</div>');
                    }
                    else if (type === 'bloggers') {
                        swiperBloggers.appendSlide('<div class="swiper-slide">'+data+'</div>');
                    }
                }
            });
            counters[type]++;
        }

        swiper.on('onSlideNextEnd', function () {
          getPost('popular');
        });

        swiperNew.on('onSlideNextEnd', function () {
          getPost('new');
        });

        swiperBloggers.on('onSlideNextEnd', function () {
          getPost('bloggers');
        });

    </script>
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

        clone.subtract('days', dayOfWeek+1);

        for(var i = dayOfWeek; i > 0 ; i--) {
            this.drawDay(clone.add('days', 1));
        }
    }

    Calendar.prototype.fowardFill = function() {
        var clone = this.current.clone().add('months', 1).subtract('days', 1);
        var dayOfWeek = clone.day();

        if(dayOfWeek === 6) { return; }

        for(var i = dayOfWeek; i < 6 ; i++) {
            this.drawDay(clone.add('days', 1));
        }
    }

    Calendar.prototype.currentMonth = function() {
        var clone = this.current.clone();

        while(clone.month() === this.current.month()) {
            this.drawDay(clone);
            clone.add('days', 1);
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

        console.log(todaysEventsIds);
        getByDayAndCategory(day.format(), null, todaysEventsIds);
        // var currentOpened = document.querySelector('.details');

        // //Check to see if there is an open detais box on the current row
        // if(currentOpened && currentOpened.parentNode === el.parentNode) {
        //   details = currentOpened;
        //   arrow = document.querySelector('.arrow');
        // } else {
        //   //Close the open events on differnt week row
        //   //currentOpened && currentOpened.parentNode.removeChild(currentOpened);
        //   if(currentOpened) {
        //     currentOpened.addEventListener('webkitAnimationEnd', function() {
        //       currentOpened.parentNode.removeChild(currentOpened);
        //     });
        //     currentOpened.addEventListener('oanimationend', function() {
        //       currentOpened.parentNode.removeChild(currentOpened);
        //     });
        //     currentOpened.addEventListener('msAnimationEnd', function() {
        //       currentOpened.parentNode.removeChild(currentOpened);
        //     });
        //     currentOpened.addEventListener('animationend', function() {
        //       currentOpened.parentNode.removeChild(currentOpened);
        //     });
        //     currentOpened.className = 'details out';
        //   }

        //   //Create the Details Container
        //   details = createElement('div', 'details in');

        //   //Create the arrow
        //   var arrow = createElement('div', 'arrow');

        //   //Create the event wrapper

        //   details.appendChild(arrow);
        //   el.parentNode.appendChild(details);
        // }

        // var todaysEvents = this.events.reduce(function(memo, ev) {
        //   if(ev.date.isSame(day, 'day')) {
        //     memo.push(ev);
        //   }
        //   return memo;
        // }, []);

        // this.renderEvents(todaysEvents, details);

        // arrow.style.left = el.offsetLeft - el.parentNode.offsetLeft + 27 + 'px';
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
        this.current.add('months', 1);
        this.next = true;
        this.draw();

        getByMonthAndCategory(this.current.format(), this.category);
    }

    Calendar.prototype.prevMonth = function() {
        this.current.subtract('months', 1);
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
    

function getByMonth ($date) {
    $.ajax({
        type: 'GET',
        url: "{{route('get_dates')}}",
        data: {date: $date},
        success: function(data){
            console.log(data);
            if(data.result === false){
                console.log("Ошибка при загрузки");
            }
            else{
                return data.dates;
            }
        }
    });
}

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
                // events[index]['date'] = moment(date).date( d.toISOString());
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
    
    if(ids.length>0){
        $.ajax({
            type: 'GET',
            url: "{{route('get_dates')}}",
            data: {date: date, category_id: category, ids: ids, type: 2},
            async: true,
            success: function(data){
                console.log(data);
                if(data.events.length>0){
                    addEvents(data.events);
                } else {
                    $('.eventss').empty();
                    let locale = '{{App::getLocale()}}';
                    let more = 'В этот день нет событий';
                    if(locale=="kz"){
                        more = 'Бұл күні ешқандай оқиғалар жоқ';
                    }
                    $('.eventss').append('<p>'+more+'</p>');
                }
            }
        });
    } else {
        $('.eventss').empty();
        let locale = '{{App::getLocale()}}';
        let more = 'В этот день нет событий';
        if(locale=="kz"){
            more = 'Бұл күні ешқандай оқиғалар жоқ';
        }
        $('.eventss').append('<p>'+more+'</p>');
    }
    $('.ajax-loader').fadeOut();
}
function addEvents(events){
    $('.eventss').empty();
    for(let i=0; i < events.length; i++){
        let image = "";
        if(events[i].media[0]){
            image = '<div class="media-body"><img src="/event_photo/'+events[i].media[0].link+'" class="media-object"></div>'
        }

        $('.eventss').append('<div class="media"><div class="media-left media-middle"><p class="date date-p"><strong class="time">'+events[i].time_start+'</strong><br>'+events[i].date_start+'</p></div>'+image+'<div class="media-right"><a href="/events/'+events[i].event_url_name+'" style="color: #4c0404;"><h5 class="media-heading">'+events[i].title+'</h5></a><p>'+events[i].address+'</p></div></div>');
    }
}
    </script>
<script>
function get_list(id,e){
  e.preventDefault();
  $.ajax({
    type: 'GET',
    url: "{{ LaravelLocalization::getLocalizedURL(App::getLocale(), '/blogs_get_list') }}",
    data: {id: id},
    success: function(data){
      if(data.result == false){
        alert("Ошибка при загрузке");
      }
      else{
        $('.list-slider').empty();
        // console.log(data);
        for(let i = 0; i<data.list.length ; i++){
          let which="";
          let view="";
          if(data.list[i].which==1){
            // which = "<img class='card-img-top' src='/news_photo/"+data.list[i].image+"'><div class='card-block'><div class='card-title cat-red'>{{trans('messages.Новости')}}</div></div>";
            which = "<div class='card-block'><div class='card-title cat-red'>{{trans('messages.Новости')}}</div></div><div class='card-img' style='background-image: url("+'"/news_photo/'+data.list[i].image+'"'+")'></div>";
            view = "<span class='views ml-20'><i class='icon icon-eye-gray mr-5'></i>"+data.list[i].view+"</span>";
          } else{
            // which = "<img class='card-img-top' src='/event_photo/"+data.list[i].image+"'><div class='card-block'><div class='card-title cat-red'>{{trans('messages.slider_events')}}</div></div>";
            which = "<div class='card-block'><div class='card-title cat-red'>{{trans('messages.slider_events')}}</div></div><div class='card-img' style='background-image: url("+'"/event_photo/'+data.list[i].image+'"'+"')'></div>";
          }
          // $('.list-slider').append("<div class='swiper-slide'><div class='card card-blog card-blog-small'>"+which+"<div class='card-footer'><div class='media'><div class='media-body'><a href='"+data.list[i].url+"'><h5 class='media-heading'>"+data.list[i].title+"</h5></a><p class='date'>"+data.list[i].date+"</p></div></div></div></div></div>"
          $('.list-slider').append("<div class='swiper-slide'><div class='card card-md card-fixed'><a href='"+data.list[i].url+"' style='text-decoration: none;'>"+which+"</a><div class='card-body'><h3 class='card-title'><a href='"+data.list[i].url+"'>"+data.list[i].title+"</a></h3><div class='dv'><span class='date'>"+data.list[i].date+"</span>"+view+"</div></div></div></div>");
        }
        swiperNews.update();
      }
    }
  });
  return false;
}
</script>
@endsection