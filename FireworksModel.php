<?php

class FireworksModel {

    private $dbh;

    public function __construct(PDO $dbh) {
        $this->dbh = $dbh;
    }

    public function select_tweets($count = 180) {
        $sql = 'select * from ff_tweets where(`geo_lat` IS NOT NULL) limit ' . $count;
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $tweets = array();
        while($res = $stmt->fetch()) {
            $st = new stdclass();
            $st->geo = new stdclass();
            $st->geo->coordinates = array($res["geo_lat"], $res["geo_lon"]);
            $st->text = $res["tweet_text"];
            $st->user_id = $res["user_id"];
            $tweets[] = $st;
        }
        return $tweets;
    }

}
