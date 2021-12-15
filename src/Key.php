<?php
namespace src;

class Key
{

    /**
     * ワードを鍵に変換する
     * 
     * @param string[] $wordlist 2048 語の配列。0 から 2047 までの添字が振られている前提。
     * @param string $key 256 bit 長の 16 進数表記の文字列(64文字)。
     * @return string[] 変換後のワード。
     */
    public function getWordsFromKey(array $wordlist, string $key): array
    {

        $checksum = substr(hash('sha256', $key), 0, 2);

        // 巨大な数になるのでうまく機能しない
        // $binary = base_convert($key . $checksum, 16, 2);
        $binary = implode('',
            array_map(
                function ($h) {
                    return str_pad(base_convert($h, 16, 2), 4, '0', STR_PAD_LEFT);
                },
                str_split($key . $checksum, 1)
            )
        );

        return array_map(
            function ($b) use ($wordlist) {
                return $wordlist[(int)base_convert($b, 2, 10)];
            },
            str_split($binary, 11)
        );

    }

    /**
     * ワードから鍵に変換する
     * 
     * @param string[] $wordlist 2048 語の配列。0 から 2047 までの添字が振られている前提。
     * @param string[] $words 24 語のワード
     * @return string 変換後の鍵
     */
    public function getKeyFromWords(array $wordlist, array $words): string
    {
        $dec = array_map( function ($w) use ($wordlist) {
                return array_search($w, $wordlist);
            },
            $words
        );

        $fails = array_filter(
            $dec,
            function ($d) {
                return $d === false;
            }
        );

        if (count($fails) > 0) {
            throw new Execption();
        }

        $binary = implode('',
            array_map(
                function ($d) use ($wordlist) {
                    return str_pad(base_convert((int)$d, 10, 2), 11, '0', STR_PAD_LEFT);
                },
                $dec
            )
        );

        $hex = implode('',
            array_map(
                function ($b) {
                    return base_convert($b, 2, 16);
                },
                str_split($binary, 4)
            )
        );

        $key = substr($hex, 0, 64);
        $checksum = substr($hex, 64, 2);

        if (substr(hash('sha256', $key), 0, 2) !== $checksum) {
            throw new Execption();
        }

        return $key;
    }

}
