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
        $this->assertSame($password, 'txvecXPrsbNCgAc9');
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
        $this->assertSame($password, 'y(qlHlAyrRs2lZ4G');
    }

    public function testDiffierence(): void
    {
        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $base = 72;
        $len = 16;

        $p0 = Crypt::create($key, $phrase, $name, $seed, $base, $len);   

        $key = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaab';
        $p1 = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertNotSame($p0, $p1);

        $phrase = 'phrase1';
        $p2 = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertNotSame($p1, $p2);

        $name = 'name1';
        $p3 = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertNotSame($p2, $p3);

        $seed = 'seed1';
        $p4 = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertNotSame($p3, $p4);

        $base = 62;
        $p5 = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertNotSame($p4, $p5);

        $len = 15;
        $p6 = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertNotSame($p5, $p6);
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
        $this->assertSame($password, 'TE)lyWMZ5#9?Vv$G');

        // 0x11111111111111111111111111111111
        $key = ~$key;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'MCog7XQx<-PgPkWr');
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
        $this->assertSame($password, '9268087381340731');

        $base = 26;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'ovlppjfxynwconit');

        $base = 36;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'nvjg2w7for5aq2fg');

        $base = 52;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'RYFwBuYDUTQfrGtE');

        $base = 62;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'Yt8hqTvINZqYOzwC');

        $base = 72;
        $password = Crypt::create($key, $phrase, $name, $seed, $base, $len);   
        $this->assertSame($password, 'txvecXPrsbNCgAc9');
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
