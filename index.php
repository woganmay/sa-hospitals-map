<?php
 /* Google Maps */
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <script src="jquery-1.11.3.min.js" type="text/javascript"></script>
    <title>C4SA - Map of Hospitals</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>
      
    var populationOverlay;
    var map;

    function initMap() {
    
      map = new google.maps.Map(document.getElementById('map'), {
        zoom: 6,
        center: {lat: -28.5646222, lng: 24.217508}
      });
      
      var imageBounds = new google.maps.LatLngBounds(
      new google.maps.LatLng(-34.931866, 16.116683),
      new google.maps.LatLng(-22.122521, 33.16));
      
      var red = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
      var amber = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
      var green = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
      
      // Place the markers
      
      var JSON;
  
      $.getJSON('resolve.php', function(response){
            $.each(response, function(i, v) {
              
              // Decide which icon to use
              var colorIcon = '';
              
              if (v.overall_performance > 0 && v.overall_performance <= 50)
              {
                colorIcon = red;
              }
              else if (v.overall_performance >= 51 && v.overall_performance <= 79)
              {
                colorIcon = amber;
              }
              else
              {
                colorIcon = green;
              }

              if (1 == 1) {
                              
                var marker = new google.maps.Marker({
                    position: { lat: v.Latitude, lng: v.Longitude },
                    map: map,
                    title: v.name,
                    icon: colorIcon
                  });
                  
                var contact = (v.cel.trim().length > 0) ? v.email : v.cel;
                
                var Ownership = 'No Ownership Information';
                
                switch(v.ownership)
                {
                  case 'Facility Owner - Government - Prov': Ownership = 'Provincial Government Owned'; break;
                  case 'Facility Owner - Tribal': Ownership = 'Tribal Government Owned'; break;
                  case 'Facility Owner - Government - Local': Ownership = 'Local Government Owned'; break;
                  case 'Facility Owner - Other': Ownership = 'Unspecified Ownership'; break;
                  case 'Facility Owner - Private': Ownership = 'Private Ownership'; break;
                }
                
                var infowindow = new google.maps.InfoWindow({
                    content: 
                    "<u>" + v.classification + "</u><br>" +
                    v.name + " ("+v.overall_performance+"%)<br>" +
                    Ownership + "<br>" + 
                    v.manager + " ("+v.cel+")"
                });
                  
                google.maps.event.addListener(marker, 'click', function() {
                  infowindow.open(map,marker);
                });
              
              }
                
            });
      });

    }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?callback=initMap" async defer></script>
  </body>
</html>
