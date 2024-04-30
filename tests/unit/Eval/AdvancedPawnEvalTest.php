<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoardFactory;
use Chess\Eval\AdvancedPawnEval;
use Chess\Tests\AbstractUnitTestCase;

class AdvancedPawnEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function b6()
    {
        $expectedResult = [
            'w' => ['b6'],
            'b' => [],
        ];

        $expectedExplanation = [
            "White has a slight advanced pawn advantage.",
        ];

        $expectedElaboration = [
            "b6 is an advanced pawn.",
        ];

        $board = FenToBoardFactory::create('8/1p6/1P1K4/pk6/8/8/5B2/8 b - - 3 56');

        $advancedPawnEval = new AdvancedPawnEval($board);

        $this->assertSame($expectedResult, $advancedPawnEval->getResult());
        $this->assertSame($expectedExplanation, $advancedPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $advancedPawnEval->getElaboration());
    }

    /**
     * @test
     */
    public function e6_c3_e2()
    {
        $expectedResult = [
            'w' => ['e6'],
            'b' => ['c3', 'e2'],
        ];

        $expectedExplanation = [
            "Black has a slight advanced pawn advantage.",
        ];

        $expectedElaboration = [
            "e6, c3 and e2 are advanced pawns.",
        ];

        $board = FenToBoardFactory::create('8/8/4P3/4K3/8/2p2k2/4p3/8 w - - 0 1');

        $advancedPawnEval = new AdvancedPawnEval($board);

        $this->assertSame($expectedResult, $advancedPawnEval->getResult());
        $this->assertSame($expectedExplanation, $advancedPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $advancedPawnEval->getElaboration());
    }
}
