window.mapInputs = window.mapInputs || {};
window.google = window.google || {};
google.maps = google.maps || {};
(function() {
  
  function getScript(src) {
    document.write('<' + 'script src="' + src + '"' +
                   ' type="text/javascript"><' + '/script>');
  }
  
  var modules = google.maps.modules = {};
  google.maps.__gjsload__ = function(name, text) {
    modules[name] = text;
  };
  
  google.maps.Load = function(apiLoad) {
    delete google.maps.Load;
    apiLoad([0.009999999776482582,[[["https://mts0.googleapis.com/vt?lyrs=m@278000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.googleapis.com/vt?lyrs=m@278000000\u0026src=api\u0026hl=en-US\u0026"],null,null,null,null,"m@278000000",["https://mts0.google.com/vt?lyrs=m@278000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.google.com/vt?lyrs=m@278000000\u0026src=api\u0026hl=en-US\u0026"]],[["https://khms0.googleapis.com/kh?v=159\u0026hl=en-US\u0026","https://khms1.googleapis.com/kh?v=159\u0026hl=en-US\u0026"],null,null,null,1,"159",["https://khms0.google.com/kh?v=159\u0026hl=en-US\u0026","https://khms1.google.com/kh?v=159\u0026hl=en-US\u0026"]],[["https://mts0.googleapis.com/vt?lyrs=h@278000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.googleapis.com/vt?lyrs=h@278000000\u0026src=api\u0026hl=en-US\u0026"],null,null,null,null,"h@278000000",["https://mts0.google.com/vt?lyrs=h@278000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.google.com/vt?lyrs=h@278000000\u0026src=api\u0026hl=en-US\u0026"]],[["https://mts0.googleapis.com/vt?lyrs=t@132,r@278000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.googleapis.com/vt?lyrs=t@132,r@278000000\u0026src=api\u0026hl=en-US\u0026"],null,null,null,null,"t@132,r@278000000",["https://mts0.google.com/vt?lyrs=t@132,r@278000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.google.com/vt?lyrs=t@132,r@278000000\u0026src=api\u0026hl=en-US\u0026"]],null,null,[["https://cbks0.googleapis.com/cbk?","https://cbks1.googleapis.com/cbk?"]],[["https://khms0.googleapis.com/kh?v=84\u0026hl=en-US\u0026","https://khms1.googleapis.com/kh?v=84\u0026hl=en-US\u0026"],null,null,null,null,"84",["https://khms0.google.com/kh?v=84\u0026hl=en-US\u0026","https://khms1.google.com/kh?v=84\u0026hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt?hl=en-US\u0026","https://mts1.googleapis.com/mapslt?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt/ft?hl=en-US\u0026","https://mts1.googleapis.com/mapslt/ft?hl=en-US\u0026"]],[["https://mts0.googleapis.com/vt?hl=en-US\u0026","https://mts1.googleapis.com/vt?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt/loom?hl=en-US\u0026","https://mts1.googleapis.com/mapslt/loom?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt?hl=en-US\u0026","https://mts1.googleapis.com/mapslt?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt/ft?hl=en-US\u0026","https://mts1.googleapis.com/mapslt/ft?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt/loom?hl=en-US\u0026","https://mts1.googleapis.com/mapslt/loom?hl=en-US\u0026"]]],["en-US","US",null,0,null,null,"https://maps.gstatic.com/mapfiles/","https://csi.gstatic.com","https://maps.googleapis.com","https://maps.googleapis.com",null,"https://maps.google.com"],["https://maps.gstatic.com/maps-api-v3/api/js/18/9","3.18.9"],[2877274378],1,null,null,null,null,null,"",null,null,1,"https://khms.googleapis.com/mz?v=159\u0026",null,"https://earthbuilder.googleapis.com","https://earthbuilder.googleapis.com",null,"https://mts.googleapis.com/vt/icon",[["https://mts0.googleapis.com/vt","https://mts1.googleapis.com/vt"],["https://mts0.googleapis.com/vt","https://mts1.googleapis.com/vt"],[null,[[0,"m",278000000]],[null,"en-US","US",null,18,null,null,null,null,null,null,[[47],[37,[["smartmaps"]]]]],0],[null,[[0,"m",278000000]],[null,"en-US","US",null,18,null,null,null,null,null,null,[[47],[37,[["smartmaps"]]]]],3],[null,[[0,"m",278000000]],[null,"en-US","US",null,18,null,null,null,null,null,null,[[50],[37,[["smartmaps"]]]]],0],[null,[[0,"m",278000000]],[null,"en-US","US",null,18,null,null,null,null,null,null,[[50],[37,[["smartmaps"]]]]],3],[null,[[4,"t",132],[0,"r",132000000]],[null,"en-US","US",null,18,null,null,null,null,null,null,[[63],[37,[["smartmaps"]]]]],0],[null,[[4,"t",132],[0,"r",132000000]],[null,"en-US","US",null,18,null,null,null,null,null,null,[[63],[37,[["smartmaps"]]]]],3],[null,null,[null,"en-US","US",null,18],0],[null,null,[null,"en-US","US",null,18],3],[null,null,[null,"en-US","US",null,18],6],[null,null,[null,"en-US","US",null,18],0],["https://mts0.google.com/vt","https://mts1.google.com/vt"],"/maps/vt",278000000,132],2,500,["https://geo0.ggpht.com/cbk","https://g0.gstatic.com/landmark/tour","https://g0.gstatic.com/landmark/config","","https://www.google.com/maps/preview/log204","","https://static.panoramio.com.storage.googleapis.com/photos/"],["https://www.google.com/maps/api/js/master?pb=!1m2!1u18!2s9!2sen-US!3sUS!4s18/9","https://www.google.com/maps/api/js/widget?pb=!1m2!1u18!2s9!2sen-US"],1,0], loadScriptTime);
  };
  var loadScriptTime = (new Date).getTime();
  getScript("https://maps.gstatic.com/maps-api-v3/api/js/18/9/main.js");
})();

