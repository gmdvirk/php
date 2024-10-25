<?php

require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;


define('CONSUMER_KEY','jIoHYWfC43OKDxMylKgHkRW5b');
define('CONSUMER_SECRET','ndvGboYkSHb322QnRO71ojdrh2a9z9QSpEeip69qpN99iPeclq');
define('ACCESS_TOKEN','439099869-RMI5XU8ry7DqccgnPRBhlZMxAqKVuu0KemdAUnYj');
define('ACCESS_TOKEN_SECRET','5H8Z1oS5y40Q7xmohBiMOYQveEUTXB9xiczjR5Q2UwGaO');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);

return $connection;
$content = $connection->get("account/verify_credentials");

echo '<pre>';print_r($content);die;