<?php

if (file_exists('.key')) {
    echo 'Exists .key file already.' . PHP_EOL;
    exit;
}

file_put_contents('.key', bin2hex(random_bytes(32)));
