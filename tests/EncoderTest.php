<?php

declare(strict_types=1);

namespace Afonso\Base24;

class EncoderTest extends \PHPUnit\Framework\TestCase
{
    private $encoder;

    protected function setUp(): void
    {
        $this->encoder = new Encoder();
    }

    /**
     * @dataProvider getTestData
     */
    public function testEncode($decoded, $encoded): void
    {
        $this->assertSame(strtoupper($encoded), $this->encoder->encode($decoded));
    }

    /**
     * @dataProvider getTestData
     */
    public function testDecode($decoded, $encoded): void
    {
        $this->assertSame($decoded, $this->encoder->decode($encoded));
    }

    public function testEncodeThrowsExceptionIfInputLengthIsNotMultipleOf4(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Input to encode must have a length multiple of 4');
        $this->encoder->encode([0]);
    }

    public function testDecodeThrowsExceptionIfInputLengthIsNotMultipleOf7(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Input to decode must have a length multiple of 7');
        $this->encoder->decode('A');
    }

    public function getTestData(): array
    {
        $mappings = [
            ['00000000', 'ZZZZZZZ'],
            ['000000000000000000000000', 'ZZZZZZZZZZZZZZZZZZZZZ'],
            ['00000001', 'ZZZZZZA'],
            ['000000010000000100000001', 'ZZZZZZAZZZZZZAZZZZZZA'],
            ['00000010', 'ZZZZZZP'],
            ['00000030', 'ZZZZZCZ'],
            ['88553311', '5YEATXA'],
            ['FFFFFFFF', 'X5GGBH7'],
            ['FFFFFFFFFFFFFFFFFFFFFFFF', 'X5GGBH7X5GGBH7X5GGBH7'],
            ['FFFFFFFFFFFFFFFFFFFFFFFF', 'x5ggbh7x5ggbh7x5ggbh7'],
            ['1234567887654321', 'A64KHWZ5WEPAGG'],
            ['1234567887654321', 'a64khwz5wepagg'],
            ['FF0001FF001101FF01023399', 'XGES63FZZ247C7ZC2ZA6G'],
            ['FF0001FF001101FF01023399', 'xges63fzz247c7zc2za6g'],
            ['25896984125478546598563251452658', '2FC28KTA66WRST4XAHRRCF237S8Z'],
            ['25896984125478546598563251452658', '2fc28kta66wrst4xahrrcf237s8z']
        ];

        $testDarta = [];

        foreach ($mappings as $mapping) {
            $decoded = $mapping[0];
            $encoded = $mapping[1];

            $testData[] = [array_map('hexdec', str_split($decoded, 2)), $encoded];
        }

        return $testData;
    }
}
