<?php
namespace src;

class Crypt
{
    public static function create($key, $phrase, $name, $seed, $base, $len)
    {
        if ($len <= 0 || 41 <= $len) {
            throw new \Exception('Wrong length: ' . $len);
        }

        $baseString = self::getBaseString($base);
        $baseArray = self::getBaseArray(
            hash_pbkdf2('sha256', $key . $phrase, $name . $seed, 65536, 256, true),
            $base
        );

        $longPassword = array_map(
            function ($x) use ($baseString) {
                return $baseString[$x];
            }, $baseArray
        );

        return implode('', array_slice($longPassword, 0, $len));
    }

    public static function getChecksum($password, $seed)
    {
        return hash_pbkdf2('sha256', $password, $seed, 65536, 4, false);
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

