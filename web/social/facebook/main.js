

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
    }

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

var latlon=[0,0];

function get_location() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position);
    }
}

function position(pos) {
    latlon[0]=pos.coords.latitude;
    latlon[1]=pos.coords.longitude; 
}

$.ajaxSetup({
    error: function(xhr, status, error) {
        alert("An AJAX error occured: " + status + "\nError: " + error);
    }
});

var fb = new fb_interface(api_key);
get_location();

function send_to(url) {    
    
    var now = new Date();
    var hours = now.getHours();
    var ampm = hours >= 12 ? 'pm' : 'am';
    
    var date = now.format("m/d/Y");
    var hour = now.format("H");
    var min = now.format("M");

    var g=$.post("/api", {
        task:"report",
        incident_title: document.getElementById("entry").value,
        incident_description: document.getElementById("desc").value,
        incident_date: date,
        incident_hour: hour,
        incident_minute: min,
        incident_ampm: ampm,
        incident_category: "1",
        latitude: latlon[0],
        longitude: latlon[1],
        location_name: "unknown",
        person_first: fb.me.first_name, 
        person_last: fb.me.last_name
        
    }, function (data) {
        alert("sent...");
        //alert(JSON.stringify(data));
    });
}
                

