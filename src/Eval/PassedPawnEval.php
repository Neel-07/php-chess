<?php

namespace Chess\Eval;

use Chess\Piece\P;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class PassedPawnEval extends AbstractEval
{
    const NAME = 'Passed pawn';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::P && $this->isPassedPawn($piece)) {
                $this->result[$piece->getColor()][] = $piece->getSq();
            }
        }

        $this->explain($this->result);
    }

    private function isPassedPawn(P $pawn): bool
    {
        $leftFile = chr(ord($pawn->getSqFile()) - 1);
        $rightFile = chr(ord($pawn->getSqFile()) + 1);

        foreach ([$leftFile, $pawn->getSqFile(), $rightFile] as $file) {
            if ($file >= 'a' && $file <= 'h') {
                $rank = (int) $pawn->getSqRank();
                if ($pawn->getColor() === Color::W) {
                    for ($i = $rank + 1; $i <= $this->board->getSize()['ranks'] - 1; $i++) {
                        if ($piece = $this->board->getPieceBySq($file.$i)) {
                            if ($piece->getId() === Piece::P && $piece->getColor() !== $pawn->getColor()) {
                                return false;
                            }
                        }
                    }
                } else {
                    for ($i = $rank - 1; $i >= 2; $i--) {
                        if ($piece = $this->board->getPieceBySq($file.$i)) {
                            if ($piece->getId() === Piece::P && $piece->getColor() !== $pawn->getColor()) {
                                return false;
                            }
                        }
                    }
                }
            }
        }

        return true;
    }

    private function explain(array $result): void
    {
        $singular = mb_strtolower('a ' . self::NAME);
        $plural = mb_strtolower(self::NAME.'s');

        $this->shorten($result, $singular, $plural);
    }
}
