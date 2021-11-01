<?php

namespace Chess\Tests\Unit\ML\Supervised;

use Chess\Board;
use Chess\Combinatorics\RestrictedPermutationWithRepetition;
use Chess\HeuristicPicture;
use Chess\ML\Supervised\Regression\LinearCombinationLabeller;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Checkmate\Fool as FoolCheckmate;
use Chess\Tests\Sample\Checkmate\Scholar as ScholarCheckmate;
use Chess\Tests\Sample\Opening\Benoni\BenkoGambit;
use Chess\Tests\Sample\Opening\Sicilian\Open as ClosedSicilian;

class LinearCombinationLabellerTest extends AbstractUnitTestCase
{
    static $permutations;

    public static function setUpBeforeClass(): void
    {
        $dimensions = (new HeuristicPicture(''))->getDimensions();

        self::$permutations = (new RestrictedPermutationWithRepetition())
            ->get(
                [5, 8, 13, 21],
                count($dimensions),
                100
            );
    }

    /**
     * @test
     */
    public function start_balanced()
    {
        $board = new Board();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $expected = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->balance(end($balance));

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function w_e4_b_e5_balanced()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'e5'));

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $expected = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->balance(end($balance));

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function w_e4_b_Na6_balanced()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'Na6'));

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $expected = [
            Symbol::WHITE => 50,
            Symbol::BLACK => -6,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->balance(end($balance));

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function w_e4_b_Nc6_balanced()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'Nc6'));

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $expected = [
            Symbol::WHITE => 16,
            Symbol::BLACK => -16,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->balance(end($balance));

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function fool_checkmate_balanced()
    {
        $board = (new FoolCheckmate(new Board()))->play();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $expected = [
            Symbol::WHITE => 0,
            Symbol::BLACK => -54.5,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->balance(end($balance));

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function scholar_checkmate_balanced()
    {
        $board = (new ScholarCheckmate(new Board()))->play();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $expected = [
            Symbol::WHITE => 42,
            Symbol::BLACK => -25.36,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->balance(end($balance));

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function benko_gambit_balanced()
    {
        $board = (new BenkoGambit(new Board()))->play();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $expected = [
            Symbol::WHITE => 53.2,
            Symbol::BLACK => -0.88,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->balance(end($balance));

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function closed_sicilian_balanced()
    {
        $board = (new ClosedSicilian(new Board()))->play();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $expected = [
            Symbol::WHITE => 24.19,
            Symbol::BLACK => -9.89,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->balance(end($balance));

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function closed_sicilian_extract_permutation()
    {
        $board = (new ClosedSicilian(new Board()))->play();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $end = end($balance);

        $expected = [ 13, 8, 13, 8, 8, 8, 8, 8, 8, 8, 5, 5 ];

        $permutation = (new LinearCombinationLabeller(self::$permutations))
            ->extractPermutation($end, 2.95);

        $this->assertEquals($expected, $permutation);
    }

    /**
     * @test
     */
    public function closed_sicilian_calc_permutations()
    {
        $board = (new ClosedSicilian(new Board()))->play();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $end = end($balance);

        $expected = [
            'n' => 40,
            'eval' => -9.89,
            'weights' => [ 13, 5, 21, 5, 21, 5, 5, 5, 5, 5, 5, 5 ],
        ];

        $calc = (new LinearCombinationLabeller(self::$permutations))
            ->permute($end, Symbol::BLACK);

        $this->assertEquals($expected, $calc[0]);
    }
}
