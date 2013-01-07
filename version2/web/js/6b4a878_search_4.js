$(document).ready(function() {
    
    // Autocomplete search
    var cache = {};
    $( "#search input" ).autocomplete({
        minLength: 2,
        source: function( request, response ) {
            var term = request.term;
            if ( term in cache ) {
                response( cache[ term ] );
                return;
            }
            $.getJSON( $('#search form').attr('action') + 'widget/' + $('#search input').val(), request, function( data, status, xhr ) {
                cache[term] = data;
                response( data );
            });
        },
        select: function( event, ui ) {
            
            // Play song with the following search string on youtube:
            // ui.item.youtube
            
        }
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .data( "item.autocomplete", item.youtube )
            .append( "<a class=\"autocomplete\"><strong>" + item.title + "</strong><br />" + item.artist + "</a>" )
            .appendTo( ul );
    };
    
    // Full search
    $("#search form").submit(function(event) {
        event.preventDefault();
        console.log("debug 1");
        $("#search input").autocomplete("close");
        loadContents($('#search form').attr('action') + '/' + $('#search input').val());
    });
    
    // Process full search page
    $('#content').bind('contentchanged', function() {
        if($('#content #searchresults').length > 0) {
            
            
            
        }
    });
    
});
