

$.ajaxSetup({
    error: function(xhr, status, error) {
        alert("An AJAX error occured: " + status + "\nError: " + error);
    }
});

function send_to(url) {    
    var g=$.post("../littlej/api", {
        task:"report",
        incident_title: "from fb",
        incident_description: "helloooo",
        incident_date: "02/26/2013",
        incident_hour: "9",
        incident_minute: "31",
        incident_ampm: "pm",
        incident_category: "1",
        latitude: "0",
        longitude: "0",
        location_name: "home"
    }, function (data) {
        alert(JSON.stringify(data));
    });
}
                

