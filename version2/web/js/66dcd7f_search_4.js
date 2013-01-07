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
            findSongAndPlay(ui.item.artist, ui.item.title);
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
        $("#search input").autocomplete("close");
        loadContents($('#search form').attr('action') + '/' + $('#search input').val());
    });
    
    // Process full search page
    $('#content').bind('contentchanged', function() {
        if($('#content #searchresults').length > 0) {
            $('#content #searchresults .song').each(function() {
                var artist = $(this).children('.description').children('.artist').contents().text();
                var title = $(this).children('.description').children('.title').contents().text();
                $(this).click(function() {
                    findSongAndPlay(artist, title);
                });
            })
        }
    });
    
});
