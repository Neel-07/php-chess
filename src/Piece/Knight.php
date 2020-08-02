<?php

namespace PGNChess\Piece;

use PGNChess\Exception\UnknownNotationException;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validate;
use PGNChess\Piece\AbstractPiece;

/**
 * Knight class.
 *
 * @author Jordi Bassagañas
 * @link https://programarivm.com
 * @license GPL
 */
class Knight extends AbstractPiece
{
    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     */
    public function __construct(string $color, string $square)
    {
        parent::__construct($color, $square, Symbol::KNIGHT);

        $this->scope = (object)[
            'jumps' => []
        ];

        $this->scope();
    }

    /**
     * Calculates the knight's scope.
     */
    protected function scope(): void
    {
        try {
            $file = chr(ord($this->position[0]) - 1);
            $rank = (int)$this->position[1] + 2;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position[0]) - 2);
            $rank = (int)$this->position[1] + 1;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position[0]) - 2);
            $rank = (int)$this->position[1] - 1;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position[0]) - 1);
            $rank = (int)$this->position[1] - 2;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position[0]) + 1);
            $rank = (int)$this->position[1] - 2;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {

            $file = chr(ord($this->position[0]) + 2);
            $rank = (int)$this->position[1] - 1;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position[0]) + 2);
            $rank = (int)$this->position[1] + 1;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position[0]) + 1);
            $rank = (int)$this->position[1] + 2;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

    }

    public function getLegalMoves(): array
    {
        $moves = [];

        foreach ($this->scope->jumps as $square) {

            switch(true) {
                case null != $this->getMove() && !$this->getMove()->isCapture:
                    if (in_array($square, $this->boardStatus->squares->free)) {
                        $moves[] = $square;
                    } elseif (in_array($square, $this->boardStatus->squares->used->{$this->getOppColor()})) {
                        $moves[] = $square;
                    }
                    break;

                case null != $this->getMove() && $this->getMove()->isCapture:
                    if (in_array($square, $this->boardStatus->squares->used->{$this->getOppColor()})) {
                        $moves[] = $square;
                    }
                    break;

                case null == $this->getMove():
                    if (in_array($square, $this->boardStatus->squares->free)) {
                        $moves[] = $square;
                    } elseif (in_array($square, $this->boardStatus->squares->used->{$this->getOppColor()})) {
                        $moves[] = $square;
                    }
                    break;
            }
        }

        return $moves;
    }
}
