var map;

function initialize(lat, lon) {

	var location = new google.maps.LatLng(lat, lon);

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
	  zoom: 13,
	  center: location,
	  mapTypeId: 'styledMap',
	  streetViewControl: false,
	   disableDefaultUI: true
	};
	map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
	map.mapTypes.set('styledMap', new google.maps.StyledMapType(mapStyle,{ map: map }));

	//show users location on the map
	var marker = new google.maps.Marker({
	  position: location,
	  map: map,
	  title:"You"
	});
	
	//and finally display events on the map
	getEvents(map, lat, lon);
	
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

function getEvents(map, lat, lon){
	var json = $.getJSON("http://api.songkick.com/api/3.0/events.json?apikey=Fgp3vqBaiHFcCEJ0&location=geo:" + lat + "," + lon, function(data) {
		var events = data.resultsPage.results.event;
			
		for (var i=0;i<events.length;i++){
		
			var marker = new google.maps.Marker({ 
				position: new google.maps.LatLng(events[i].location.lat, events[i].location.lng), 
				map: map, 
				title: events[i].displayName,
				icon: "http://tunemaps.com/images/icon_event.png"
			});
			
		}
	});
}