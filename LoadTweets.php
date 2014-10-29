<?php

function get_tweets_db($count = 180) {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
    $dm = new FireworksModel($pdo);
    $tweets = $dm->select_tweets($count);
    return $tweets;
}
