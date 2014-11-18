_this=null

class MapInput
    constructor: (@id, @data)->
        window.mapInputs[id] = this
        _this = this

        @el   = document.getElementById(@id)
        @info = document.createElement('div')
        @save = document.createElement('input')

        @position   = new google.maps.LatLng(data.map.lat, data.map.lng)
        @map        = null
        @marker     = null
        @streetview = null

        handleEvent window, 'load', ()->
        _this.init()

    init: ()->
        @initMap()
        @initMarker()
        @initStreetView()

    initMap: ()->
        @map = new google.maps.Map @el,
              center: @position,
              zoom: 18,
              streetViewControl: true
        handleEvent @map, 'zoom_changed', ()->
            _this.updateMapZoom @getZoom()

    initMarker: ()->
        @marker = new google.maps.Marker
            position: @position,
            map: @map,
            animation: google.maps.Animation.DROP,
            draggable: true
        handleEvent @marker, 'dragend', ()->
            _this.updateMapPosition this.getPosition()

    initStreetView: ()->
        @streetview = @map.getStreetView()
        @streetview.setPosition @position
        @streetview.setPov
            heading: parseFloat(_this.data.streetview.heading),
            pitch:   parseFloat(_this.data.streetview.pitch),
            zoom:    parseFloat(_this.data.streetview.zoom)
        handleEvent @streetview, 'pov_changed', ()->
            _this.updatePov @getPov()

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
        console.log(_this.id)
        document.getElementById(_this.id+'map_lat').value  = this.data.map.lat
        document.getElementById(_this.id+'map_lng').value  = this.data.map.lng
        document.getElementById(_this.id+'map_zoom').value = this.data.map.zoom

        document.getElementById(_this.id+'street_heading').value = this.data.streetview.heading
        document.getElementById(_this.id+'street_pitch').value   = this.data.streetview.pitch
        document.getElementById(_this.id+'street_zoom').value    = this.data.streetview.zoom

handleEvent = (object, name, handler)->
  google.maps.event.addDomListener object, name, ()->
    handler.call object