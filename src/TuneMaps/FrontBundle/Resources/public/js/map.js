//setup map
var map;
getUserLocation();

//initializes map centered at lat,lon in the element with id 'map_canvas'
function initializeMap(lat, lon) {
	var location = new google.maps.LatLng(lat, lon);
	
	//initialize map
	var mapOptions = {
	  zoom: 9,
	  center: location,
	  mapTypeId: 'styledMap',
	  streetViewControl: false,
	   disableDefaultUI: true
	};
        
        //insert map object into the element with id 'map_canvas'
	map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
	map.mapTypes.set('styledMap', new google.maps.StyledMapType(mapStyle(),{ map: map }));

	//show location of the user on the map
	new google.maps.Marker({
	  position: location,
	  map: map,
	  title:"You"
	});
	
	//display events on the map
	getEvents(map, lat, lon);
}

//returns the current mapStyle options 
function mapStyle() {
    return [
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
}

//Retrieve the location of the user (via the browser)
function getUserLocation(){
  if (navigator.geolocation)
    navigator.geolocation.getCurrentPosition(showMapByLocation,showDefaultMap);
  else
	alert("Geolocation is not supported by this browser.");
}
  
//Show map centered around a position
function showMapByLocation(position){
	initializeMap(position.coords.latitude, position.coords.longitude);
}  

//Show map centered around a default location (if user doesn't supply his location
function showDefaultMap(error){
  initializeMap(52.008238, 4.365864);
}

//retrieve events in the nearby area
function getEvents(map, lat, lon){
    $.getJSON("getEvents?&distance=300&long=" + lon + "&lang=" + lat + "&limit=100", function(events) {
        for (var i=0;i<events.length;i++){
            new google.maps.Marker({ 
                position: new google.maps.LatLng(events[i].venue.location.lattitude, events[i].venue.location.longitude), 
                map: map, 
                title: events[i].name + " in " + events[i].venue.name,
                icon: "http://tunemaps.com/images/icon_event.png"
            });
        }
    });
}