<?php

use PHPUnit\Framework\TestCase;
use src\Crypt;

final class CryptTest extends TestCase
{
    public function testCreatePassword(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $name = 'name';
        $seed = 'seed';
        $base = 72;
        $len = 16;

        $password = Crypt::getPassword($key, $name, $seed, $base, $len);   
        $this->assertSame($password, 'zd?_1TgM6Z1%#H7b');
    }
}
