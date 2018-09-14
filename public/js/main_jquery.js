jQuery(document).ready(function($) {
    $('.toggle-menu').jPushMenu();
});

var maxHeight = 0;
var maxH = 0;

var width = $(window).width();



$(document).ready(function() {
    if (width >= 767) {


        $('.column').each(function () {
            var h = this.offsetHeight;
            if (h > maxH) maxH = h;
        });
        $('.column').each(function () {
            var h = this.offsetHeight;
            if (h > maxH) maxH = h;
        });
        $('.column').css("height", maxH);
        $('.columnFooter').css("position", "absolute");


        if (width >= 992) {

            $(".list_main_page").each(function () {
                if ($(this).height() > maxHeight) {
                    maxHeight = $(this).height();
                }
            });
            $(".list_main_page").height(maxHeight);
        }
    }
});


    $(window).resize(function() {

        if (width >= 767) {

            $('.column').each(function() {
                var h = this.offsetHeight;
                if (h > maxH) maxH = h;
            });
            $('.column').each(function() {
                var h = this.offsetHeight;
                if (h > maxH) maxH = h;
            });
            $('.column').css("height", maxH);
            $('.columnFooter').css("position", "absolute");

            if (width >= 992) {
                $(".list_main_page").each(function() {
                    if ($(this).height() > maxHeight) {
                        maxHeight = $(this).height();
                    }
                });
                $(".list_main_page").height(maxHeight);
            }
        }

    });
(function() {

    "use strict";

    var toggles = document.querySelectorAll(".c-hamburger");

    for (var i = toggles.length - 1; i >= 0; i--) {
        var toggle = toggles[i];
        toggleHandler(toggle);
    };

    function toggleHandler(toggle) {
        toggle.addEventListener("click", function(e) {
            e.preventDefault();
            (this.classList.contains("is-active") === true) ? this.classList.remove("is-active"): this.classList.add("is-active");
        });
    }

})();

var count = $('.blockNewsText').length;
for (var k = 0; k < count; k++) {
    var text = $('.blockNewsText a').eq(k).text();
    arr = text.split('');
    var length = arr.length;
    if (length > 95) {
        $('.blockNewsText a').eq(k).text('');
        var string = "";
        for (var i = 0; i < 95; i++) {
            string += arr[i];
        }
        string += '...';
        $('.blockNewsText a').eq(k).text(string);
    }
}
$(document).ready(function() {
            $(".poisk").click(function() {
                $(".footer_inputs1").toggle();
            });

        });