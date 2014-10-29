<?php
function get_tweets($lat, $long) {
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
    $connection->host = 'https://api.twitter.com/1.1/';
    //var_dump($connection);
    $r = '10km';
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
    return array($tweets, $tweets_4s);
}
?>

