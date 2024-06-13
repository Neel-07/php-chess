<?php

namespace Chess\Piece;

use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

class PieceArray
{
    protected array $array;

    protected Square $square;

    private CastlingRule $castlingRule;

    public function __construct(array $array, Square $square, CastlingRule $castlingRule)
    {
        $this->square = $square;

        $this->castlingRule = $castlingRule;

        foreach ($array as $i => $row) {
            $file = 'a';
            $rank = $i + 1;
            foreach ($row as $j => $item) {
                $char = trim($item);
                if (ctype_lower($char)) {
                    $char = strtoupper($char);
                    $this->push(Color::B, $char, $file . $rank);
                } elseif (ctype_upper($char)) {
                    $this->push(Color::W, $char, $file . $rank);
                }
                $file = chr(ord($file) + 1);
            }
        }
    }

    public function getArray(): array
    {
        return $this->array;
    }

    private function push(string $color, string $id, string $sq): void
    {
        if ($id === Piece::R) {
            if (
                $color === Color::B &&
                $sq === $this->castlingRule->getRule()[Color::B][Piece::R][Castle::LONG]['sq']['current']
            ) {
                $this->array[] = new R($color, $sq, $this->square, RType::CASTLE_LONG);
            } elseif (
                $color === Color::B &&
                $sq === $this->castlingRule->getRule()[Color::B][Piece::R][Castle::SHORT]['sq']['current']
            ) {
                $this->array[] = new R($color, $sq, $this->square, RType::CASTLE_SHORT);
            } elseif (
                $color === Color::W &&
                $sq === $this->castlingRule->getRule()[Color::B][Piece::R][Castle::LONG]['sq']['current']
            ) {
                $this->array[] = new R($color, $sq, $this->square, RType::CASTLE_LONG);
            } elseif (
                $color === Color::W &&
                $sq === $this->castlingRule->getRule()[Color::W][Piece::R][Castle::SHORT]['sq']['current']
            ) {
                $this->array[] = new R($color, $sq, $this->square, RType::CASTLE_SHORT);
            } else { // it doesn't matter which RType is assigned
                $this->array[] = new R($color, $sq, $this->square, RType::PROMOTED);
            }
        } else {
            $className = "\\Chess\\Piece\\$id";
            $this->array[] = new $className($color, $sq, $this->square);
        }
    }
}
