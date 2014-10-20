<?php

require_once('./vendor/autoload.php');
require_once('./MyStatus.php');
require_once('./keys.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
$connection->host = 'https://api.twitter.com/1.1/';
//var_dump($connection);
$lat = "35.749412";
$long = 139.805108;
$r = '1km';
$params = array(
    'geocode' => implode(',', array($lat, $long, $r)),
);
//var_dump($params);
$query = 'search/tweets';
$res = $connection->get($query, $params);
//foreach ($res->statuses as $st) {
//    echo '@' . $st->user->screen_name;
//    echo PHP_EOL;
//    echo $st->text;
//    echo PHP_EOL;
//    echo PHP_EOL;
//    if (isset($st->geo)) {
//        var_dump($st->geo);
//    }
//}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
    <script type="text/javascript"
src="http://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_API_KEY ?>&sensor=TRUE">
    </script>
    <script type="text/javascript">
      function initialize() {
        var mapOptions = {
        center: new google.maps.LatLng(<?= $lat ?>, <?= $long ?>),
          zoom: 17,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        var infowindow = new google.maps.InfoWindow();
        var geocoder = new google.maps.Geocoder();
<?php 
$locs = array();
foreach ($res->statuses as $st) { 
if (!isset($st->geo) || $st->geo->type != 'Point') continue;
    $locs[] = '["' . str_replace(array('"', '”', '“', "\n"), array('\\"', '\\"', '\\"', ' '), $st->text) . '", ' . $st->geo->coordinates[0] . ', ' . $st->geo->coordinates[1] . ']';
}?>
var locations = [<?= implode(',', $locs)?>];
console.log(locations);
    for (i = 0; i < locations.length; i++) {
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
    });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
      }

    </script>
  </head>
  <body onload="initialize()">
    <div id="map_canvas" style="width:100%; height:100%"></div>
  </body>
</html>
