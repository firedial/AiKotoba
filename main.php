<?php

require_once('src/Crypt.php');
use src\Crypt;

$key = file_get_contents('./master_key.txt');
// 読み込みに失敗した時
if ($key === false) {
    echo 'Can not read master key file.' . PHP_EOL;
    exit; 
}

$name = isset($argv[1]) ? array_pop($argv) : '';

$params = getopt('b:l:');
$base = isset($params['b']) ? (int)$params['b'] : 72;
$len = isset($params['l']) ? (int)$params['l'] : 16;

// シードを受け取る
system('stty -echo');
@flock(STDIN, LOCK_EX);
echo 'phrase: ';
$phrase = fgets(STDIN);
echo PHP_EOL;
echo 'seed: ';
$seed = fgets(STDIN);
@flock(STDIN, LOCK_UN);
system('stty echo');
$seed = trim($seed);
$phrase = trim($phrase);
echo PHP_EOL;

$password = Crypt::create($key, $phrase, $name, $seed, $base, $len);
echo 'password: ' . $password;
echo PHP_EOL;
echo 'checksum: ' . Crypt::getChecksum($password, $seed);
echo PHP_EOL;

