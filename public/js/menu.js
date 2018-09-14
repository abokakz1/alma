 function myFunction(x) {
    x.classList.toggle("change");
}
var maxH=0;
 $(document).ready(function(){
    $('.column').each(function(){
        var h=this.offsetHeight;
        if(h>maxH) maxH=h;
        });
    $('.column').each(function(){
        var h=this.offsetHeight;
        if(h>maxH) maxH=h;
    });
    $('.column').css("height", maxH);
    $('.columnFooter').css("position", "absolute");
 });