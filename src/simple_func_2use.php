<?
function base24_encode($input){
        $ALPHABET = 'ZAC2B3EF4GH5TK67P8RS9WXY';
        $ALPHABET_LENGTH = 24; $encodeMap = []; $decodeMap = [];
        for ($i = 0; $i < strlen($ALPHABET); $i++) { $decodeMap[$ALPHABET[$i]] = $i; $decodeMap[strtolower($ALPHABET[$i])] = $i; $encodeMap[$i] = $ALPHABET[$i]; }

        $dataLength = is_array($input)?count($input):strlen($input);
        if ($dataLength % 4 !== 0) { throw new \InvalidArgumentException('Input to encode must have a length multiple of 4'); }
        $result = '';
        for ($i = 0; $i < $dataLength / 4; $i++) {
            $j = $i * 4;
            $mask = 0xFF;
            $b3 = $input[$j] & $mask;
            $b2 = $input[$j + 1] & $mask;
            $b1 = $input[$j + 2] & $mask;
            $b0 = $input[$j + 3] & $mask;
            $value = 0xFFFFFFFF & (($b3 << 24) | ($b2 << 16) | ($b1 << 8) | $b0);
            $subResult = '';
            for ($k = 0; $k < 7; $k++) {
                $idx = $value % 24;
                $value = $value / $ALPHABET_LENGTH;
                $subResult = $encodeMap[$idx] . $subResult;
            }
            $result .= $subResult;
        }
        return $result;
}
function base24_decode($input){
        $ALPHABET = 'ZAC2B3EF4GH5TK67P8RS9WXY';
        $ALPHABET_LENGTH = 24; $encodeMap = []; $decodeMap = [];
        for ($i = 0; $i < strlen($ALPHABET); $i++) { $decodeMap[$ALPHABET[$i]] = $i; $decodeMap[strtolower($ALPHABET[$i])] = $i; $encodeMap[$i] = $ALPHABET[$i]; }
        $dataLength = strlen($input);
        if ($dataLength % 7 !== 0) { throw new \InvalidArgumentException('Input to decode must have a length multiple of 7'); }
        $bytes = [];
        for ($i = 0; $i < $dataLength / 7; $i++) {
            $j = $i * 7;
            $subData = substr($input, $j, 7);
            $value = 0;
            foreach (str_split($subData) as $s) {
                if (array_key_exists($s, $decodeMap) === false) { throw new \InvalidArgumentException('Input to decode contains an invalid character'); }
                $idx = $decodeMap[$s];
                $value = $ALPHABET_LENGTH * $value + $idx;
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
        return implode('',$bytes);
  }

  /// testing
  print_r([$hellob24 = base24_encode('4111111111111111'),base24_decode($hellob24)]);
  print_r([$hellob24 = base24_encode('3216549873216549'),base24_decode($hellob24)]);

  ?>
