<?php

class FireworksModel {

    private $dbh;
    public function __construct(PDO $dbh) {
//        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbh = $dbh;
    }

    public function select_second_tweets($user_count = 100) {
        // TODO*
        $sql = "select * from ff_second_tweets where (user_id <= " . $user_count . ")";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $tweets = array();
        while($res = $stmt->fetch()) {
            $st = new stdclass();
            $st->id = $res["_id"];
            $st->geo = new stdclass();
            $st->geo->coordinates = array($res["geo_lat"], $res["geo_lon"]);
            $st->text = $res["tweet_text"];
            $st->user_id = $res["user_id"];
            $st->timestamp = strtotime($res["timestamp"]);
            $st->time = date('Hi', $st->timestamp);
            if (!isset($tweets[$st->user_id])) {
                $tweets[$st->user_id] = array();
            }
            $tweets[$st->user_id][] = $st;
        }
        return $tweets;
    }

    public function select_geo_tweets($count = 180, $since_id = 0) {
        // GEO情報の付いているツイートのみ
        $sql = "select * from ff_tweets where (`geo_lat` IS NOT NULL and `place` = 1 and `_id` > " . $since_id . " and `timestamp` between '2014-10-18 00:00:00' and '2014-10-18 23:59:59') order by _id limit " . $count;
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $tweets = array();
        while($res = $stmt->fetch()) {
            $st = new stdclass();
            $st->id = $res["_id"];
            $st->geo = new stdclass();
            $st->geo->coordinates = array($res["geo_lat"], $res["geo_lon"]);
            $st->text = $res["tweet_text"];
            $st->user_id = $res["user_id"];
            $tweets[] = $st;
        }
        return $tweets;
    }

    public function regist_second_tweets($tweets) {
        foreach ($tweets as $user_id => $statuses) {
            $user_id = $this->regist_user($user_id);
            foreach ($statuses as $st) {
                $this->regist_second_tweet($user_id, $st);
            }
        }
    }

    public function regist_second_tweet($user_id, $st) {
//        echo "regist_tweet \n";
        $sql = "insert into `ff_second_tweets` (`user_id`, `tweet_id`, `tweet_text`, `geo_lat`, `geo_lon`, `timestamp`) values (:USER_ID, :TWEET_ID, :TWEET_TEXT, :GEO_LAT, :GEO_LON, :TIMESTAMP)";
        $stmt = $this->dbh->prepare($sql);
        $params = array(
            ':USER_ID' => $user_id,
            ':TWEET_ID' => $st->id,
            ':TWEET_TEXT' => $st->text,
            ':GEO_LAT' => $st->geo->coordinates[0],
            ':GEO_LON' => $st->geo->coordinates[1],
            ':TIMESTAMP' => date('Y-m-d H:i:s', $st->timestamp),
        );
        $stmt->execute($params);
    }

    public function regist_user($twitter_user_id) {
//        echo "regist_user \n";
        $sql = "insert into `ff_users` (`twitter_user_id`) values(:ID)";
        $stmt = $this->dbh->prepare($sql);
        $params = array(
            ':ID' => $twitter_user_id,
        );
        $stmt->execute($params);
        return $this->dbh->lastInsertId();
    }

}
