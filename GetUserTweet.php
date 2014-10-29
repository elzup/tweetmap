<?php

function get_user_tweets($tweets) {
    
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
    $connection->host = 'https://api.twitter.com/1.1/';

    // DB内で該当日の最初のツイート日時
    $start_id = 523126286693462016;
    $end_id = 523781960657674240;
    $user_list = array();
    $user_tweets = array();
    foreach ($tweets as $st) {
        if (in_array($user_id = $st->user_id, $user_list)) {
            continue;
        }
        $user_list[] = $user_id;

        $params = array(
            'user_id' => $user_id,
            'since_id' => $start_id,
            'max_id' => $end_id,
//            'count' => 10,
        );
        //var_dump($params);
        $query = 'statuses/user_timeline';
        $res = $connection->get($query, $params);
//        var_dump($res);
        $user_tweets[$user_id] = array();
        foreach ($res as $st) {
            if (!isset($st->geo)) {
                continue;
            }
            $user_tweets[$user_id][] = $st;
        }
    }
    return $user_tweets;
}
