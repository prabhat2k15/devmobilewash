<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Heatmap</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 34.052234, lng: -118.243685},
          zoom: 9
        });

        var layer = new google.maps.FusionTablesLayer({
          query: {
            select: 'location',
            from: '1w98QPlWNJsN-avLuJ31cCbfCJCW-t6c-Qummct0q'
          },
          heatmap: {
            enabled: true
          }
        });

        layer.setMap(map);
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuokwB88pjRfuNHVc9ktCUqDuuquOMLwA&callback=initMap">
    </script>
  </body>
</html>