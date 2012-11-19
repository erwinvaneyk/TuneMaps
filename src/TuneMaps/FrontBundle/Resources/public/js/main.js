
$(document).ready(function() {

	//music player
	function tick(){
		$('#player li:first').animate({'opacity':0}, 200, function () { $(this).appendTo($('#player ul')).css('opacity', 1); });
	}
	setInterval(function(){ tick () }, 4000);
	
	
});