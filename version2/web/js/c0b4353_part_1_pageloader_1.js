$(document).ready(function() {
    $('#navigation a').each(function(item) {
        alert(item);
    })
});

function loadContents(contentUrl) {
    $.ajax({
        url: contentUrl
    }).done(function(data) {
        $('#content').html(data);
    });
}