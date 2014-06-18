function initialize() {
  var mapOptions = {
    zoom: 12,
    //coordinates for the center of Santiago de Chile
    center: new google.maps.LatLng(-33.51627,-70.79147)
  };
  var map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

  //set styles so map is visually different from common google maps
  map.set('styles', [

      {
        featureType: 'road.local',
        elementType: 'geometry',
        stylers: [
          {color: '#96A9C1'},
          { visibility: 'simplified'},
          { weight: 0.9 }
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

 var myLatLng = new google.maps.LatLng(-33.51627,-70.79147)

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

  // Load a GeoJSON from the same server as our demo.
  //map.data.loadGeoJson('http://localhost/gps_mit/front/js/maps/geojson_test.json');

  // Load a polyline by hand
  var routeOneCoordinates = [
    new google.maps.LatLng(-33.51627,-70.79147),
    new google.maps.LatLng( -33.51627, -70.79147 ),
    new google.maps.LatLng( -33.51627, -70.79147 ),
    new google.maps.LatLng( -33.51627, -70.79147 ),
    new google.maps.LatLng( -33.51627, -70.79147 ),
    new google.maps.LatLng( -33.51627, -70.79147 ),
    new google.maps.LatLng( -33.51622, -70.79147 ),
    new google.maps.LatLng( -33.51622, -70.79137 ),
    new google.maps.LatLng( -33.51622, -70.79137 ),
    new google.maps.LatLng( -33.51623, -70.79137 ),
    new google.maps.LatLng( -33.51623, -70.79137 ),
    new google.maps.LatLng( -33.51623, -70.79137 ),
    new google.maps.LatLng( -33.51624, -70.79137 ),
    new google.maps.LatLng( -33.51624, -70.79137 ),
    new google.maps.LatLng( -33.51624, -70.79137 ),
    new google.maps.LatLng( -33.51625, -70.79137 ),
    new google.maps.LatLng( -33.51625, -70.79138 ),
    new google.maps.LatLng( -33.51625, -70.79138 ),
    new google.maps.LatLng( -33.51623, -70.79138 ),
    new google.maps.LatLng( -33.51623, -70.79141 ),
    new google.maps.LatLng( -33.51623, -70.79141 ),
    new google.maps.LatLng( -33.51624, -70.79141 ),
    new google.maps.LatLng( -33.51624, -70.79141 ),
    new google.maps.LatLng( -33.51624, -70.79141 ),
    new google.maps.LatLng( -33.51626, -70.79141 ),
    new google.maps.LatLng( -33.51626, -70.7914 ),
    new google.maps.LatLng( -33.51626, -70.7914 ),
    new google.maps.LatLng( -33.51627, -70.7914 ),
    new google.maps.LatLng( -33.51627, -70.7914 ),
    new google.maps.LatLng( -33.51627, -70.7914 ),
    new google.maps.LatLng( -33.51676, -70.7914 ),
    new google.maps.LatLng( -33.51676, -70.7913 ),
    new google.maps.LatLng( -33.51676, -70.7913 ),
    new google.maps.LatLng( -33.51672, -70.7913 ),
    new google.maps.LatLng( -33.51672, -70.7913 ),
    new google.maps.LatLng( -33.51672, -70.7913 ),
    new google.maps.LatLng( -33.51672, -70.7913 ),
    new google.maps.LatLng( -33.51672, -70.7913 ),
    new google.maps.LatLng( -33.51672, -70.7913 ),
    new google.maps.LatLng( -33.51671, -70.7913 ),
    new google.maps.LatLng( -33.51671, -70.79129 ),
    new google.maps.LatLng( -33.51671, -70.79129 ),
    new google.maps.LatLng( -33.51634, -70.79129 ),
    new google.maps.LatLng( -33.51634, -70.79137 ),
    new google.maps.LatLng( -33.51634, -70.79137 ),
    new google.maps.LatLng( -33.51636, -70.79137 ),
    new google.maps.LatLng( -33.51636, -70.79137 ),
    new google.maps.LatLng( -33.51636, -70.79137 ),
    new google.maps.LatLng( -33.5164, -70.79137 ),
    new google.maps.LatLng( -33.5164, -70.79135 ),
    new google.maps.LatLng( -33.5164, -70.79135 ),
    new google.maps.LatLng( -33.5164, -70.79135 ),
    new google.maps.LatLng( -33.5164, -70.79135 ),
    new google.maps.LatLng( -33.5164, -70.79135 ),
    new google.maps.LatLng( -33.51835, -70.79135 ),
    new google.maps.LatLng( -33.51835, -70.79067 ),
    new google.maps.LatLng( -33.51835, -70.79067 ),
    new google.maps.LatLng( -33.51771, -70.79067 ),
    new google.maps.LatLng( -33.51771, -70.7909 ),
    new google.maps.LatLng( -33.51771, -70.7909 ),
    new google.maps.LatLng( -33.51733, -70.7909 ),
    new google.maps.LatLng( -33.51733, -70.79099 ),
    new google.maps.LatLng( -33.51733, -70.79099 ),
    new google.maps.LatLng( -33.51724, -70.79099 ),
    new google.maps.LatLng( -33.51724, -70.79101 ),
    new google.maps.LatLng( -33.51724, -70.79101 ),
    new google.maps.LatLng( -33.51626, -70.79101 ),
    new google.maps.LatLng( -33.51626, -70.79141 ),
    new google.maps.LatLng( -33.51626, -70.79141 ),
    new google.maps.LatLng( -33.51626, -70.79141 ),
    new google.maps.LatLng( -33.51626, -70.79141 ),
    new google.maps.LatLng( -33.51626, -70.79141 ),
    new google.maps.LatLng( -33.51633, -70.79141 ),
    new google.maps.LatLng( -33.51633, -70.7914 ),
    new google.maps.LatLng( -33.51633, -70.7914 ),
    new google.maps.LatLng( -33.51634, -70.7914 ),
    new google.maps.LatLng( -33.51634, -70.79139 ),
    new google.maps.LatLng( -33.51634, -70.79139 ),
    new google.maps.LatLng( -33.51681, -70.79139 ),
    new google.maps.LatLng( -33.51681, -70.79113 ),
    new google.maps.LatLng( -33.51681, -70.79113 ),
    new google.maps.LatLng( -33.51668, -70.79113 ),
    new google.maps.LatLng( -33.51668, -70.79118 ),
    new google.maps.LatLng( -33.51668, -70.79118 ),
    new google.maps.LatLng( -33.51665, -70.79118 ),
    new google.maps.LatLng( -33.51665, -70.79121 ),
    new google.maps.LatLng( -33.51665, -70.79121 ),
    new google.maps.LatLng( -33.51661, -70.79121 ),
    new google.maps.LatLng( -33.51661, -70.79123 ),
    new google.maps.LatLng( -33.51661, -70.79123 ),
    new google.maps.LatLng( -33.51671, -70.79123 ),
    new google.maps.LatLng( -33.51671, -70.79119 ),
    new google.maps.LatLng( -33.51671, -70.79119 ),
    new google.maps.LatLng( -33.51668, -70.79119 ),
    new google.maps.LatLng( -33.51668, -70.7912 ),
    new google.maps.LatLng( -33.51668, -70.7912 ),
    new google.maps.LatLng( -33.51666, -70.7912 ),
    new google.maps.LatLng( -33.51666, -70.79122 ),
    new google.maps.LatLng( -33.51666, -70.79122 ),
    new google.maps.LatLng( -33.51663, -70.79122 ),
    new google.maps.LatLng( -33.51663, -70.79123 ),
    new google.maps.LatLng( -33.51663, -70.79123 ),
    new google.maps.LatLng( -33.51634, -70.79123 ),
    new google.maps.LatLng( -33.51634, -70.79136 ),
    new google.maps.LatLng( -33.51634, -70.79136 ),
    new google.maps.LatLng( -33.51635, -70.79136 ),
    new google.maps.LatLng( -33.51635, -70.79136 ),
    new google.maps.LatLng( -33.51635, -70.79136 ),
    new google.maps.LatLng( -33.51736, -70.79136 ),
    new google.maps.LatLng( -33.51736, -70.79142 ),
    new google.maps.LatLng( -33.51736, -70.79142 ),
    new google.maps.LatLng( -33.51901, -70.79142 ),
    new google.maps.LatLng( -33.51901, -70.79139 ),
    new google.maps.LatLng( -33.51901, -70.79139 ),
    new google.maps.LatLng( -33.52229, -70.79139 ),
    new google.maps.LatLng( -33.52229, -70.79314 ),
    new google.maps.LatLng( -33.52229, -70.79314 ),
    new google.maps.LatLng( -33.51969, -70.79314 ),
    new google.maps.LatLng( -33.51969, -70.79444 ),
    new google.maps.LatLng( -33.51969, -70.79444 ),
    new google.maps.LatLng( -33.51921, -70.79444 ),
    new google.maps.LatLng( -33.51921, -70.79131 ),
    new google.maps.LatLng( -33.51921, -70.79131 ),
    new google.maps.LatLng( -33.51891, -70.79131 ),
    new google.maps.LatLng( -33.51891, -70.78917 ),
    new google.maps.LatLng( -33.51891, -70.78917 ),
    new google.maps.LatLng( -33.51836, -70.78917 ),
    new google.maps.LatLng( -33.51836, -70.78186 ),
    new google.maps.LatLng( -33.51836, -70.78186 ),
    new google.maps.LatLng( -33.51835, -70.78186 ),
    new google.maps.LatLng( -33.51835, -70.77911 ),
    new google.maps.LatLng( -33.51835, -70.77911 ),
    new google.maps.LatLng( -33.51805, -70.77911 ),
    new google.maps.LatLng( -33.51805, -70.77215 ),
    new google.maps.LatLng( -33.51805, -70.77215 ),
    new google.maps.LatLng( -33.51785, -70.77215 ),
    new google.maps.LatLng( -33.51785, -70.76688 ),
    new google.maps.LatLng( -33.51785, -70.76688 ),
    new google.maps.LatLng( -33.5178, -70.76688 ),
    new google.maps.LatLng( -33.5178, -70.76463 ),
    new google.maps.LatLng( -33.5178, -70.76463 ),
    new google.maps.LatLng( -33.5176, -70.76463 ),
    new google.maps.LatLng( -33.5176, -70.76348 ),
    new google.maps.LatLng( -33.5176, -70.76348 ),
    new google.maps.LatLng( -33.5167, -70.76348 ),
    new google.maps.LatLng( -33.5167, -70.75887 ),
    new google.maps.LatLng( -33.5167, -70.75887 ),
    new google.maps.LatLng( -33.51658, -70.75887 ),
    new google.maps.LatLng( -33.51658, -70.75851 ),
    new google.maps.LatLng( -33.51658, -70.75851 ),
    new google.maps.LatLng( -33.51642, -70.75851 ),
    new google.maps.LatLng( -33.51642, -70.75613 ),
    new google.maps.LatLng( -33.51642, -70.75613 ),
    new google.maps.LatLng( -33.51597, -70.75613 ),
    new google.maps.LatLng( -33.51597, -70.75138 ),
    new google.maps.LatLng( -33.51597, -70.75138 ),
    new google.maps.LatLng( -33.51616, -70.75138 ),
    new google.maps.LatLng( -33.51616, -70.74695 ),
    new google.maps.LatLng( -33.51616, -70.74695 ),
    new google.maps.LatLng( -33.51417, -70.74695 ),
    new google.maps.LatLng( -33.51417, -70.7422 ),
    new google.maps.LatLng( -33.51417, -70.7422 ),
    new google.maps.LatLng( -33.51024, -70.7422 ),
    new google.maps.LatLng( -33.51024, -70.73958 ),
    new google.maps.LatLng( -33.51024, -70.73958 ),
    new google.maps.LatLng( -33.51038, -70.73958 ),
    new google.maps.LatLng( -33.51038, -70.73778 ),
    new google.maps.LatLng( -33.51038, -70.73778 ),
    new google.maps.LatLng( -33.51103, -70.73778 ),
    new google.maps.LatLng( -33.51103, -70.73411 ),
    new google.maps.LatLng( -33.51103, -70.73411 ),
    new google.maps.LatLng( -33.51001, -70.73411 ),
    new google.maps.LatLng( -33.51001, -70.7343 )
  ];

  // Load a polyline by hand
  var routeTwoCoordinates = [
    new google.maps.LatLng( -33.53177, -70.76241 ),
    new google.maps.LatLng( -33.53177, -70.76242 ),
    new google.maps.LatLng( -33.53177, -70.76242 ),
    new google.maps.LatLng( -33.53177, -70.76241 ),
    new google.maps.LatLng( -33.53182, -70.76242 ),
    new google.maps.LatLng( -33.53182, -70.76226 ),
    new google.maps.LatLng( -33.53182, -70.76226 ),
    new google.maps.LatLng( -33.53177, -70.76226 ),
    new google.maps.LatLng( -33.53177, -70.76237 ),
    new google.maps.LatLng( -33.53177, -70.76237 ),
    new google.maps.LatLng( -33.53199, -70.76237 ),
    new google.maps.LatLng( -33.53199, -70.76206 ),
    new google.maps.LatLng( -33.53199, -70.76206 ),
    new google.maps.LatLng( -33.53256, -70.76206 ),
    new google.maps.LatLng( -33.53256, -70.76269 ),
    new google.maps.LatLng( -33.53256, -70.76269 ),
    new google.maps.LatLng( -33.53346, -70.76269 ),
    new google.maps.LatLng( -33.53346, -70.76174 ),
    new google.maps.LatLng( -33.53346, -70.76174 ),
    new google.maps.LatLng( -33.53348, -70.76174 ),
    new google.maps.LatLng( -33.53348, -70.76175 ),
    new google.maps.LatLng( -33.53348, -70.76175 ),
    new google.maps.LatLng( -33.53348, -70.76175 ),
    new google.maps.LatLng( -33.53348, -70.76175 ),
    new google.maps.LatLng( -33.53348, -70.76175 ),
    new google.maps.LatLng( -33.53352, -70.76175 ),
    new google.maps.LatLng( -33.53352, -70.7617 ),
    new google.maps.LatLng( -33.53352, -70.7617 ),
    new google.maps.LatLng( -33.53323, -70.7617 ),
    new google.maps.LatLng( -33.53323, -70.76159 ),
    new google.maps.LatLng( -33.53323, -70.76159 ),
    new google.maps.LatLng( -33.53323, -70.76159 ),
    new google.maps.LatLng( -33.53323, -70.76159 ),
    new google.maps.LatLng( -33.53323, -70.76159 ),
    new google.maps.LatLng( -33.5331, -70.76159 ),
    new google.maps.LatLng( -33.5331, -70.76165 ),
    new google.maps.LatLng( -33.5331, -70.76165 ),
    new google.maps.LatLng( -33.5331, -70.76165 ),
    new google.maps.LatLng( -33.5331, -70.76164 ),
    new google.maps.LatLng( -33.5331, -70.76164 ),
    new google.maps.LatLng( -33.53332, -70.76164 ),
    new google.maps.LatLng( -33.53332, -70.76175 ),
    new google.maps.LatLng( -33.53332, -70.76175 ),
    new google.maps.LatLng( -33.533, -70.76175 ),
    new google.maps.LatLng( -33.533, -70.76219 ),
    new google.maps.LatLng( -33.533, -70.76219 ),
    new google.maps.LatLng( -33.53302, -70.76219 ),
    new google.maps.LatLng( -33.53302, -70.76278 ),
    new google.maps.LatLng( -33.53302, -70.76278 ),
    new google.maps.LatLng( -33.53303, -70.76278 ),
    new google.maps.LatLng( -33.53303, -70.76263 ),
    new google.maps.LatLng( -33.53303, -70.76263 ),
    new google.maps.LatLng( -33.53304, -70.76263 ),
    new google.maps.LatLng( -33.53304, -70.76259 ),
    new google.maps.LatLng( -33.53304, -70.76259 ),
    new google.maps.LatLng( -33.533, -70.76259 ),
    new google.maps.LatLng( -33.533, -70.76245 ),
    new google.maps.LatLng( -33.533, -70.76245 ),
    new google.maps.LatLng( -33.533, -70.76245 ),
    new google.maps.LatLng( -33.533, -70.76243 ),
    new google.maps.LatLng( -33.533, -70.76243 ),
    new google.maps.LatLng( -33.53337, -70.76243 ),
    new google.maps.LatLng( -33.53337, -70.76193 ),
    new google.maps.LatLng( -33.53337, -70.76193 ),
    new google.maps.LatLng( -33.53228, -70.76193 ),
    new google.maps.LatLng( -33.53228, -70.76183 ),
    new google.maps.LatLng( -33.53228, -70.76183 ),
    new google.maps.LatLng( -33.53215, -70.76183 ),
    new google.maps.LatLng( -33.53215, -70.76196 ),
    new google.maps.LatLng( -33.53215, -70.76196 ),
    new google.maps.LatLng( -33.53215, -70.76196 ),
    new google.maps.LatLng( -33.53215, -70.76196 ),
    new google.maps.LatLng( -33.53215, -70.76196 ),
    new google.maps.LatLng( -33.53114, -70.76196 ),
    new google.maps.LatLng( -33.53114, -70.76239 ),
    new google.maps.LatLng( -33.53114, -70.76239 ),
    new google.maps.LatLng( -33.52728, -70.76239 ),
    new google.maps.LatLng( -33.52728, -70.7564 ),
    new google.maps.LatLng( -33.52728, -70.7564 ),
    new google.maps.LatLng( -33.5229, -70.7564 ),
    new google.maps.LatLng( -33.5229, -70.74829 ),
    new google.maps.LatLng( -33.5229, -70.74829 ),
    new google.maps.LatLng( -33.51906, -70.74829 ),
    new google.maps.LatLng( -33.51906, -70.74126 ),
    new google.maps.LatLng( -33.51906, -70.74126 ),
    new google.maps.LatLng( -33.51647, -70.74126 ),
    new google.maps.LatLng( -33.51647, -70.73636 ),
    new google.maps.LatLng( -33.51647, -70.73636 ),
    new google.maps.LatLng( -33.51226, -70.73636 ),
    new google.maps.LatLng( -33.51226, -70.72783 ),
    new google.maps.LatLng( -33.51226, -70.72783 ),
    new google.maps.LatLng( -33.5107, -70.72783 ),
    new google.maps.LatLng( -33.5107, -70.72431 ),
    new google.maps.LatLng( -33.5107, -70.72431 ),
    new google.maps.LatLng( -33.51273, -70.72431 ),
    new google.maps.LatLng( -33.51273, -70.72024 ),
    new google.maps.LatLng( -33.51273, -70.72024 ),
    new google.maps.LatLng( -33.51569, -70.72024 ),
    new google.maps.LatLng( -33.51569, -70.71509 ),
    new google.maps.LatLng( -33.51569, -70.71509 ),
    new google.maps.LatLng( -33.51627, -70.71509 ),
    new google.maps.LatLng( -33.51627, -70.7141 ),
    new google.maps.LatLng( -33.51627, -70.7141 ),
    new google.maps.LatLng( -33.51682, -70.7141 ),
    new google.maps.LatLng( -33.51682, -70.71273 ),
    new google.maps.LatLng( -33.51682, -70.71273 ),
    new google.maps.LatLng( -33.51732, -70.71273 ),
    new google.maps.LatLng( -33.51732, -70.71151 ),
    new google.maps.LatLng( -33.51732, -70.71151 ),
    new google.maps.LatLng( -33.52021, -70.71151 ),
    new google.maps.LatLng( -33.52021, -70.70376 ),
    new google.maps.LatLng( -33.52021, -70.70376 ),
    new google.maps.LatLng( -33.52731, -70.70376 ),
    new google.maps.LatLng( -33.52731, -70.69167 ),
    new google.maps.LatLng( -33.52731, -70.69167 ),
    new google.maps.LatLng( -33.53273, -70.69167 ),
    new google.maps.LatLng( -33.53273, -70.67955 ),
    new google.maps.LatLng( -33.53273, -70.67955 ),
    new google.maps.LatLng( -33.53653, -70.67955 ),
    new google.maps.LatLng( -33.53653, -70.66724 ),
    new google.maps.LatLng( -33.53653, -70.66724 ),
    new google.maps.LatLng( -33.54003, -70.66724 ),
    new google.maps.LatLng( -33.54003, -70.65479 ),
    new google.maps.LatLng( -33.54003, -70.65479 ),
    new google.maps.LatLng( -33.54165, -70.65479 ),
    new google.maps.LatLng( -33.54165, -70.64214 ),
    new google.maps.LatLng( -33.54165, -70.64214 ),
    new google.maps.LatLng( -33.54316, -70.64214 ),
    new google.maps.LatLng( -33.54316, -70.62927 ),
    new google.maps.LatLng( -33.54316, -70.62927 ),
    new google.maps.LatLng( -33.54113, -70.62927 ),
    new google.maps.LatLng( -33.54113, -70.61443 ),
    new google.maps.LatLng( -33.54113, -70.61443 ),
    new google.maps.LatLng( -33.53156, -70.61443 ),
    new google.maps.LatLng( -33.53156, -70.60557 ),
    new google.maps.LatLng( -33.53156, -70.60557 ),
    new google.maps.LatLng( -33.52278, -70.60557 ),
    new google.maps.LatLng( -33.52278, -70.59821 ),
    new google.maps.LatLng( -33.52278, -70.59821 ),
    new google.maps.LatLng( -33.51222, -70.59821 ),
    new google.maps.LatLng( -33.51222, -70.59012 ),
    new google.maps.LatLng( -33.51222, -70.59012 ),
    new google.maps.LatLng( -33.50958, -70.59012 ),
    new google.maps.LatLng( -33.50958, -70.59237 ),
    new google.maps.LatLng( -33.50958, -70.59237 ),
    new google.maps.LatLng( -33.50883, -70.59237 ),
    new google.maps.LatLng( -33.50883, -70.60309 )
  ];

  var routeOne = new google.maps.Polyline({
    path: routeOneCoordinates,
    geodesic: true,
    strokeColor: '#FF0000',
    strokeOpacity: 1.0,
    strokeWeight: 2
  });

  routeOne.setMap(map);

var routeTwo = new google.maps.Polyline({
    path: routeTwoCoordinates,
    geodesic: true,
    strokeColor: '#C030FF',
    strokeOpacity: 1.0,
    strokeWeight: 2
  });

  routeTwo.setMap(map);
}

function loadScript() {
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
      'callback=initialize';
  document.body.appendChild(script);
}

window.onload = loadScript;