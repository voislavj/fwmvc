_thisMap=null

class MapInput
    constructor: (@id, @data)->
        window.mapInputs[@id] = this
        _thisMap = this

        unless @data.streetview.lat?
            @data.streetview.lat = @data.map.lat
        unless @data.streetview.lng?
            @data.streetview.lng = @data.map.lng

        console.log @data.streetview

        @el   = document.getElementById(@id)
        @info = document.createElement('div')
        @save = document.createElement('input')
        @position   = new google.maps.LatLng(@data.map.lat, @data.map.lng)
        @posStreet  = new google.maps.LatLng(@data.streetview.lat, @data.streetview.lng)
        @map        = null
        @marker     = null
        @streetview = null

        handleEvent window, 'load', ()->
        _thisMap.init()

    init: ()->
        @loadInputs()
        @initMap()
        @initMarker()
        @initStreetView()
        @initInputs()

    loadInputs: ()->
        id = @id.replace /_+$/, ''

        @inptMapLat  = document.getElementById(id+'_map_lat')
        @inptMapLng  = document.getElementById(id+'_map_lng')
        @inptMapZoom = document.getElementById(id+'_map_zoom')

        @inptSvLat     = document.getElementById(id+'_streetview_lat')
        @inptSvLng     = document.getElementById(id+'_streetview_lng')
        @inptSvHeading = document.getElementById(id+'_streetview_heading')
        @inptSvPitch   = document.getElementById(id+'_streetview_pitch')
        @inptSvZoom    = document.getElementById(id+'_streetview_zoom')

    initMap: ()->
        @map = new google.maps.Map @el,
              center: @position,
              zoom: @data.map.zoom,
              streetViewControl: true
        handleEvent @map, 'zoom_changed', ()->
            _thisMap.updateMapZoom @getZoom()

    initMarker: ()->
        @marker = new google.maps.Marker
            position: @position,
            map: @map,
            animation: google.maps.Animation.DROP,
            draggable: true
        handleEvent @marker, 'dragend', ()->
            _thisMap.updateMapPosition this.getPosition()

    initStreetView: ()->
        @streetview = @map.getStreetView()
        @streetview.setPosition @position
        @streetview.setPov
            heading: parseFloat(_thisMap.data.streetview.heading),
            pitch:   parseFloat(_thisMap.data.streetview.pitch),
            zoom:    parseFloat(_thisMap.data.streetview.zoom)
        handleEvent @streetview, 'pov_changed', ()->
            _thisMap.updatePov @getPov()

    initInputs: ()->
        @inptMapLat.onchange = (e)->
            _thisMap.data.map.lat = this.value
            _thisMap.updateMap()
        @inptMapLng.onchange = (e)->
            _thisMap.data.map.lng = this.value
            _thisMap.updateMap()
        @inptMapZoom.onchange = (e)->
            _thisMap.data.map.zoom = this.value
            _thisMap.updateMap()

        @inptSvHeading.onchange = (e)->
            _thisMap.data.streetview.heading = this.value
            _thisMap.updateStreetView()
        @inptSvPitch.onchange = (e)->
            _thisMap.data.streetview.pitch = this.value
            _thisMap.updateStreetView()
        @inptSvZoom.onchange = (e)->
            _thisMap.data.streetview.zoom = this.value
            _thisMap.updateStreetView()

    updateMap: ()->
        pos =
            lat: parseFloat(@data.map.lat),
            lng: parseFloat(@data.map.lng)
        
        @map.setCenter pos
        @map.setZoom parseInt(@data.map.zoom)
        @marker.setPosition pos

    updateStreetView: ()->
        @streetview.setPov
            heading: parseFloat(@data.streetview.heading),
            pitch:   parseFloat(@data.streetview.pitch),
            zoom:    parseFloat(@data.streetview.zoom)

    updateMapPosition: (pos)->
        @data.map.lat = pos.lat()
        @data.map.lng = pos.lng()
        
        @position = pos
        @streetview.setPosition pos

        @updateInputs()

    updateMapZoom: (zoom)->
        @data.map.zoom = zoom
        @updateInputs()

    updatePov: (pov)->
        @data.streetview.heading = pov.heading
        @data.streetview.pitch   = pov.pitch
        @data.streetview.zoom    = pov.zoom
        @updateInputs()

    updateInputs:()->
        @inptMapLat.value  = this.data.map.lat
        @inptMapLng.value  = this.data.map.lng
        @inptMapZoom.value = this.data.map.zoom

        @inptSvHeading.value = this.data.streetview.heading
        @inptSvPitch.value   = this.data.streetview.pitch
        @inptSvZoom.value    = this.data.streetview.zoom

handleEvent = (object, name, handler)->
  google.maps.event.addDomListener object, name, ()->
    handler.call object