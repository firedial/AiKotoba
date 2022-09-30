<?php
require 'vendor/autoload.php';
use src\Key;

$wordlistText = file_get_contents('wordlist.txt');
if ($wordlistText === false) {
    echo 'Can not open wordlist file.';
    exit;
}
$wordlist = explode("\n", $wordlistText);

$key = file_get_contents('.key');
// 鍵ファイルが読み込めるならワードを表示させて終了
if ($key !== false) {
    $words = Key::getWordsFromKey($wordlist, $key);
    echo implode("\n", $words);
    exit;
}

// それ以外の場合は鍵を復旧する
$words = file_get_contents('.words');
if ($words === false) {
    echo 'Can not open words file.';
    exit;
}

// 鍵を作り直す
$key = Key::getKeyFromWords(explode("\n", $wordlistText), explode("\n", $words));
file_put_contents('.key', $key);
