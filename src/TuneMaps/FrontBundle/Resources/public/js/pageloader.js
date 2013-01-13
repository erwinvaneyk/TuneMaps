$(document).ready(function() {
    $('#navigation a,#logo a').each(function(index, item) {
        if($(item).attr('href').match(/^#/)) {
            $(item).click(function(event) {
                event.preventDefault();
                loadContents($(item).attr('href').substring(1));
            });
        }
    });
    $('#navigation a:first').click();
    getLocation();
});
function loadContents(contentUrl) {
    displaySpinner();
    $.ajax({
        url: contentUrl
    }).done(function(data) {
        $('#content').html(data);
        $('#content').trigger('contentchanged');
    }).error(function(xhr, ajaxOptions, thrownError) {
        $('#content').html('<h1 class="error">' + xhr.status + ' - ' + thrownError + '</h1>');
    });
}

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            $.ajax({
                url: 'location/' + position.coords.latitude + "/" + position.coords.longitude
            });
        });
    } else { x.innerHTML="Geolocation is not supported by this browser."; }
}

function displaySpinner() {
    $('#content').html('<div id="spinner"></div>');
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
    var spinner = new Spinner(opts).spin(document.getElementById('spinner'));
}