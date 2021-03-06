///////////////////////////////////////////////////////////////////////////
// little j facebook app (C) 2013 Dave Griffiths GPLv3



///////////////////////////////////////////////////////////////////////////
// time formatting stuff

// Simulates PHP's date function
Date.prototype.format = function(format) {
    var returnStr = '';
    var replace = Date.replaceChars;
    for (var i = 0; i < format.length; i++) {       var curChar = format.charAt(i);         if (i - 1 >= 0 && format.charAt(i - 1) == "\\") {
            returnStr += curChar;
        }
        else if (replace[curChar]) {
            returnStr += replace[curChar].call(this);
        } else if (curChar != "\\"){
            returnStr += curChar;
        }
    }
    return returnStr;
};

Date.replaceChars = {
    shortMonths: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    longMonths: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    shortDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    longDays: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],

    // Day
    d: function() { return (this.getDate() < 10 ? '0' : '') + this.getDate(); },
    D: function() { return Date.replaceChars.shortDays[this.getDay()]; },
    j: function() { return this.getDate(); },
    l: function() { return Date.replaceChars.longDays[this.getDay()]; },
    N: function() { return this.getDay() + 1; },
    S: function() { return (this.getDate() % 10 == 1 && this.getDate() != 11 ? 'st' : (this.getDate() % 10 == 2 && this.getDate() != 12 ? 'nd' : (this.getDate() % 10 == 3 && this.getDate() != 13 ? 'rd' : 'th'))); },
    w: function() { return this.getDay(); },
    z: function() { var d = new Date(this.getFullYear(),0,1); return Math.ceil((this - d) / 86400000); }, // Fixed now
    // Week
    W: function() { var d = new Date(this.getFullYear(), 0, 1); return Math.ceil((((this - d) / 86400000) + d.getDay() + 1) / 7); }, // Fixed now
    // Month
    F: function() { return Date.replaceChars.longMonths[this.getMonth()]; },
    m: function() { return (this.getMonth() < 9 ? '0' : '') + (this.getMonth() + 1); },
    M: function() { return Date.replaceChars.shortMonths[this.getMonth()]; },
    n: function() { return this.getMonth() + 1; },
    t: function() { var d = new Date(); return new Date(d.getFullYear(), d.getMonth(), 0).getDate(); }, // Fixed now, gets #days of date
    // Year
    L: function() { var year = this.getFullYear(); return (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0)); },   // Fixed now
    o: function() { var d  = new Date(this.valueOf());  d.setDate(d.getDate() - ((this.getDay() + 6) % 7) + 3); return d.getFullYear();}, //Fixed now
    Y: function() { return this.getFullYear(); },
    y: function() { return ('' + this.getFullYear()).substr(2); },
    // Time
    a: function() { return this.getHours() < 12 ? 'am' : 'pm'; },
    A: function() { return this.getHours() < 12 ? 'AM' : 'PM'; },
    B: function() { return Math.floor((((this.getUTCHours() + 1) % 24) + this.getUTCMinutes() / 60 + this.getUTCSeconds() / 3600) * 1000 / 24); }, // Fixed now
    g: function() { return this.getHours() % 12 || 12; },
    G: function() { return this.getHours(); },
    h: function() { return ((this.getHours() % 12 || 12) < 10 ? '0' : '') + (this.getHours() % 12 || 12); },
    H: function() { return (this.getHours() < 10 ? '0' : '') + this.getHours(); },
    i: function() { return (this.getMinutes() < 10 ? '0' : '') + this.getMinutes(); },
    s: function() { return (this.getSeconds() < 10 ? '0' : '') + this.getSeconds(); },
    u: function() { var m = this.getMilliseconds(); return (m < 10 ? '00' : (m < 100 ?
'0' : '')) + m; },
    // Timezone
    e: function() { return "Not Yet Supported"; },
    I: function() {
        var DST = null;
            for (var i = 0; i < 12; ++i) {
                    var d = new Date(this.getFullYear(), i, 1);
                    var offset = d.getTimezoneOffset();

                    if (DST === null) DST = offset;
                    else if (offset < DST) { DST = offset; break; }                     else if (offset > DST) break;
            }
            return (this.getTimezoneOffset() == DST) | 0;
        },
    O: function() { return (-this.getTimezoneOffset() < 0 ? '-' : '+') + (Math.abs(this.getTimezoneOffset() / 60) < 10 ? '0' : '') + (Math.abs(this.getTimezoneOffset() / 60)) + '00'; },
    P: function() { return (-this.getTimezoneOffset() < 0 ? '-' : '+') + (Math.abs(this.getTimezoneOffset() / 60) < 10 ? '0' : '') + (Math.abs(this.getTimezoneOffset() / 60)) + ':00'; }, // Fixed now
    T: function() { var m = this.getMonth(); this.setMonth(0); var result = this.toTimeString().replace(/^.+ \(?([^\)]+)\)?$/, '$1'); this.setMonth(m); return result;},
    Z: function() { return -this.getTimezoneOffset() * 60; },
    // Full Date/Time
    c: function() { return this.format("Y-m-d\\TH:i:sP"); }, // Fixed now
    r: function() { return this.toString(); },
    U: function() { return this.getTime() / 1000; }
};

