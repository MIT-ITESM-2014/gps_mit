var temporal_script = null;
var route;
var map;
  
function initialize() {
  var mapOptions = {
    zoom: 12,
    center: new google.maps.LatLng(-33.50742, -70.58493)
  };
  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  map.set('styles', 
  [
    {
      featureType: 'water',
      stylers: 
      [
        { 
          visibility: 'on' 
        },
        {
          color: '#acbcc9' 
        }
      ]
    },
    {
      featureType: 'landscape',
      stylers: 
      [
        { color: '#FBF7F1' }
      ]
    },
    {
      featureType: 'road.highway',
      elementType: 'geometry',
      stylers: 
      [
        { color: '#B2AFA7' }
      ]
    },
    {
      featureType: 'road.arterial',
      elementType: 'geometry',
      stylers: 
      [
          { color: '#D4D4D4' }
      ]
    },
    {
      featureType: 'road.local',
      elementType: 'geometry',
      stylers: 
      [
          { color: '#D4D4D4'},
          { weight: 0.5 }
      ]
    },
    {
      featureType: 'poi.park',
      elementType: 'geometry',
      stylers: 
      [
          { color: '#c5dac6' }
      ]
    },
    {
      featureType: 'administrative',
      stylers: 
      [
          { visibility: 'on' },
          { lightness: 33 }
      ]
    },
    {
      featureType: 'poi',
      elementType: 'labels',
      stylers: 
      [
          { visibility: 'off' },
          { lightness: 0 }
      ]
    },
    {
      featureType: 'road,highway',
      stylers: 
      [
          { lightness: 20 }
      ]
    },
    {
      featureType: 'road',
      elementType: 'labels',
      stylers: 
      [
        { lightness: -10 },
        { saturation: -100 },
      ]
    }
  ]);
  //Set map legend to #map-legend
  map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(document.getElementById('map-legend'));
                  
  //Load a polyline
  var routeCoordinates = 
    [ 
      new google.maps.LatLng( -33.50742, -70.58493 ),
      new google.maps.LatLng( -33.47018, -70.5412 ),
    ];
  route = new google.maps.Polyline({
    path: routeCoordinates,
    geodesic: true,
    strokeColor: '#49CEAE',
    strokeOpacity: 1.0,
    strokeWeight: 2
  });
  route.setMap(map);
}

//Load script from google maps
function loadScript() {
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' + 'callback=initialize';
  document.body.appendChild(script);
}
      
window.onload = loadScript;

