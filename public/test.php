<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/10/19
 * Time: 15:43
 */
//echo md5("123456_#string_try");

$arr = [
    'order',
    'id',
    'desc',
    'price',
    'asc'
];

for ($i = 1; $i < count($arr); $i+=2){
    $order[] = [$arr[$i] => $arr[$i+1]];
}

$order[] = ['nice' => 'desc'];

var_dump($order);

//$i = 1;
//$i +=2;
//echo $i;