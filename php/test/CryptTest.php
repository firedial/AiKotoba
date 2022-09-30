<?php

use PHPUnit\Framework\TestCase;
use src\Crypt;

final class CryptTest extends TestCase
{
    public function testCreatePassword(): void
    {
        $key = '8f6d3a485a7deda9c31791079ef5dd4c582bcc144ddc16d8027b82c4eb3a5bc7';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $iteration = 65536;
        $base = 72;
        $len = 16;

        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, '6>?rm8$5NpgS)WWb');
    }
    
    public function testParameter(): void
    {
        $key = '8f6d3a485a7deda9c31791079ef5dd4c582bcc144ddc16d8027b82c4eb3a5bc7';
        $phrase = 'phrase';
        $iteration = 256;
        $base = 72;
        $len = 16;

        $name = 'name1';
        $seed = 'seed1';
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, '4DiE?7%CVgvZ7cwq');
    }

    public function testDiffierence(): void
    {
        $key = '8f6d3a485a7deda9c31791079ef5dd4c582bcc144ddc16d8027b82c4eb3a5bc7';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $iteration = 256;
        $base = 72;
        $len = 16;

        $p0 = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   

        $key = '0000000000000000c31791079ef5dd4c582bcc144ddc16d8027b82c4eb3a5bc7';
        $p1 = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertNotSame($p0, $p1);

        $phrase = 'phrase1';
        $p2 = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertNotSame($p1, $p2);

        $name = 'name1';
        $p3 = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertNotSame($p2, $p3);

        $seed = 'seed1';
        $p4 = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertNotSame($p3, $p4);

        $base = 62;
        $p5 = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertNotSame($p4, $p5);

        $len = 15;
        $p6 = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertNotSame($p5, $p6);
    }

    public function testSpecificKey(): void
    {
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $iteration = 256;
        $base = 72;
        $len = 16;

        $key = '0000000000000000000000000000000000000000000000000000000000000000';
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, 'JKveN)D#2edGCF#s');

        $key = '1111111111111111111111111111111111111111111111111111111111111111';
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, 'e8nA$dsDzgoPkeZf');
    }

    public function testIteration(): void
    {
        $key = '8f6d3a485a7deda9c31791079ef5dd4c582bcc144ddc16d8027b82c4eb3a5bc7';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $base = 72;
        $len = 16;

        $iteration = 1;
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, 'N%#E3G<MCo6ZAm<<');

        $iteration = 256;
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, '4VH3qm)rAD9HYGZH');

        $iteration = 10000; 
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, '8Ht5d-K_J5#<GZ9S');

        $iteration = 65536; 
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, '6>?rm8$5NpgS)WWb');

        $iteration = 100000; 
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, 'ljiY_o>#>$zh91Kx');
    }

    public function testBase(): void
    {
        $key = '8f6d3a485a7deda9c31791079ef5dd4c582bcc144ddc16d8027b82c4eb3a5bc7';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $iteration = 256;
        $len = 16;

        $base = 10;
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, '2797682670430894');

        $base = 26;
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, 'iunslvyibhibviil');

        $base = 36;
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, 'qb3mxv5mb2skxbi2');

        $base = 52;
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, 'IXdsvDGQAtlkODXI');

        $base = 62;
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, 'y1T0rb4qKt474RXZ');

        $base = 72;
        $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
        $this->assertSame($password, '4VH3qm)rAD9HYGZH');
    }

    public function testLength(): void
    {
        $key = '8f6d3a485a7deda9c31791079ef5dd4c582bcc144ddc16d8027b82c4eb3a5bc7';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $iteration = 256;
        $base = 72;

        for ($len = 1; $len <= 40; $len++) {
            $password = Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
            $this->assertSame(strlen($password), $len);
        }
    }

    public function testBaseException(): void
    {
        $key = '8f6d3a485a7deda9c31791079ef5dd4c582bcc144ddc16d8027b82c4eb3a5bc7';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $iteration = 256;
        $base = 73;
        $len = 16;
        $this->expectException(Exception::class);
        Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
    }

    public function testLowerLengthException(): void
    {
        $key = '8f6d3a485a7deda9c31791079ef5dd4c582bcc144ddc16d8027b82c4eb3a5bc7';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $iteration = 256;
        $base = 72;
        $len = 0;
        $this->expectException(Exception::class);
        Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
    }

    public function testUpperLengthException(): void
    {
        $key = '8f6d3a485a7deda9c31791079ef5dd4c582bcc144ddc16d8027b82c4eb3a5bc7';
        $phrase = 'phrase';
        $name = 'name';
        $seed = 'seed';
        $iteration = 256;
        $base = 72;
        $len = 41;
        $this->expectException(Exception::class);
        Crypt::create($key, $phrase, $name, $seed, $iteration, $base, $len);   
    }

    public function testChecksum(): void
    {
        $password = 'ljiY_o>#>$zh91Kx';
        $seed = 'seed';
        $iteration = 256;

        $checksum = Crypt::getChecksum($password, $seed, $iteration);   
        $this->assertSame($checksum, '2283');

        $password = '4VH3qm)rAD9HYGZH';
        $checksum = Crypt::getChecksum($password, $seed, $iteration);   
        $this->assertSame($checksum, 'e05a');

        $seed = 'seed1';
        $checksum = Crypt::getChecksum($password, $seed, $iteration);   
        $this->assertSame($checksum, '7824');

        $iteration = 65536;
        $checksum = Crypt::getChecksum($password, $seed, $iteration);   
        $this->assertSame($checksum, 'f325');
    }

}
