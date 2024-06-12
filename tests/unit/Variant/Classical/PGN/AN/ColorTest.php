<?php

namespace Chess\Tests\Unit\Variant\Classical\PGN\AN;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\AN\Color;

class ColorTest extends AbstractUnitTestCase
{
    static private $color;

    public static function setUpBeforeClass(): void
    {
        self::$color = new Color();
    }

    /**
     * @test
     */
    public function green_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        self::$color->validate('green');
    }

    /**
     * @test
     */
    public function validate_w()
    {
        $this->assertSame(Color::W, self::$color->validate('w'));
    }

    /**
     * @test
     */
    public function validate_b()
    {
        $this->assertSame(Color::B, self::$color->validate('b'));
    }

    /**
     * @test
     */
    public function opp_w()
    {
        $this->assertSame(Color::B, self::$color->opp('w'));
    }

    /**
     * @test
     */
    public function values()
    {
        $expected = [
            'W' => 'w',
            'B' => 'b',
        ];

        $this->assertSame($expected, self::$color->values());
    }
}
