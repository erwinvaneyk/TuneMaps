$(document).ready(function() {
    var opts = {
        lines: 15, // The number of lines to draw
        length: 7, // The length of each line
        width: 3, // The line thickness
        radius: 18, // The radius of the inner circle
        corners: 1, // Corner roundness (0..1)
        rotate: 8, // The rotation offset
        color: '#000', // #rgb or #rrggbb
        speed: 0.9, // Rounds per second
        trail: 78, // Afterglow percentage
        shadow: false, // Whether to render a shadow
        hwaccel: false, // Whether to use hardware acceleration
        className: 'spinner', // The CSS class to assign to the spinner
        zIndex: 2e9, // The z-index (defaults to 2000000000)
        top: '50%', // Top position relative to parent in px
        left: '50%' // Left position relative to parent in px
    };
    
    $('#navigation a').each(function(index, item) {
        if($(item).attr('href').match(/^#/)) {
            $(item).click(function(event) {
                event.preventDefault();
                $('#content').html('<div id="spinner"></div>');
                var spinner = new Spinner(opts).spin(document.getElementById('spinner'));
                loadContents($(item).attr('href').substring(1));
            })
        }
    })
});
function loadContents(contentUrl) {
    $.ajax({
        url: contentUrl
    }).done(function(data) {
        $('#content').html(data);
    }).error(function(xhr, ajaxOptions, thrownError) {
        $('#content').html('<h1 class="error">' + xhr.status + ' - ' + thrownError + '</h1>');
    });
}