///////////////////////////////////////////////////////////////////////////
// facebook stuff

function fb_interface(appid)
{
    if (appid!="")
    {
        var fb=this;
        $(document).ready(function() {
	        FB.init({appId: appid, status: true, cookie: true, xfbml: true});
            fb.login();
	    });
    }

    this.accessToken=false;
    this.uid=false;
    this.me=null;

    this.get_user=function() {
        var fb=this;
        FB.api('/me', function(response) {
            fb.me = response;
        });
    };

    this.login=function()
    {
        var fb=this;
        console.log("attempting login");

        FB.getLoginStatus(function(response) {
            if (response.status=="connected") {
                console.log("logged in already...");
		        fb.uid = response.authResponse.userID;
		        fb.accessToken = response.authResponse.accessToken;
                fb.get_user();
	        }
	        else
	        {
		        FB.login(function(response) {
                    console.log("logging in...");
			        if (response.authResponse) {
			            fb.accessToken = response.authResponse.accessToken;
                        fb.get_user();
			        }
		        }, {
                    scope:'user_about_me'
                });
	        }
	    });
    }
}

///////////////////////////////////////////////////////////////////////////
// main stuff

$.ajaxSetup({
    error: function(xhr, status, error) {
        alert("An AJAX error occured: " + status + "\nError: " + error);
    }
});


fb = new fb_interface(api_key);

function send_to(url) {

    var default_loc = {
        coords:
        {
            latitude:51.57122,
            longitude:-3.85354
        },
        address:
        {
            city: "unknown"
        }
    };

    if (navigator.geolocation &&
        document.getElementById("location").checked) {
	console.log("getting location...");
        navigator.geolocation.getCurrentPosition(function (pos) {
            send(pos);
        },
						 function () {
						     console.log("failed to get location...");
						     send(default_loc);
						 },
						 {timeout:10000});
    }
    else {
        send(default_loc);
    }
}

function build_categories(id) {
    var g=$.post("/json/categories", {}, function (data) {
        var select = document.getElementById(id);
        data.forEach(function(cat) {
            // don't add the route categories
            if (cat.title!="Route" &&
                cat.title!="Twitter" &&
                cat.title!="Facebook" &&
                cat.title!="Android App" &&
                cat.title!="SMS" &&
                cat.title!="Trusted" &&
                cat.title!="Assignments" &&
                cat.title!="Site") {

                var option=document.createElement("option");
                option.text=cat.title;
                option.value=cat.id;
                select.add(option,null);
            }
        });
    });
}

function send(pos) {
    var now = new Date();
    var hours = now.getHours();
    var ampm = hours >= 12 ? 'pm' : 'am';

    var date = now.format("m/d/Y");
    var hour = ((now.format("H")-1)%12)+1;
    var min = now.format("i");

    var loc = "unknown";
    if (pos.address) {
        loc = pos.address.city;
    }

    var first_name="";
    var last_name="";

    if (document.getElementById("identity").checked) {
        first_name=fb.me.first_name;
        last_name=fb.me.last_name;
    }

    var e = document.getElementById("categories");
    var category_selected = e.options[e.selectedIndex].value;

    console.log("sending...");

    var g=$.post("/api", {
        task:"report",
        incident_title: document.getElementById("entry").value,
        incident_description: document.getElementById("desc").value,
        incident_date: date,
        incident_hour: hour,
        incident_minute: min,
        incident_ampm: ampm,
        incident_category: "18, "+category_selected,
        latitude: pos.coords.latitude,
        longitude: pos.coords.longitude,
        location_name: loc,
        person_first: first_name,
        person_last: last_name

    }, function (data) {
        alert("Thanks. Your report has been sent over to the main Little J site and the Port Talbot Magnet journalists will review it very soon.");
    });
}
