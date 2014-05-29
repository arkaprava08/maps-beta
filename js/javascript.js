var map;
var exitMarkers = [];

var latitude = null;
var longitude = null;
var metro_id = null;


var filter = '';

var toggle = 0;

/* drag marker variables */
var iniLatitude = null;
var iniLongitude = null;


function initialize() {
    var mapOptions = {
        center: new google.maps.LatLng(20, 20),
        zoom: 2,
        mapTypeId: google.maps.MapTypeId.HYBRID
    };
    map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);



    /* add double click event to add exit marker */
    google.maps.event.addListener(map, 'dblclick', function(event) {
        var lat = event.latLng.lat();
        var lng = event.latLng.lng();

        var Latlng = new google.maps.LatLng(lat, lng);

        createMarkPoints(Latlng);
    });

}
google.maps.event.addDomListener(window, 'load', initialize);


function updateExitMarker(marker)
{
    $.get("updateExitMarker.php?inilatitude=" + iniLatitude.toFixed(10)
            + "&inilongitude=" + iniLongitude.toFixed(10)
            + "&latitude=" + marker.position.lat().toFixed(10)
            + "&longitude=" + marker.position.lng().toFixed(10), function(data, status) {
        if (status === "success")
        {
            alert(data);
        }
    });
}
function createMarkPoints(pos) //for marking **********
{


    if (map.getZoom() < 19)
    {
        return;
    }
    if (toggle === 0)
    {
        tooltip();
        return;
    }
    if (metro_id === null)
    {
        alert('no metro selected');
        return;
    }
    if (!confirm("Set the marker ?"))
    {
        return;
    }

    var img = 'img/exit.png';
    var marker = new google.maps.Marker({
        position: pos,
        map: map,
        icon: img,
        draggable: true
    });

    $.get("set_exits.php?metro_id=" + metro_id + "&latitude=" + marker.position.lat().toFixed(10) + "&longitude=" + marker.position.lng().toFixed(10), function(data, status) {
        if (status === "success")
        {
            alert(data);
            exitMarkers.push(marker);
        }
    });
    //edit here for delete marker
    google.maps.event.addListener(marker, 'dragstart', function() {
        iniLatitude = marker.position.lat();
        iniLongitude = marker.position.lng();
    });
    google.maps.event.addListener(marker, 'dragend', function() {
        updateExitMarker(marker);
    });


    google.maps.event.addListener(marker, 'click', function() {
        var zoomlevel = map.getZoom();
        map.setZoom(zoomlevel + 2);
        map.setCenter(marker.getPosition());
    });

    google.maps.event.addListener(marker, 'rightclick', function() {

        if (toggle === 1 && confirm("Delete marker ?"))
        {


            $.get("deleteMarker.php?latitude=" + marker.position.lat().toFixed(10) + "&longitude=" + marker.position.lng().toFixed(10), function(data, status) {
                if (status === "success")
                {
                    alert(data);
                    marker.setMap(null);
                }
            });
        }
        if (toggle === 0)
        {
            tooltip();
        }
    });
}


/* ajax for updating metro list */

function fetchMetroList() {
    $.get("fetch_metros.php?real_city=" + filter, function(data, status) {
        if (status === "success")
        {
            document.getElementById('metro-data').innerHTML = data;


            //add marker to all metro locations
            var metrodataitem = document.getElementById('metro-data').childNodes[0];
            var len = metrodataitem.children.length;

            for (var i = 0; i < len; i++)
            {
                var locname = metrodataitem.children[i].children[0].innerHTML;
                var templat = $(metrodataitem.children[i]).data("values").latitude;
                var templng = $(metrodataitem.children[i]).data("values").longitude;
                var metro_id = $(metrodataitem.children[i]).data("values").metro_id;

                var tempLatlng = new google.maps.LatLng(templat, templng);

                createMarker(tempLatlng, locname, metro_id);

            }

        }
    });
}

function fetchMetroExitList() {

    $.ajax({
        url: 'fetch_metroexits.php',
        type: 'get',
        dataType: 'json',
        success: function(data) {
            var jsondata = JSON.parse(JSON.stringify(data));

            for (var i = 0; i < jsondata.length; i++)
            {
                var pos = new google.maps.LatLng(jsondata[i].latitude, jsondata[i].longitude);

                initializeExitMarker(pos);
            }

        }
    });
}

function initializeExitMarker(pos) {
    var img = 'img/exit.png';
    var marker = new google.maps.Marker({
        position: pos,
        map: map,
        icon: img
    });

    exitMarkers.push(marker);

    //edit here for delete marker
    google.maps.event.addListener(marker, 'dragstart', function() {
        iniLatitude = marker.position.lat();
        iniLongitude = marker.position.lng();
    });
    google.maps.event.addListener(marker, 'dragend', function() {
        updateExitMarker(marker);
    });

    google.maps.event.addListener(marker, 'click', function() {
        var zoomlevel = map.getZoom();
        map.setZoom(zoomlevel + 2);
        map.setCenter(marker.getPosition());
    });

    google.maps.event.addListener(marker, 'rightclick', function() {

        if (toggle === 1 && confirm("Delete marker ?"))
        {


            $.get("deleteMarker.php?latitude=" + marker.position.lat().toFixed(10) + "&longitude=" + marker.position.lng().toFixed(10), function(data, status) {
                if (status === "success")
                {
                    alert(data);
                    marker.setMap(null);
                }
            });
        }
        if (toggle === 0)
        {
            tooltip();
        }
    });
}

