<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\TacticsEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class TacticsEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $result = (new TacticsEval(new Board()))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function e4_d5()
    {
        $expected = [
            'w' => 0,
            'b' => 1,
        ];

        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'd5');

        $tacticsEval = (new TacticsEval($board))->getResult();

        $this->assertSame($expected, $tacticsEval);
    }

    /**
     * @test
     */
    public function B56()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $B56 = file_get_contents(self::DATA_FOLDER.'/sample/B56.pgn');

        $board = (new SanPlay($B56))->validate()->getBoard();

        $result = (new TacticsEval($board))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function B25()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $B25 = file_get_contents(self::DATA_FOLDER.'/sample/B25.pgn');

        $board = (new SanPlay($B25))->validate()->getBoard();

        $result = (new TacticsEval($board))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bb5_a6_Nxe5()
    {
        $expected = [
            'w' => 0,
            'b' => 6.53,
        ];

        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Bb5');
        $board->play('b', 'a6');
        $board->play('w', 'Nxe5');

        $tacticsEval = (new TacticsEval($board))->getResult();

        $this->assertSame($expected, $tacticsEval);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_a3_Nxe4_d3()
    {
        $expected = [
            'w' => 4.2,
            'b' => 0,
        ];

        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'a3');
        $board->play('b', 'Nxe4');
        $board->play('w', 'd3');

        $tacticsEval = (new TacticsEval($board))->getResult();

        $this->assertSame($expected, $tacticsEval);
    }
}
