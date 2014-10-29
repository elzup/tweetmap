<?php

define('ENVIRONMENT_DEV', 1);
define('ENVIRONMENT_PRO', 2);
if (file_exists('./env.php')) {
    define('ENVIRONMENT', ENVIRONMENT_PRO);
} else {
    define('ENVIRONMENT', ENVIRONMENT_DEV);
}

require_once('./vendor/autoload.php');
require_once('./MyStatus.php');
require_once('./keys.php');
require_once('./GetTweets.php');
require_once('./GetUserTweet.php');
require_once('./FireworksModel.php');
require_once('./LoadTweets.php');
require_once('./Funcs.php');

$tweets = get_tweets_db(2);
$user_tweets = get_user_tweets($tweets);

// 藤沢市
$lat = "35.3266269";
$long = "139.4829343";
//// 足立区
//$lat = "35.7780774";
//$long = "139.7972246";

//list($tweets, $tweets_4s) = get_tweets($lat, $long);

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
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_API_KEY ?>&sensor=TRUE">
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
    $locs[] = '["' . escape_js_string($st->text) . '", ' . $st->geo->coordinates[0] . ', ' . $st->geo->coordinates[1] . ']';
}

//foreach ($tweets_4s as $st) { 
//    if (!isset($st->geo) || $st->geo->type != 'Point') continue;
//    $locs_4s[] = '["' . str_replace(array('"', '”', '“', "\n"), array('\\"', '\\"', '\\"', ' '), $st->text) . '", ' . $lat . ', ' . $long . ']';
//}

?>
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