window.onload = function() {
    fetchMetroList();

    fetchMetroExitList();

    $.get("fetch_filterList.php", function(data, status) {
        if (status === "success")
        {
            document.getElementById('filter-list').innerHTML = data;
        }
    });

    $(document).ready(function() {

        $('a.toggler').click(function() {
            $(this).toggleClass('off');

            if ($(this).hasClass('off'))
            {
                var len = exitMarkers.length;
                for (var i = 0; i < len; i++)
                {
                    exitMarkers[i].setOptions({draggable: false});
                }

                toggle = 0;
            }
            else
            {
                var len = exitMarkers.length;
                for (var i = 0; i < len; i++)
                {
                    exitMarkers[i].setOptions({draggable: true});
                }

                toggle = 1;
            }

        });


        setInterval(function()
        {
            if (!$('#search').is(":hover") && !$('#metro-data').is(":hover"))
            {
                if (isdropdown === true && !$('#search').is(":focus"))
                {
                    $('#metro-data').slideUp("slow", function() {
                        isdropdown = false;
                    });
                }
            }

            if ($('#search').is(":hover"))
            {
                if (isdropdown === false)
                {
                    $('#metro-data').slideDown("slow", function() {
                        isdropdown = true;
                    });
                }
            }
        }, 2000);

        setInterval(function()
        {
            if (metro_id !== null)
            {
                $.get("metroIdentify.php?metro_id=" + metro_id, function(data, status) {
                    if (status === "success")
                    {
                        document.getElementById('metroIdentify').innerHTML = "METRO: " + data;
                    }
                });
            }
            else
            {
                document.getElementById('metroIdentify').innerHTML = "No Metro Selected";
            }
        }, 100);

        setInterval(function()
        {
            if (map.getZoom() >= 19)
            {
                console.log(map.getCenter().lat(), map.getCenter().lng());
                
            }
        }, 1000);
    });




};

/* adding metro marker */
function createMarker(pos, t, metro_id) {
    var img = 'img/underground.png';
    var marker = new google.maps.Marker({
        position: pos,
        map: map, // google.maps.Map 
        title: metro_id,
        icon: img
    });
    google.maps.event.addListener(marker, 'click', function() {
        var zoomlevel = map.getZoom();
        map.setZoom(zoomlevel + 2);
        map.setCenter(marker.getPosition());

        window.metro_id = marker.title;
        latitude = marker.position.lat();
        longitude = marker.position.lng();

    });
}



/* location load function*/
function locationLoad(item)
{
    var locname = $(item).innerHTML;
    metro_id = $(item).parent().data("values").metro_id;
    latitude = $(item).parent().data("values").latitude;
    longitude = $(item).parent().data("values").longitude;
    var Latlng = new google.maps.LatLng(latitude, longitude);


//    map.panTo(Latlng);
//    map.setZoom(20);
    map.setCenter(Latlng);
    map.setZoom(20);


}







function setfilter(item) {
    filter = item.value;
    fetchMetroList();


    if (filter !== '')
        $.get("https://maps.googleapis.com/maps/api/geocode/json?address=" + filter + "&sensor=false&key=AIzaSyCqPwD-lQNxWqoHMPX9_0UckQlfBVlPVeY"
                , function(data, status) {
                    if (status === "success")
                    {
                        var lat = data.results[0].geometry.location.lat;
                        var lng = data.results[0].geometry.location.lng;
                        var Latlng = new google.maps.LatLng(lat, lng);
                        map.panTo(Latlng);
                        map.setZoom(10);
                    }
                });

    else
    {
        var Latlng = new google.maps.LatLng(20, 20);
        map.panTo(Latlng);
        map.setZoom(2);

        metro_id = null;
        latitude = null;
        longitude = null;
    }
}

/* dropdown options */
var isdropdown = false;
$("#search").hover(
        function() {
            //document.getElementById('metro-data').style.display = 'block';
            if (isdropdown === false)
                $('#metro-data').slideDown("slow", function() {
                    isdropdown = true;
                });
        }, function() {
    if (!$('#metro-data').is(":hover"))
        if (isdropdown === true)
            $('#metro-data').slideUp("slow", function() {
                isdropdown = false;
            });
}
);

$("#metro-data").mouseleave(
        function() {
            if (isdropdown === true)
                $('#metro-data').slideUp("slow", function() {
                    isdropdown = false;
                });
        }
);




function dropdownData(item) {
    var searchitem = item.value;

    $.get("ajaxDropdown.php?searchitem=" + searchitem + "&real_city=" + filter, function(data, status) {
        if (status === "success")
        {
            document.getElementById('metro-data').innerHTML = data;

            $('#metro-data').slideDown("slow", function() {
                isdropdown = true;
            });
        }
    });
}
/* tooltip for editing markers */
function tooltip() {
    $("#toggletooltip").show();
    $("#toggletooltip").delay(3000).fadeOut(500);
}


