<?php

declare(strict_types=1);

namespace Afonso\Base24;

/**
 * An encoder/decoder that uses the Base 24 binary-to-text encoding scheme.
 */
class Encoder
{
    /**
     * The alphabet used by this encoder.
     *
     * @var string
     */
    const ALPHABET = 'ZAC2B3EF4GH5TK67P8RS9WXY';

    /**
     * The length of the alphabet used by this encoder.
     *
     * @var int
     */
    const ALPHABET_LENGTH = 24;

    private $encodeMap = [];
    private $decodeMap = [];

    public function __construct()
    {
        for ($i = 0; $i < strlen(self::ALPHABET); $i++) {
            $this->decodeMap[self::ALPHABET[$i]] = $i;
            $this->decodeMap[strtolower(self::ALPHABET[$i])] = $i;
            $this->encodeMap[$i] = self::ALPHABET[$i];
        }
    }

    /**
     * Convert the given array of bytes into a Base 24-encoded string.
     *
     * The length of the input array must be a multiple of 4, otherwise this
     * method will throw an exception.
     *
     * @param int[] $input
     * @return string
     * @throws \InvalidArgumentException
     */
    public function encode(array $input): string
    {
        $dataLength = count($input);

        if ($dataLength % 4 !== 0) {
            throw new \InvalidArgumentException('Input to encode must have a length multiple of 4');
        }

        $result = '';
        for ($i = 0; $i < $dataLength / 4; $i++) {
            $j = $i * 4;
            $mask = 0xFF;

            $b3 = $input[$j] & $mask;
            $b2 = $input[$j + 1] & $mask;
            $b1 = $input[$j + 2] & $mask;
            $b0 = $input[$j + 3] & $mask;

            $value = 0xFFFFFFFF &
                (($b3 << 24) | ($b2 << 16) | ($b1 << 8) | $b0);

            $subResult = '';
            for ($k = 0; $k < 7; $k++) {
                $idx = $value % 24;
                $value = $value / self::ALPHABET_LENGTH;
                $subResult = $this->encodeMap[$idx] . $subResult;
            }

            $result .= $subResult;
        }
        return $result;
    }

    /**
     * Convert the given Base 24-encoded string into an array of bytes.
     *
     * The length of the input string must be a multiple of 7, otherwise this
     * method will throw an exception.
     *
     * @param string $input
     * @return int[]
     * @throws \InvalidArgumentException
     */
    public function decode(string $input): array
    {
        $dataLength = strlen($input);

        if ($dataLength % 7 !== 0) {
            throw new \InvalidArgumentException('Input to decode must have a length multiple of 7');
        }

        $bytes = [];
        for ($i = 0; $i < $dataLength / 7; $i++) {
            $j = $i * 7;
            $subData = substr($input, $j, 7);
            $value = 0;

            foreach (str_split($subData) as $s) {
                if (array_key_exists($s, $this->decodeMap) === false) {
                    throw new \InvalidArgumentException('Input to decode contains an invalid character');
                }

                $idx = $this->decodeMap[$s];
                $value = self::ALPHABET_LENGTH * $value + $idx;
            }

            $mask = 0xFF;
            $b0 = ($value & ($mask << 24)) >> 24;
            $b1 = ($value & ($mask << 16)) >> 16;
            $b2 = ($value & ($mask << 8)) >> 8;
            $b3 = $value & $mask;

            $bytes[] = $b0;
            $bytes[] = $b1;
            $bytes[] = $b2;
            $bytes[] = $b3;
        }

        return $bytes;
    }
}
