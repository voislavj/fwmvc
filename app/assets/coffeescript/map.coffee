initMap = ()->
    if !MAP_DATA? or !STREET_DATA?
        return console.error 'Map config invalid.'

    el  = $('#map .map')[0];console.log(el);
    pos = new google.maps.LatLng MAP_DATA.lat, MAP_DATA.lng

    map = new google.maps.Map el,
        center: pos,
        zoom: parseFloat(MAP_DATA.zoom)

    marker = new google.maps.Marker
        position: pos,
        map: map,
        title: SETTINGS_NAME,
        icon: '/img/map_marker.png',
        animation: google.maps.Animation.DROP

    street = map.getStreetView()
    pov =
        heading: parseFloat(STREET_DATA.heading),
        pitch:   parseFloat(STREET_DATA.pitch),
        zoom:    parseFloat(STREET_DATA.zoom)
    street.setPosition pos
    street.setPov pov

    prevVisible = false;
    google.maps.event.addDomListener street, 'visible_changed', ()->
        if @getVisible() != prevVisible
            @setPosition pos
            @setPov pov
        prevVisible = this.getVisible()