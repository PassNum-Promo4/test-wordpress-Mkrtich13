if (document.getElementsByClassName('wpview_map_view').length || document.getElementsByClassName('wpview_map_field').length) {
    var map = new Array();
    for (var index in wpview_map_coord) {
        for (var i = 0; i < wpview_map_coord[index].length; i++) {
            var cLat = parseFloat(wpview_map_coord[index][i].lat);
            var cLng = parseFloat(wpview_map_coord[index][i].lng);
            var cZoom = parseFloat(wpview_map_coord[index][i].zoom);
            var coord = {lat: cLat, lng: cLng};
            map[i] = new google.maps.Map(document.getElementById('wpview_map-' + wpview_map_coord[index][i].field_id), {
                zoom: cZoom,
                center: new google.maps.LatLng(cLat, cLng),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                fullscreenControl: true
            });
            var marker = new google.maps.Marker({map: map[i], position: coord});
            marker.addListener('click', function () {
                infowindow.open(map[i], marker);
            });
        }
    }
}