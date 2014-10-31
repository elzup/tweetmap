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
require_once('./SaveTweets.php');
require_once('./Funcs.php');

$start_id = trim(file_get_contents('./memory2.txt'));
$tweets = get_tweets_db_hot(90, $start_id);
if (!count($tweets)) {
    die("no tweet");
}
$st = end($tweets);
$end_id = $st->id;
reset($tweets);
exit;
$user_tweets = get_user_tweets($tweets);
save_tweets_db($user_tweets, TRUE);
file_put_contents('./memory2.txt', $end_id);
