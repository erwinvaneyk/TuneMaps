var map;

function initialize(lat, lon) {

	var userLocation = new google.maps.LatLng(lat, lon);

	var mapOptions = {
	  zoom: 12,
	  center: userLocation,
	  mapTypeId: google.maps.MapTypeId.TERRAIN,
	  streetViewControl: false,
	   disableDefaultUI: true
	};
	map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

	//show users location on the map
	var marker = new google.maps.Marker({
	  position: userLocation,
	  map: map,
	  title:"You"
	});
	
}

getLocation();

function getLocation(){
  if (navigator.geolocation)
    navigator.geolocation.getCurrentPosition(showLocation,showError);
  else
	alert("Geolocation is not supported by this browser.");
}
  
function showLocation(position){
	initialize(position.coords.latitude, position.coords.longitude);
}  
  
function showError(error){
  initialize(52.008238, 4.365864);
}