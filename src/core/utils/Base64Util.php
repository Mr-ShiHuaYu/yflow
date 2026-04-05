<?php
/*
 *    Copyright 2026, Y-Flow (974988176@qq.com).
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *       https://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace Yflow\core\utils;

/**
 * Base64 工具类
 *
 * @author hh
 */
final class Base64Util
{
    private const BASELENGTH         = 128;
    private const LOOKUPLENGTH       = 64;
    private const TWENTYFOURBITGROUP = 24;
    private const EIGHTBIT           = 8;
    private const SIXTEENBIT         = 16;
    private const FOURBYTE           = 4;
    private const SIGN               = -128;
    private const PAD                = '=';

    private static array $base64Alphabet       = [];
    private static array $lookUpBase64Alphabet = [];

    public function __construct()
    {
        // 防止实例化
    }

    /**
     * 静态初始化
     */
    private static function init(): void
    {
        if (empty(self::$base64Alphabet)) {
            for ($i = 0; $i < self::BASELENGTH; ++$i) {
                self::$base64Alphabet[$i] = -1;
            }
            for ($i = ord('Z'); $i >= ord('A'); $i--) {
                self::$base64Alphabet[$i] = $i - ord('A');
            }
            for ($i = ord('z'); $i >= ord('a'); $i--) {
                self::$base64Alphabet[$i] = $i - ord('a') + 26;
            }
            for ($i = ord('9'); $i >= ord('0'); $i--) {
                self::$base64Alphabet[$i] = $i - ord('0') + 52;
            }
            self::$base64Alphabet[ord('+')] = 62;
            self::$base64Alphabet[ord('/')] = 63;

            for ($i = 0; $i <= 25; $i++) {
                self::$lookUpBase64Alphabet[$i] = chr(ord('A') + $i);
            }
            for ($i = 26, $j = 0; $i <= 51; $i++, $j++) {
                self::$lookUpBase64Alphabet[$i] = chr(ord('a') + $j);
            }
            for ($i = 52, $j = 0; $i <= 61; $i++, $j++) {
                self::$lookUpBase64Alphabet[$i] = chr(ord('0') + $j);
            }
            self::$lookUpBase64Alphabet[62] = '+';
            self::$lookUpBase64Alphabet[63] = '/';
        }
    }

    private static function isWhiteSpace(int $octect): bool
    {
        return ($octect === 0x20 || $octect === 0xd || $octect === 0xa || $octect === 0x9);
    }

    private static function isPad(int $octect): bool
    {
        return ($octect === ord(self::PAD));
    }

    private static function isData(int $octect): bool
    {
        return ($octect < self::BASELENGTH && self::$base64Alphabet[$octect] !== -1);
    }

    /**
     * 编码
     *
     * @param string $data 二进制数据
     * @return string|null Base64 编码后的字符串
     */
    public static function encode(string $data): ?string
    {
        self::init();

        $lengthDataBits = strlen($data) * self::EIGHTBIT;
        if ($lengthDataBits === 0) {
            return "";
        }

        $fewerThan24bits = $lengthDataBits % self::TWENTYFOURBITGROUP;
        $numberTriplets  = intdiv($lengthDataBits, self::TWENTYFOURBITGROUP);
        $numberQuartet   = $fewerThan24bits !== 0 ? $numberTriplets + 1 : $numberTriplets;
        $encodedData     = [];

        $encodedIndex = 0;
        $dataIndex    = 0;

        for ($i = 0; $i < $numberTriplets; $i++) {
            $b1 = ord($data[$dataIndex++]);
            $b2 = ord($data[$dataIndex++]);
            $b3 = ord($data[$dataIndex++]);

            $l = $b2 & 0x0f;
            $k = $b1 & 0x03;

            $val1 = (($b1 & self::SIGN) === 0) ? ($b1 >> 2) : (($b1 >> 2) ^ 0xc0);
            $val2 = (($b2 & self::SIGN) === 0) ? ($b2 >> 4) : (($b2 >> 4) ^ 0xf0);
            $val3 = (($b3 & self::SIGN) === 0) ? ($b3 >> 6) : (($b3 >> 6) ^ 0xfc);

            $encodedData[$encodedIndex++] = self::$lookUpBase64Alphabet[$val1];
            $encodedData[$encodedIndex++] = self::$lookUpBase64Alphabet[$val2 | ($k << 4)];
            $encodedData[$encodedIndex++] = self::$lookUpBase64Alphabet[($l << 2) | $val3];
            $encodedData[$encodedIndex++] = self::$lookUpBase64Alphabet[$b3 & 0x3f];
        }

        if ($fewerThan24bits === self::EIGHTBIT) {
            $b1                           = ord($data[$dataIndex]);
            $k                            = $b1 & 0x03;
            $val1                         = (($b1 & self::SIGN) === 0) ? ($b1 >> 2) : (($b1 >> 2) ^ 0xc0);
            $encodedData[$encodedIndex++] = self::$lookUpBase64Alphabet[$val1];
            $encodedData[$encodedIndex++] = self::$lookUpBase64Alphabet[$k << 4];
            $encodedData[$encodedIndex++] = self::PAD;
            $encodedData[$encodedIndex++] = self::PAD;
        } elseif ($fewerThan24bits === self::SIXTEENBIT) {
            $b1 = ord($data[$dataIndex]);
            $b2 = ord($data[$dataIndex + 1]);
            $l  = $b2 & 0x0f;
            $k  = $b1 & 0x03;

            $val1 = (($b1 & self::SIGN) === 0) ? ($b1 >> 2) : (($b1 >> 2) ^ 0xc0);
            $val2 = (($b2 & self::SIGN) === 0) ? ($b2 >> 4) : (($b2 >> 4) ^ 0xf0);

            $encodedData[$encodedIndex++] = self::$lookUpBase64Alphabet[$val1];
            $encodedData[$encodedIndex++] = self::$lookUpBase64Alphabet[$val2 | ($k << 4)];
            $encodedData[$encodedIndex++] = self::$lookUpBase64Alphabet[$l << 2];
            $encodedData[$encodedIndex++] = self::PAD;
        }

        return implode('', $encodedData);
    }

