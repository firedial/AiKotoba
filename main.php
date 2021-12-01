<?php

require_once('Crypt.php');

$key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
$name = 'name';
$seed = 'seed';
$base = 72;
$len = 16;

$password = Crypt::getPassword($key, $name, $seed, $base, $len);
echo $password;
echo PHP_EOL;


