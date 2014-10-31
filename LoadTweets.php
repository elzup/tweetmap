<?php

function get_tweets_db($count = 180, $since_id = 0) {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
    $dm = new FireworksModel($pdo);
    $tweets = $dm->select_geo_tweets($count, $since_id);
    return $tweets;
}

function get_tweets_db_hot($count = 90, $since_id = 0) {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
    $dm = new FireworksModel($pdo);
    $tweets = $dm->select_geo_tweets_hot($count, $since_id);
    return $tweets;
}

function get_second_tweets_db($count = 180, $type) {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
    $dm = new FireworksModel($pdo);
    if ($type == 'b') {
        $tweets = $dm->select_second_tweets_hot($count);
    } else {
        $tweets = $dm->select_second_tweets($count);
    }
    return $tweets;
}

