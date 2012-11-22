var map;

function initialize(lat, lon) {

	var userLocation = new google.maps.LatLng(lat, lon);

	//custom map style
	var mapStyle = [
	  {
		"featureType": "road",
		"stylers": [
		  { "visibility": "off" }
		]
	  },{
		"featureType": "administrative",
		"stylers": [
		  { "visibility": "on" },
		  { "weight": 0.5 },
		  { "gamma": 1.8 }
		]
	  },{
		"stylers": [
		  { "saturation": 13 }
		]
	  } 
	];
	
	//initialize map
	var mapOptions = {
	  zoom: 10,
	  center: userLocation,
	  mapTypeId: 'styledMap',
	  streetViewControl: false,
	   disableDefaultUI: true
	};
	map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
	map.mapTypes.set('styledMap', new google.maps.StyledMapType(mapStyle,{ map: map }));

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