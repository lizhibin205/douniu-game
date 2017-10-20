<?php 
use BGame\Douniu\Douniu;

require __DIR__ . "/vendor/autoload.php";

$game = new Douniu();
$result = $game->init(['1', '2', '3', '4', '5', '6'])->getResult();
print_r($result);