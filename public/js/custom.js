$(document).ready(function() {
	
	$("body").css("display", "none");

    $("body").fadeIn(700);
    
	$("a.transition").click(function(event){
		event.preventDefault();
		linkLocation = this.href;
		$("body").fadeOut(10000, redirectPage);		
	});
		
	function redirectPage() {
		window.location = linkLocation;
	}
	
});
