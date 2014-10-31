<?php

function save_tweets_db($tweets, $is_watch) {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
    $dm = new FireworksModel($pdo);
    $dm->regist_second_tweets($tweets, $is_watch);
}
