function myMap() {
    var mapCanvas = document.getElementById("map");
    var mapOptions = {
            center: new google.maps.LatLng(map_lat_center, map_lon_center),
            zoom: map_zoom_level
    };
    var map = new google.maps.Map(mapCanvas, mapOptions);
}

function cleanData() {
    if (window.confirm('Are you sure to delete all data in the server?')) {
        window.location = 'cleandata.php';
    }
}

$(function() {
    console.log('hello world');
});