function MapInput(id, data) {
  window.mapInputs[id] = this;
  var _this = this;
  
  this.id   = id
  this.data = data;
  this.el   = document.getElementById(this.id);
  this.info = document.createElement('div');
  this.save = document.createElement('input');

  this.position = new google.maps.LatLng(data.map.lat, data.map.lng);
  this.map = null;
  this.marker = null;
  this.streetview = null;

  this.init = function() {

    // Info Window
    this.info.id = this.id+'_info_window';
    this.info.style.display = 'inline-block';
    this.info.style.marginLeft = '5px';
    this.info.innerHTML = '<p><b>mapa:</b><br>'+
                          '&nbsp;&nbsp;lat: <span id="'+this.id+'_map_info_lat">'+this.data.map.lat+'</span><br>'+
                          '&nbsp;&nbsp;lng: <span id="'+this.id+'_map_info_lng">'+this.data.map.lng+'</span><br>'+
                          '&nbsp;&nbsp;zoom: <span id="'+this.id+'_map_info_zoom">'+this.data.map.zoom+'</span></p>'+
                          '<p><b>street-view:</b><br>'+
                          '&nbsp;&nbsp;heading: <span id="'+this.id+'_street_info_heading">'+this.data.streetview.heading+'</span><br>'+
                          '&nbsp;&nbsp;pitch: <span id="'+this.id+'_street_info_pitch">'+this.data.streetview.pitch+'</span><br>'+
                          '&nbsp;&nbsp;zoom: <span id="'+this.id+'_street_info_zoom">'+this.data.streetview.zoom+'</span></p>';
    this.el.parentNode.insertBefore(this.info, this.el.nextSibling);

    // Save button
    this.save.type  = 'button';
    this.save.value = 'Sačuvaj parametre mape';
    this.save.style.display = 'none';
    this.save.onclick = function() {
      document.getElementById(_this.id+'map_lat').value = _this.data.map.lat;
      document.getElementById(_this.id+'map_lng').value = _this.data.map.lng;
      document.getElementById(_this.id+'map_zoom').value = _this.data.map.zoom;

      document.getElementById(_this.id+'streetview_heading').value = _this.data.streetview.heading;
      document.getElementById(_this.id+'streetview_pitch').value = _this.data.streetview.pitch;
      document.getElementById(_this.id+'streetview_zoom').value = _this.data.streetview.zoom;
      
      _this.showSave(false);
    };
    this.info.appendChild(this.save);
    
    // Map
    this.map = new google.maps.Map(this.el, {
      center: this.position,
      zoom: 18,
      streetViewControl: true
    });
    handleEvent(this.map, 'zoom_changed', function(){
      _this.updateMapZoom(this.getZoom());
    });

    // Marker
    this.marker = new google.maps.Marker({
      position: this.position,
      map: this.map,
      animation: google.maps.Animation.DROP,
      draggable: true
    });
    handleEvent(this.marker, 'dragend', function(){
      _this.updateMapPosition(this.getPosition())
    });

    // Street-View
    this.streetview = this.map.getStreetView();
    this.streetview.setPosition(this.position);
    this.streetview.setPov({
      heading: parseFloat(_this.data.streetview.heading),
      pitch:   parseFloat(_this.data.streetview.pitch),
      zoom:    parseFloat(_this.data.streetview.zoom)
    });
    handleEvent(this.streetview, 'pov_changed', function(){
      _this.updatePov(this.getPov());
    })
  };

  this.updateMapPosition = function(pos) {
    this.data.map.lat = pos.lat()
    this.data.map.lng = pos.lng();
    
    this.position = pos;
    this.streetview.setPosition(pos);

    this.updateInfoWindow();
    _this.showSave(true);
  };
  this.updateMapZoom = function(zoom) {
    this.data.map.zoom = zoom;
    this.updateInfoWindow();
    _this.showSave(true);
  };
  this.updatePov = function(pov) {
    this.data.streetview.heading = pov.heading;
    this.data.streetview.pitch = pov.pitch;
    this.data.streetview.zoom = pov.zoom;
    this.updateInfoWindow();
    _this.showSave(true);
  };

  this.updateInfoWindow = function() {
    document.getElementById(_this.id+'_map_info_lat').innerHTML = this.data.map.lat;
    document.getElementById(_this.id+'_map_info_lng').innerHTML = this.data.map.lng;
    document.getElementById(_this.id+'_map_info_zoom').innerHTML = this.data.map.zoom;

    document.getElementById(_this.id+'_street_info_heading').innerHTML = this.data.streetview.heading;
    document.getElementById(_this.id+'_street_info_pitch').innerHTML = this.data.streetview.pitch;
    document.getElementById(_this.id+'_street_info_zoom').innerHTML = this.data.streetview.zoom;
  };

  this.showSave = function(show) {
    show = !!show;
    this.save.style.display = show ? 'block' : 'none';
  }

  handleEvent(window, 'load', function(){
    _this.init();
  });
}

function handleEvent(object, name, handler) {
  google.maps.event.addDomListener(object, name, function(){
    handler.call(object);
  });
}