/**
 * Global variables
 */
var markers = [];
var map = 0;
var eventIcon = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
var eventSelectedIcon = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";

/**
 * Page load function
 */
$(document).ready(function() {
    $('#content').bind('contentchanged', function() {
        if($('#content #event').length > 0) {
            
            // Initially hide all event descriptions
            $('#content .eventdescription').hide();
            
            // Create the map
            createMap();
            
            // Create the events on the map
            $('#content .event').each(function() {
                var latitude = $(this).children('.location').children('.latitude').contents().text();
                var longitude = $(this).children('.location').children('.longitude').contents().text();
                var id = $(this).attr('id').substring(5);
                var name = $(this).children('.eventtitle').contents().text();
                createMarker(id, name, latitude, longitude);
            });
            
            // Enable click events to show event descriptions
            $('#content .event').click(function() {
                var id = $(this).attr('id').substring(5);
                selectEvent(id);
            });
            
        }
    });
});

/**
 * Selects an event
 * 
 * @param eventid The id of the event to select
 */
function selectEvent(eventid) {
    
    // Modify selection in list of events
    $('#content .event').removeClass('selected');
    $('#event' + eventid).addClass('selected');
    $('#content .eventdescription').hide();
    $('#eventdescription' + eventid).show();
    
    // Modify selection in map
    for(id in markers) {
        markers[id].setIcon(eventIcon);
        if(markers[id].getZIndex() != 0) {
            markers[id].setZIndex(0);
        }
    }
    markers[eventid].setIcon(eventSelectedIcon)
    markers[eventid].setZIndex(100);
    
    // Smooth animation
    var from = {lat: map.getCenter().lat(), lng: map.getCenter().lng()};
    var to = {lat: markers[eventid].position.lat(), lng: markers[eventid].position.lng()};
    var lat = 0;
    var lng = 0;
    var update = false;
    $(from).animate(to, {duration: 700, step: function(now) {
        if(!update) {
            lat = now;
        } else {
            lng = now;
            map.setCenter(new google.maps.LatLng(lat, lng));
        }
        update = !update;
    }});
    
}

/**
 * Creates a marker for given event
 * 
 * @param eventid The event id
 * @param latitude The latitude
 * @param longitude The longitude
 */
function createMarker(eventid, name, latitude, longitude) {
    var loc = new google.maps.LatLng(latitude, longitude);
    var marker = new google.maps.Marker({ 
		position: loc, 
		map: map, 
        title: name,
		icon: eventIcon
	});
    markers[eventid] = marker;
    google.maps.event.addListener(marker, 'click', function() {
		selectEvent(eventid);
	});
}

/**
 * Creates the map using the google maps API
 */
function createMap() {
    
    // Configure all the google map settings
    var location = new google.maps.LatLng(52.0833, 4.3000);
    var mapOptions = {
        zoom: 8,
        center: location,
        mapTypeId: 'styledMap',
        streetViewControl: false,
        disableDefaultUI: true
	};
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
    
    // Create map object at the correct div
	map = new google.maps.Map(document.getElementById('map'), mapOptions);
	map.mapTypes.set('styledMap', new google.maps.StyledMapType(mapStyle,{ map: map }));
    
}