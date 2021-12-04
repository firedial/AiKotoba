<?php
namespace src;

class Crypt
{
    const HASH_STRETCH_NUMBER = 1024;
    
    public static function create($key, $phrase, $name, $seed, $base, $len)
    {
        if ($len <= 0 || 41 <= $len) {
            throw new \Exception('Wrong length: ' . $len);
        }

        $secret = self::mh($key, 1) ^ self::mh($phrase, 2);
        $iv = self::mh($name, 3) ^ self::mh($seed, 4) ^ self::mh($base, 5) ^ self::mh($base, 6);

        $baseString = self::getBaseString($base);
        $baseArray = self::getBaseArray(
            self::getPrePassword($secret, $iv, self::HASH_STRETCH_NUMBER),
            $base
        );

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

        if ($base === 72) {
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


        throw new \Exception('Wrong base number: ' . $base);
    }

    public static function getPrePassword($secret, $iv, $stretch)
    {
        if ($stretch === 0) {
            return $iv;
        }
        return self::getPrePassword($secret, $secret ^ self::h($iv), --$stretch);
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

    private static function h($v)
    {
        return hash('sha256', $v, true);
    }

    private static function mh($v, $n)
    {
        if ($n === 0) {
            return $v;
        }
        return self::mh(self::h($v), --$n);
    }
}


