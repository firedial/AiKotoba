<?php

use PHPUnit\Framework\TestCase;
use src\Crypt;

final class CryptTest extends TestCase
{
    public function testCreatePassword(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $base = 72;
        $len = 16;

        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'x5J(9wo7ER_nR9&B');
    }
    
    public function testParameter(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $phrase = 'phrase';
        $base = 72;
        $len = 16;

        $name = 'name1';
        $seed = 'seed1';
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'x5J(9wo7ER_nR9&B');
    }

    public function testSpecificKey(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $base = 72;
        $len = 16;

        // 0x00000000000000000000000000000000
        $key = $key ^ $key;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'tgr%ym5b1#F_l^>N');

        // 0x11111111111111111111111111111111
        $key = ~$key;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'fvTxpjWMl5hpqbrN');
    }

    public function testBase(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $len = 16;

        $base = 10;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, '8578876338292060');

        $base = 26;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'suvjcimvtglkpmor');

        $base = 36;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'k982zz6o7byqltmp');

        $base = 52;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'SxlhTvHmdNQozXFO');

        $base = 62;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'sVfxqQvpZcF3JIiX');

        $base = 72;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'x5J(9wo7ER_nR9&B');
    }

    public function testLength(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $base = 72;

        for ($len = 1; $len <= 40; $len++) {
            $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
            $this->assertSame(strlen($password), $len);
        }
    }

    public function testBaseException(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $base = 73;
        $len = 16;
        $this->expectException(Exception::class);
        Crypt::create($key, $phrase, $name, $seed, $base, $len);   
    }

    public function testLowerLengthException(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $base = 72;
        $len = 0;
        $this->expectException(Exception::class);
        Crypt::create($key, $phrase, $name, $seed, $base, $len);   
    }

    public function testUpperLengthException(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $base = 72;
        $len = 41;
        $this->expectException(Exception::class);
        Crypt::create($key, $phrase, $name, $seed, $base, $len);   
    }

}
