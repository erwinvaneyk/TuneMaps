 var crawling = false;
$(document).ready(function() {
    progress(0, 10, 0);
    
    //check only numbers
    $("#pages_from,#pages_to").keypress(function(e) {
        console.log('hit');
        if (e.which > 31 && (e.which < 48 || e.which > 57))
            return false;
        return true;
    });
    
    $("#startButton").click(function(e) {
        if(!crawling) { 
            if(parseInt($("#pages_from").val()) && parseInt($("#pages_to").val())) {
                crawling = true;
                crawlUsers($("#pages_from").val(),$("#pages_to").val());
                $("#startButton").val('Stop crawling');
            }
        } else {
            crawling = false;
            $("#startButton").val('Start crawling');
        }
    });
    println("Standby.");
});

function crawlUsers(page_from, page_to) {
    println("crawling started");
    progress(page_from,page_to+1,0);
    for(var i = page_from; i <= page_to; i++) {
        if(crawling) {
            $.ajax({
                url: "users/" + i,
                async: false,
                success: function (response) {
                    if(!('error' in response)) {
                        users = response; 
                        for(var key in users.users) {
                            crawlTracks(users.users[key],1,1);
                        }
                        println(response.inserts + " new users retrieved on page " + i);
                    } else {
                        println("error: " + response.error);
                        crawling = false;
                    }
                    progress(page_from,page_to+1,i);
                }
            });
        }
    }
    println("crawling ended");
}

function crawlTracks(username,page_from,page_to) {
    for(var i = page_from; i <= page_to; i++) {
        if(crawling) {
            $.ajax({
                url: "recenttracks/" + username + "/" + i,
                async: false,
                success: function (response) {
                    if(!('error' in response)) {
                        recenttracks = response; 
                        println(100 + " new tracks retrieved from user \"" + username + "\" on page " + i);
                    } else {
                        println("error: " + response.error);
                        crawling = false;
                    }
                    progress(page_from,page_to+1,i);
                }
            });
        }
    }
}

function progress(begin, end, current) {
    var nvalue = current*(100/(end-begin));
    $( "#progressbar" ).progressbar({
        value: nvalue
    })
}

function println(line) {
    $("#log").html(line + "\n" + $("#log").html());
}

console.log("loaded");