function myMap() {
    var mapCanvas = document.getElementById("map");
    var mapOptions = {
        center: new google.maps.LatLng(map_lat_center, map_lon_center),
        zoom: map_zoom_level
    };
    var map = new google.maps.Map(mapCanvas, mapOptions);

    var markerBounds = new google.maps.LatLngBounds();
    for (var i = 0; i < data.lat.length; i++) {
        var point = new google.maps.LatLng(data.lat[i], data.lon[i]);
        new google.maps.Marker({
            position: point,
            map: map
        });
        markerBounds.extend(point);
    }

    if (data.lat.length > 0) {
        map.fitBounds(markerBounds);
    }
}

function cleanData() {
    if (window.confirm('Are you sure to delete all data in the server?')) {
        window.location = 'cleandata.php';
    }
}

$(function () {
    // Based on http://config9.com/programming/nodejs/jqplot-draw-multiple-lines-with-time-series-chart-example/
    var hum = data.hum;
    var lig = data.lig;
    var tem = data.tem;

    $(document).ready(function () {
        $.jqplot('chart', [hum, lig, tem], {
            title: 'Humidty(%)/Light(%)/Temperature(&deg;C)',
            axes: {
                xaxis: {
                    renderer: $.jqplot.DateAxisRenderer,
                    tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                    tickOptions: {
                        formatString: '%d/%m/%y %H:%M:%S',
                        angle: -30
                    },
                    autoscale: false
                },
                yaxis: {
                    min:0,
                    max:100
                }
            },
            series: [
                {label: 'Humidty'},
                {label: 'Light'},
                {label: 'Temperature'}
            ],
            legend: {
                location: 'ne', showLabels: true, show: true
            },
            highlighter: {
                show:true,
                sizeAdjust: 10,
                tooltipLocation: 'n',
                tooltipAxes: 'y',
                tooltipFormatString: '%.2f',
                useAxesFormatters: false
            },
            cursor: {
                show: true
            }
        });
    });
});