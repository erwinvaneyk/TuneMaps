$(document).ready(function() {

	//set container dimensions
	setContainerDimensions();
	
	//music player
	function tick(){
		$('#player li:first').animate({'opacity':0}, 200, function () { $(this).appendTo($('#player ul')).css('opacity', 1); });
	}
	setInterval(function(){ tick () }, 4000);
	
	//handle search action
	$('.search form').submit(function(){ 
		getSearchResults(); 
		return false; 
	});
	
});

//if window is resized then reset dimensions
$(window).resize(setContainerDimensions);

function setContainerDimensions(){
	$('#container').css('width', (document.documentElement.offsetWidth-200) + 'px');
	$('#container').css('height', (window.innerHeight-50) + 'px');
	$('.bigmap').css('height', (window.innerHeight-50) + 'px');
	return;
}

function getSearchResults(){
	//delete old results
	if ($('#menu ul li').hasClass('searchres')){
		$('#menu ul li').remove('.searchres');
		$('#menu ul li').remove('.track');
	}
	
	/* >> ajax call here <<
	 * show spinner while loading
	 * do not allow users to send request twice
	 */
	
	//display results
	animatedShow('#menu ul', 'label searchres', 'Search results', 1);
	if ($('#searchbar').val() == '')
		animatedShow('#menu ul', 'track', 'No results', 1);
	else
		animatedShow('#menu ul', 'track', '<a href="">Some Nights by Fun . <img src="http://images.pricerunner.com/product/100x100/332940604/Fun.-Some-Nights.jpg" /></a>', 3);
}

function animatedShow(element, classtype, text, n){
	var newItem = $('<li class="' + classtype + '">' + text + '</li>').hide();
	$(element).append(newItem);
	if (n > 0)
		newItem.slideDown("500", function(){animatedShow(element, classtype, text, n-1)});
	return newItem;
}