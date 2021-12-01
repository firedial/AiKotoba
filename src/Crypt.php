<?php
namespace src;

class Crypt
{
    const HASH_STRETCH_NUMBER = 1024;

    public static function getPassword($key, $name, $seed, $base, $len)
    {
        $iv = hash('sha256', $name, true) ^ hash('sha256', $seed, true) ^ hash('sha256', $base, true) ^ hash('sha256', $len, true);
        $prePassword = self::getPrePassword($key, $iv, self::HASH_STRETCH_NUMBER);
        $baseArray = self::getBaseArray($prePassword, $base);
        $baseString = self::getBaseString($base);
        $longPassword = array_map(
            function ($x) use ($baseString) {
                return $baseString[$x];
            }, $baseArray
        );

        return implode('', array_slice($longPassword, 0, $len));
    }

    private static function getBaseString($base)
    {
        if ($base === 10) {
            return range('0', '9');
        }

        if ($base === 26) {
            return range('a', 'z');
        }

        if ($base === 36) {
            return array_merge(range('a', 'z'), range('0', '9'));
        }

        if ($base === 52) {
            return array_merge(range('A', 'Z'), range('a', 'z'));
        }

        if ($base === 62) {
            return array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        }

        // デフォルト値は 72 とする
        $large = range('A', 'Z');
        // unset I
        unset($large[8]);
        // unset O
        unset($large[14]);
        
        $small = range('a', 'z');
        // unset l
        unset($large[11]);

        $symbol = array('#', '$', '%', '&', '(', ')', '+', '-', '<', '=', '>', '?', '_', '^');
        return array_merge($large, $small, range('1', '9'), $symbol);
    }

    private static function getPrePassword($key, $iv, $stretch)
    {
        if ($stretch === 0) {
            return $iv;
        }
        return self::getPrePassword($key, $key ^ hash('sha256', $iv, true), $stretch - 1);
    }

    private static function getBaseArray($binary, $base)
    {
        $num = self::get256bitNumber($binary);

        $result = array();
        while (true) {
            if ((int)$num === 0) {
                return $result;
            }

            $mod = bcmod($num, $base);
            $div = bcdiv($num, $base);

            $result[] = (int)$mod;
            $num = $div;
        }
    }

    private static function get256bitNumber($binaryData)
    {
        $info = unpack('Na/Nb/Nc/Nd/Ne/Nf/Ng/Nh', $binaryData);
        $num = 0;
        foreach ($info as $n) {
            $num = bcadd(bcmul($num, bcpow('2', '32')), $n);
        }
        return $num;
    }

}


