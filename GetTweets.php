<?php

require_once('./vendor/autoload.php');
require_once('./MyStatus.php');
require_once('./keys.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
$connection->host = 'https://api.twitter.com/1.1/';
//var_dump($connection);
$lat = "35.749412";
$long = "139.805108";
$r = '100km';
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

$tweets = $res->statuses;
$params = array(
    'q' => 'I\'m at 北千住',
);
$query = 'search/tweets';
$res = $connection->get($query, $params);
$tweets_4s = array();
foreach ($res->statuses as $st) {
//    var_dump($st);
    if (strpos($st->source, 'square') !== FALSE && !isset($st->geo)) {
        $tweets_4s[] = $st;
    }
}
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
          zoom: 13,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        var infowindow = new google.maps.InfoWindow();
        var geocoder = new google.maps.Geocoder();
<?php 
$locs = array();
$locs_4s = array();
foreach ($tweets as $i => $st) { 
    if (!isset($st->geo)) continue;
    $locs[] = '["' . str_replace(array('"', '”', '“', "\n"), array('\\"', '\\"', '\\"', ' '), $st->text) . '", ' . $st->geo->coordinates[0] . ', ' . $st->geo->coordinates[1] . ']';
}

//foreach ($tweets_4s as $st) { 
//    if (!isset($st->geo) || $st->geo->type != 'Point') continue;
//    $locs_4s[] = '["' . str_replace(array('"', '”', '“', "\n"), array('\\"', '\\"', '\\"', ' '), $st->text) . '", ' . $lat . ', ' . $long . ']';
//}

?>
console.log(<?= implode(',', $locs)?>);
var locations = [<?= implode(',', $locs)?>];
console.log(locations);
var locations_4s = [<?= implode(',', $locs_4s)?>];
    for (i = 0; i < locations.length; i++) {
      var pinColor = "FF0000";
      var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
          new google.maps.Size(21, 34),
        new google.maps.Point(0,0),
        new google.maps.Point(10, 34));
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        icon: pinImage,
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
