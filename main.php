<?php
require 'vendor/autoload.php';

use src\Crypt;

$key = file_get_contents('.key');
// 読み込みに失敗した時
if ($key === false) {
    echo 'Can not read master key file.' . PHP_EOL;
    exit; 
}

// オプションの受け取り
$params = getopt('b:l:i:');
$base = isset($params['b']) ? (int)$params['b'] : 72;
$len = isset($params['l']) ? (int)$params['l'] : 16;
$iteration = isset($params['i']) ? (int)$params['i'] : 65536;

// 各種値の取得
echo 'name: ';
$name = trim(fgets(STDIN));
system('stty -echo');
@flock(STDIN, LOCK_EX);
echo 'phrase: ';
$phrase = trim(fgets(STDIN));
echo PHP_EOL;
echo 'seed: ';
$seed = trim(fgets(STDIN));
@flock(STDIN, LOCK_UN);
system('stty echo');
echo PHP_EOL;

$password = Crypt::create($key, $phrase, $name, $seed, $base, $len);
echo 'password: ' . $password;
echo PHP_EOL;
echo 'checksum: ' . Crypt::getChecksum($password, $seed);
echo PHP_EOL;




