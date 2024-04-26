<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\FenToBoardFactory;
use Chess\Play\SanPlay;
use Chess\Tutor\FenExplanation;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\Board as CapablancaBoard;

class FenExplanationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function A08()
    {
        $expected = [
            "Black has a significant control of the center.",
            "The white pieces are so better connected.",
            "Black has a significant space advantage.",
        ];

        $A08 = file_get_contents(self::DATA_FOLDER.'/sample/A08.pgn');
        $board = (new SanPlay($A08))->validate()->getBoard();

        $paragraph = (new FenExplanation($board))->getParagraph();

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function evaluated_endgame()
    {
        $expected = [
            "White has a significant material advantage.",
            "White has a significant control of the center.",
            "The white pieces are somewhat better connected.",
            "The white player is pressuring a little bit more squares than its opponent.",
            "White has a kind of absolute pin advantage.",
            "White has the bishop pair.",
            "Overall, 6 heuristic evaluation features are favoring White while 0 are favoring Black.",
        ];

        $board = FenToBoardFactory::create('8/5k2/4n3/8/8/1BK5/1B6/8 w - - 0 1');

        $paragraph = (new FenExplanation($board, $isEvaluated = true))->getParagraph();

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function advanced_pawn()
    {
        $expected = [
            "Black has a tiny material advantage.",
            "The black pieces are slightly better connected.",
            "White has a tiny space advantage.",
            "Black has a tiny passed pawn advantage.",
            "Black has a tiny advanced pawn advantage.",
            "Black has a tiny far advanced pawn advantage.",
            "White has a tiny isolated pawn advantage.",
        ];

        $board = FenToBoardFactory::create('8/8/8/8/8/8/7p/K6k b - - 0 1');

        $paragraph = (new FenExplanation($board))->getParagraph();

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function far_advanced_pawn()
    {
        $expected = [
            "Black has a tiny material advantage.",
            "The black pieces are slightly better connected.",
            "White has a tiny space advantage.",
            "Black has a tiny passed pawn advantage.",
            "Black has a tiny advanced pawn advantage.",
            "Black has a tiny far advanced pawn advantage.",
            "White has a tiny isolated pawn advantage.",
        ];

        $board = FenToBoardFactory::create('8/8/8/8/8/8/7p/K6k b - - 0 1');

        $paragraph = (new FenExplanation($board))->getParagraph();

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function backward_pawn()
    {
        $expected = [
            "White has a tiny material advantage.",
            "White is just controlling the center.",
            "White has a tiny space advantage.",
            "White has a tiny passed pawn advantage.",
            "Black has some backward pawn advantage.",
        ];

        $board = FenToBoardFactory::create('k7/7p/6p1/5p2/1PP5/P2P4/8/7K w - - 0 1');

        $paragraph = (new FenExplanation($board))->getParagraph();

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function isolated_pawn()
    {
        $expected = [
            "White has a significant material advantage.",
            "White is just controlling the center.",
            "White has a tiny space advantage.",
            "White has some passed pawn advantage.",
            "Black has a significant isolated pawn advantage.",
        ];

        $board = FenToBoardFactory::create('k7/5p2/8/8/P1P1P1P1/8/8/K7 w - - 0 1');

        $paragraph = (new FenExplanation($board))->getParagraph();

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function capablanca_f4()
    {
        $expected = [
            "White is just controlling the center.",
            "The black pieces are significantly better connected.",
            "White has a total space advantage.",
            "The white player is pressuring a little bit more squares than its opponent.",
            "Overall, 3 heuristic evaluation features are favoring White while 1 is favoring Black.",
        ];

        $board = FenToBoardFactory::create(
            'rnabqkbcnr/pppppppppp/10/10/5P4/10/PPPPP1PPPP/RNABQKBCNR b KQkq f3',
            new CapablancaBoard()
        );

        $paragraph = (new FenExplanation($board, $isEvaluated = true))->getParagraph();

        $this->assertSame($expected, $paragraph);
    }
}
