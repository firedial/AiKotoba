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
    
    public function testParameter(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $base = 72;
        $len = 16;

        $name = 'name1';
        $seed = 'seed1';
        $password = Crypt::getPassword($key, $name, $seed, $base, $len);   
        $this->assertSame($password, 'UsXog=%jMXzPo6Re');
    }

    public function testSpecificKey(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $name = 'name';
        $seed = 'seed';
        $base = 72;
        $len = 16;

        // 0x00000000000000000000000000000000
        $key = $key ^ $key;
        $password = Crypt::getPassword($key, $name, $seed, $base, $len);   
        $this->assertSame($password, 'kll4?FVr)uA?GHFx');

        // 0x11111111111111111111111111111111
        $key = ~$key;
        $password = Crypt::getPassword($key, $name, $seed, $base, $len);   
        $this->assertSame($password, 'Q=(E#<yDWTDq<r)1');
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

    public function testBaseException(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $name = 'name';
        $seed = 'seed';
        $base = 73;
        $len = 16;
        $this->expectException(Exception::class);
        Crypt::getPassword($key, $name, $seed, $base, $len);
    }

    public function testLowerLengthException(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $name = 'name';
        $seed = 'seed';
        $base = 72;
        $len = 0;
        $this->expectException(Exception::class);
        Crypt::getPassword($key, $name, $seed, $base, $len);
    }

    public function testUpperLengthException(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $name = 'name';
        $seed = 'seed';
        $base = 72;
        $len = 41;
        $this->expectException(Exception::class);
        Crypt::getPassword($key, $name, $seed, $base, $len);
    }

}
