<?php

require_once('src/Crypt.php');
use src\Crypt;

$key = file_get_contents('./master_key.txt');
// 読み込みに失敗した時
if ($key === false) {
    echo 'Can not read master key file.' . PHP_EOL;
    exit; 
}

$name = isset($argv[1]) ? $argv[1] : '';

$params = getopt('b:l:');
$base = isset($params['b']) ? $params['b'] : 72;
$len = isset($params['l']) ? $params['l'] : 16;

// シードを受け取る
echo 'seed: ';
system('stty -echo');
@flock(STDIN, LOCK_EX);
$seed = fgets(STDIN);
@flock(STDIN, LOCK_UN);
system('stty echo');
$seed = trim($seed);
echo PHP_EOL;

echo Crypt::getPassword($key, $name, $seed, $base, $len);
echo PHP_EOL;


