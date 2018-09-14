

var UBT_Logic = function() {
    this.ActiveTimeout = 15; //1min
    this.IdleTimeout = 15; //1min
    this.IntervalId = 0;
    this.TimeFlag = "UBT_TimeFlag";
    this.TimeMsgFlag = "UBT_TimeMsgFlag";
    this.LastActiveTimeFlag = "UBT_LastActiveTimeFlag";
    this.LastUserActivityTimeFlag = "UBT_LastUserActivityTimeFlag";
};


function hide_banner(id) {
    
    $(id).removeClass('fadeIn').delay(100).addClass('fadeOut');
    $(id).hide();
    $('body').css('overflow-y', 'scroll');
};

function detectIE() {
  var ua = window.navigator.userAgent;

  var msie = ua.indexOf('MSIE ');
  if (msie > 0) {
    // IE 10 or older => return version number
    return true;
  }

  var trident = ua.indexOf('Trident/');
  if (trident > 0) {
    // IE 11 => return version number
    var rv = ua.indexOf('rv:');
    return true;
  }

  var edge = ua.indexOf('Edge/');
  if (edge > 0) {
    // Edge (IE 12+) => return version number
    return true;
  }

  // other browser
  return false;
}

UBT_Logic.prototype.StartFunc = function () {
    //here can be some additional logic
    ubtLogic.InitTimeFlag();
};

UBT_Logic.prototype.InitTimeFlag = function(){
    var now = Math.floor((new Date().getTime()) / 1000);
    if ((typeof localStorage[ubtLogic.LastActiveTimeFlag]) == "undefined") {
        localStorage[ubtLogic.LastActiveTimeFlag] = now;
        localStorage[ubtLogic.TimeFlag] = 0;
        localStorage[ubtLogic.TimeMsgFlag] = 0;
    } else {
        var t = parseInt(localStorage[ubtLogic.LastActiveTimeFlag]);
        if (t <= 0 || t + 300 < now || t > now) {
            localStorage[ubtLogic.LastActiveTimeFlag] = now;
            localStorage[ubtLogic.TimeFlag] = 0;
            localStorage[ubtLogic.TimeMsgFlag] = 0;
        }
    }
    if ((typeof localStorage[ubtLogic.TimeFlag]) == "undefined") {
        localStorage[ubtLogic.TimeFlag] = 0;
        localStorage[ubtLogic.TimeMsgFlag] = 0;
    } else {
        var t = parseInt(localStorage[ubtLogic.TimeFlag]);
        if (t < 0 || t > now) {
            localStorage[ubtLogic.TimeFlag] = 0;
            localStorage[ubtLogic.TimeMsgFlag] = 0;
        }
    }
    if ((typeof localStorage[ubtLogic.TimeMsgFlag]) == "undefined")
        localStorage[ubtLogic.TimeMsgFlag] = 0;
    else {
        var t = parseInt(localStorage[ubtLogic.TimeMsgFlag]);
        if (t != 0 && t != 1) localStorage[ubtLogic.TimeMsgFlag] = 0;
    }
    localStorage[ubtLogic.LastUserActivityTimeFlag] = now;
    document.onmousemove = function (event) {
        localStorage[ubtLogic.LastUserActivityTimeFlag] = Math.floor((new Date().getTime()) / 1000);
    };
    document.onkeydown = function (event) {
        localStorage[ubtLogic.LastUserActivityTimeFlag] = Math.floor((new Date().getTime()) / 1000);
    };
    IntervalId = setInterval(ubtLogic.TimeFunc, 300);
};

UBT_Logic.prototype.TimeFunc = function () {
    var now = Math.floor((new Date().getTime()) / 1000);
    //console.log(now);
    var lastActiveTimeFlag = parseInt(localStorage[ubtLogic.LastActiveTimeFlag]);
    if (lastActiveTimeFlag < now) {
        localStorage[ubtLogic.LastActiveTimeFlag] = now;
        var timeFlag = parseInt(localStorage[ubtLogic.TimeFlag]);
        timeFlag++;
        localStorage[ubtLogic.TimeFlag] = timeFlag;
        var timeMsgFlag = parseInt(localStorage[ubtLogic.TimeMsgFlag]);
        var lastUserActivityTimeFlag = parseInt(localStorage[ubtLogic.LastUserActivityTimeFlag]);
        if (timeMsgFlag == 0) {
            if (timeFlag >= ubtLogic.ActiveTimeout) {
                localStorage[ubtLogic.TimeMsgFlag] = 1;
                ubtLogic.ActiveTimeoutFunc();
            }
            else if (lastUserActivityTimeFlag + ubtLogic.IdleTimeout <= now) {
                localStorage[ubtLogic.TimeMsgFlag] = 1;
                ubtLogic.IdleTimeoutFunc();
            }
        }
        if (localStorage[ubtLogic.TimeMsgFlag] != 0) {
            clearInterval(IntervalId);
        }
    }
};

UBT_Logic.prototype.ActiveTimeoutFunc = function () {
    var md = new MobileDetect(window.navigator.userAgent);
    if (md.mobile() || md.phone() || md.tablet()) {
        var banners = ['#banner-blog', '#mobile-banner'];
        var randomItem = banners[Math.floor(Math.random()*banners.length)];
        console.log('mobile');
        $('body').css('overflow-y', 'hidden');
        if (randomItem === '#banner-blog') {
            // $("#banner").show().css('display', 'flex');
            // setTimeout(function(){ 
            //     hide_banner("#banner");
            // }, 10000);
            // $('.btn-close').click(function() {
            //   hide_banner("#banner");
            // });
            /*$(randomItem).show().css('display', 'block');
            setTimeout(function(){ 
                hide_banner(randomItem);
            }, 11000);
            $(randomItem+' .btn-close').click(function() {
              hide_banner(randomItem);
            });*/
        }
       /* else {
            $("#mobile-banner").show().css('display', 'block');
            setTimeout(function(){ 
                hide_banner("#mobile-banner");
            }, 10000);
        }*/

        if(md.is('iPhone') || md.is('iPod') || md.is('iPad')){
            $('#mob_url').attr('href', 'https://appsto.re/kz/hNIpeb.i');
        }
        else {
            $('#mob_url').attr('href', 'https://play.google.com/store/apps/details?id=com.bugingroup.almatytv&hl=ru');
        }
    }
    else if(!detectIE()){
        var banners = ['#banner-blog']; //'#banner', 
        var randomItem = banners[Math.floor(Math.random()*banners.length)];
        console.log('desktop');
        if (randomItem === '#banner') {
           $("#banner").show().css('display', 'flex');
            setTimeout(function(){ 
                hide_banner("#banner");
            }, 10000);
            $('.btn-close').click(function() {
              hide_banner("#banner");
            });
        }
        // else if(randomItem === '#nauryz'){
        //     $(randomItem).show().css('display', 'block');
        //     setTimeout(function() {
        //         $('.zoom').addClass('animate');
        //     }, 500);
        //     console.log('animate');
        //     setTimeout(function(){ 
        //         hide_banner(randomItem);
        //     }, 11000);
        //     $(randomItem+' .btn-close').click(function() {
        //       hide_banner(randomItem);
        //     });
        // }
    }
};

UBT_Logic.prototype.IdleTimeoutFunc = function () {
    //todo: do something
    //...
    console.log("IdleTimeout");
};

var ubtLogic = new UBT_Logic();
ubtLogic.StartFunc();