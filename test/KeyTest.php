<?php

use PHPUnit\Framework\TestCase;
use src\Key;

final class KeyTest extends TestCase
{
    public function testIdentity(): void
    {
        $wordlist = range(0, 2047);

        for ($i = 0; $i < 10000; $i++) {
            $key = bin2hex(random_bytes(32));
            $this->assertSame($key, Key::getKeyFromWords($wordlist, Key::getWordsFromKey($wordlist, $key)));
        }
    }
}
