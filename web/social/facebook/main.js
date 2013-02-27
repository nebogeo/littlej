

function fb_interface(appid)
{   
    if (appid!="") 
    {
        console.log(appid);
        $(document).ready(function() {
	        FB.init({appId: appid, status: true, cookie: true, xfbml: true});
	    });
    }
    
    this.accessToken=false;
    this.uid=false;

    this.login=function()
    {
        var fb=this;
        console.log("attempting login");
        
        FB.getLoginStatus(function(response) {
            if (response.status=="connected") {
                console.log("logged in already...");
		        fb.uid = response.authResponse.userID;
		        fb.accessToken = response.authResponse.accessToken;
	        }
	        else
	        {
		        FB.login(function(response) {
                    console.log("logging in...");
			        if (response.authResponse) {
			            fb.accessToken = response.authResponse.accessToken;
			        }
		        }, {
                    scope:'user_about_me'
                });
	        }		
	    });
    }
}

$.ajaxSetup({
    error: function(xhr, status, error) {
        alert("An AJAX error occured: " + status + "\nError: " + error);
    }
});

var fb = new fb_interface(api_key);

function send_to(url) {    
    
    var g=$.post("/api", {
        task:"report",
        incident_title: document.getElementById("entry").value,
        incident_description: document.getElementById("desc").value,
        incident_date: "02/26/2013",
        incident_hour: "9",
        incident_minute: "31",
        incident_ampm: "pm",
        incident_category: "1",
        latitude: "0",
        longitude: "0",
        location_name: "home"
    }, function (data) {
        alert("sent...");
        //alert(JSON.stringify(data));
    });
}
                