    /**
     * 解码
     *
     * @param string $encoded Base64 编码的字符串
     * @return string|null 解码后的二进制数据
     */
    public static function decode(string $encoded): ?string
    {
        self::init();

        $base64Data = str_split($encoded);
        $len        = self::removeWhiteSpace($base64Data);

        if ($len % self::FOURBYTE !== 0) {
            return null;
        }

        $numberQuadruple = intdiv($len, self::FOURBYTE);

        if ($numberQuadruple === 0) {
            return "";
        }

        $decodedData  = [];
        $dataIndex    = 0;
        $encodedIndex = 0;

        for ($i = 0; $i < $numberQuadruple - 1; $i++) {
            $d1 = ord($base64Data[$dataIndex++]);
            $d2 = ord($base64Data[$dataIndex++]);
            $d3 = ord($base64Data[$dataIndex++]);
            $d4 = ord($base64Data[$dataIndex++]);

            if (!self::isData($d1) || !self::isData($d2) || !self::isData($d3) || !self::isData($d4)) {
                return null;
            }

            $b1 = self::$base64Alphabet[$d1];
            $b2 = self::$base64Alphabet[$d2];
            $b3 = self::$base64Alphabet[$d3];
            $b4 = self::$base64Alphabet[$d4];

            $decodedData[$encodedIndex++] = chr(($b1 << 2) | ($b2 >> 4));
            $decodedData[$encodedIndex++] = chr((($b2 & 0xf) << 4) | (($b3 >> 2) & 0xf));
            $decodedData[$encodedIndex++] = chr(($b3 << 6) | $b4);
        }

        $d1 = ord($base64Data[$dataIndex++]);
        $d2 = ord($base64Data[$dataIndex++]);

        if (!self::isData($d1) || !self::isData($d2)) {
            return null;
        }

        $b1 = self::$base64Alphabet[$d1];
        $b2 = self::$base64Alphabet[$d2];

        $d3 = ord($base64Data[$dataIndex++]);
        $d4 = ord($base64Data[$dataIndex++]);

        if (!self::isData($d3) || !self::isData($d4)) {
            if (self::isPad($d3) && self::isPad($d4)) {
                if (($b2 & 0xf) !== 0) {
                    return null;
                }
                return substr(implode('', $decodedData), 0, $i * 3 + 1) . chr(($b1 << 2) | ($b2 >> 4));
            } elseif (!self::isPad($d3) && self::isPad($d4)) {
                $b3 = self::$base64Alphabet[$d3];
                if (($b3 & 0x3) !== 0) {
                    return null;
                }
                $result = substr(implode('', $decodedData), 0, $i * 3 + 2);
                $result .= chr(($b1 << 2) | ($b2 >> 4));
                $result .= chr((($b2 & 0xf) << 4) | (($b3 >> 2) & 0xf));
                return $result;
            } else {
                return null;
            }
        } else {
            $b3                           = self::$base64Alphabet[$d3];
            $b4                           = self::$base64Alphabet[$d4];
            $decodedData[$encodedIndex++] = chr(($b1 << 2) | ($b2 >> 4));
            $decodedData[$encodedIndex++] = chr((($b2 & 0xf) << 4) | (($b3 >> 2) & 0xf));
            $decodedData[$encodedIndex++] = chr(($b3 << 6) | $b4);
        }

        return implode('', $decodedData);
    }

    private static function removeWhiteSpace(array &$data): int
    {
        if (empty($data)) {
            return 0;
        }

        $newSize = 0;
        $len     = count($data);
        for ($i = 0; $i < $len; $i++) {
            if (!self::isWhiteSpace(ord($data[$i]))) {
                $data[$newSize++] = $data[$i];
            }
        }
        return $newSize;
    }
}
