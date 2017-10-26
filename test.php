<?php 
use BGame\Douniu\Douniu;
use BGame\ZhaJinHua\ZhaJinHua;

require __DIR__ . "/vendor/autoload.php";

$douniu = new Douniu();
$result = $douniu->init(['1', '2', '3', '4', '5', '6'])->getResult();
//print_r($result);

$zhaJinHua = new ZhaJinHua();
$result = $zhaJinHua->init(['1', '2', '3', '4', '5', '6'])->getResult();
print_r($result);