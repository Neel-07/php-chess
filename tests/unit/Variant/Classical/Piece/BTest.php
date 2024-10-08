<?php

namespace Chess\Tests\Unit\Variant\Classical\Piece;

use Chess\FenToBoardFactory;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Piece\B;

class BTest extends AbstractUnitTestCase
{
    static private Square $square;

    public static function setUpBeforeClass(): void
    {
        self::$square = new Square();
    }

    /**
     * @test
     */
    public function mobility_a2()
    {
        $bishop = new B('w', 'a2', self::$square);
        $mobility = [
            0 => [],
            1 => ['b3', 'c4', 'd5', 'e6', 'f7', 'g8'],
            2 => [],
            3 => ['b1'],
        ];

        $this->assertEquals($mobility, $bishop->mobility);
    }

    /**
     * @test
     */
    public function mobility_d5()
    {
        $bishop = new B('w', 'd5', self::$square);
        $mobility = [
            0 => ['c6', 'b7', 'a8'],
            1 => ['e6', 'f7', 'g8'],
            2 => ['c4', 'b3', 'a2'],
            3 => ['e4', 'f3', 'g2', 'h1'],
        ];

        $this->assertEquals($mobility, $bishop->mobility);
    }

    /**
     * @test
     */
    public function mobility_a8()
    {
        $bishop = new B('w', 'a8', self::$square);
        $mobility = [
            0 => [],
            1 => [],
            2 => [],
            3 => ['b7', 'c6', 'd5', 'e4', 'f3', 'g2', 'h1'],
        ];

        $this->assertEquals($mobility, $bishop->mobility);
    }

    /**
     * @test
     */
    public function line_of_attack_e4()
    {
        $board = FenToBoardFactory::create('8/1k6/8/8/4B3/4K3/8/8 b - -');

        $expected = [
            'c6',
            'd5',
        ];

        $this->assertEquals($expected, $board->pieceBySq('e4')->lineOfAttack());
    }

    /**
     * @test
     */
    public function line_of_attack_e5()
    {
        $board = FenToBoardFactory::create('7k/8/8/4B3/8/4K3/8/8 b - -');

        $expected = [
            'g7',
            'f6',
        ];

        $this->assertEquals($expected, $board->pieceBySq('e5')->lineOfAttack());
    }

    /**
     * @test
     */
    public function line_of_attack_d6()
    {
        $board = FenToBoardFactory::create('8/8/3B4/8/8/4K3/7k/8 b - -');

        $expected = [
            'g3',
            'f4',
            'e5',
        ];

        $this->assertEquals($expected, $board->pieceBySq('d6')->lineOfAttack());
    }

    /**
     * @test
     */
    public function line_of_attack_g6()
    {
        $board = FenToBoardFactory::create('8/8/6B1/8/8/4K3/8/1k6 b - -');

        $expected = [
            'c2',
            'd3',
            'e4',
            'f5',
        ];

        $this->assertEquals($expected, $board->pieceBySq('g6')->lineOfAttack());
    }
}
