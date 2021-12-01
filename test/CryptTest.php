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

    public function testBase(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $name = 'name';
        $seed = 'seed';
        $len = 16;

        $base = 10;
        $password = Crypt::getPassword($key, $name, $seed, $base, $len);   
        $this->assertSame($password, '8615002845322849');

        $base = 26;
        $password = Crypt::getPassword($key, $name, $seed, $base, $len);   
        $this->assertSame($password, 'dlzsfqvioxhpmjts');

        $base = 36;
        $password = Crypt::getPassword($key, $name, $seed, $base, $len);   
        $this->assertSame($password, 'j5ie4cski6o9oozg');

        $base = 52;
        $password = Crypt::getPassword($key, $name, $seed, $base, $len);   
        $this->assertSame($password, 'zohhzrWfukEiLVrH');

        $base = 62;
        $password = Crypt::getPassword($key, $name, $seed, $base, $len);   
        $this->assertSame($password, 'H083O8qas5SF3HGQ');

        $base = 72;
        $password = Crypt::getPassword($key, $name, $seed, $base, $len);   
        $this->assertSame($password, 'zd?_1TgM6Z1%#H7b');
    }

    public function testLength(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $name = 'name';
        $seed = 'seed';
        $base = 72;

        for ($len = 1; $len <= 40; $len++) {
            $password = Crypt::getPassword($key, $name, $seed, $base, $len);   
            $this->assertSame(strlen($password), $len);
        }
    }

    public function testException(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $name = 'name';
        $seed = 'seed';
        $base = 73;
        $len = 16;
        $this->expectException(Exception::class);
        Crypt::getPassword($key, $name, $seed, $base, $len);
    }
}
