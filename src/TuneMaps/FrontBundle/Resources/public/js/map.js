var map;

function initialize(lat, lon) {

	var userLocation = new google.maps.LatLng(lat, lon);

	/* custom map */
	var stylez = [
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
	
	var mapOptions = {
	  zoom: 10,
	  center: userLocation,
	  //mapTypeId: google.maps.MapTypeId.TERRAIN,
	  streetViewControl: false,
	   disableDefaultUI: true
	};
	map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
	
	 var styledMapOptions = {
	 map: map,
	 name: "tips4phpHip-Hop"
	 }

	 var testmap =  new google.maps.StyledMapType(stylez,styledMapOptions);

	//show users location on the map
	var marker = new google.maps.Marker({
	  position: userLocation,
	  map: map,
	  title:"You"
	});
	 
	 map.mapTypes.set('tips4php', testmap);
	 map.setMapTypeId('tips4php');
	
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