$(document).ready(function() {
    $('#content').bind('contentchanged', function() {
        if($('#content #chart').length > 0) {
            $('#chart .song').each(function() {
                var artist = $(this).children('.description').children('.artist').contents().text();
                var title = $(this).children('.description').children('.title').contents().text();
                $(this).click(function() {
                    findSongAndPlay(artist, title);
                });
            })
        }
    });
});
