function initialize() {
  var mapOptions = {
    zoom: 15,
    //coordinates for the center of Santiago de Chile
    center: new google.maps.LatLng(-33.4520839,-70.6585019)
  };

  var map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

  //set styles so map is visually different from common google maps
  map.set('styles', [

      {
        featureType: 'road.local',
        elementType: 'geometry',
        stylers: [
          {color: '#64A0AB'},
          { visibility: 'simplified'},
          { weight: 1.0 }
        ]
      },
      {
        featureType: 'road.highway',
        elementType: 'geometry',
        stylers: [
          {color: '#F7DC9E'},
          { visibility: 'simplified'},
          { weight: 5.5 }
        ]
      },
      {
        featureType: 'road',
        elementType: 'labels',
        stylers: [
          { visibility: 'on' },
          { saturation: 600 }
        ]
      },  
      {
        featureType: 'landscape',
        elementType: 'geometry',
        stylers: [
          { hue: '#ffff00' },
          { gamma: 0.5 },
          { saturation: 82 },
          { lightness: 96 }
          ]
      },
      {
        featureType: 'poi.government',
         elementType: 'geometry',
          stylers: [
            { visibility: 'on' },
            { hue: '#9AB896' },
            { lightness: -15 },
            { saturation: 99 }
          ]
      }
    ]);


//exampĺe for adding personalized icons to a map
 var iconBase = 'http://www.miamidade.gov/transit/mobile/images/';

 var myLatLng = new google.maps.LatLng(-33.452083,-70.65850194)

  var marker = new google.maps.Marker({
    position: myLatLng,
    map: map,
    icon: iconBase + 'icon-Bus-Stop.png'
  });

  map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(
  document.getElementById('map-legend'));


  //create a legend that describes these icons
  //you can add style to this legend separately
  var legend = document.getElementById('map-legend');

  var name = 'Prueba';
  var icon = iconBase + 'icon-Bus-Stop.png';
  var div = document.createElement('div');
  div.innerHTML = '<img src="' + icon + '"> ' + name;
  legend.appendChild(div);
}

function loadScript() {
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
      'callback=initialize';
  document.body.appendChild(script);
}

window.onload = loadScript;