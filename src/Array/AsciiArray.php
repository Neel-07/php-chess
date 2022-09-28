<?php

namespace Chess\Array;

use Chess\Variant\Classical\FEN\Field\CastlingAbility;
use Chess\Variant\Classical\Board;

/**
 * Ascii array.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class AsciiArray extends AbstractArray
{
    /**
     * Constructor.
     *
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * Returns a Chess\Board object.
     *
     * @param string $turn
     * @param string $castlingAbility
     * @return \Chess\Variant\Classical\Board
     */
    public function toBoard(
        string $turn,
        $castlingAbility = CastlingAbility::NEITHER
    ): Board
    {
        $pieces = (new PieceArray($this->array))->getArray();
        $board = (new Board($pieces, $castlingAbility))->setTurn($turn);

        return $board;
    }

    /**
     * Sets an element in the array using algebraic notation to identify the square.
     *
     * @param string $elem
     * @param string $sq
     * @return \Chess\Array\AsciiArray
     */
    public function setElem(string $elem, string $sq): AsciiArray
    {
        $index = self::fromAlgebraicToIndex($sq);
        $this->array[$index[0]][$index[1]] = $elem;

        return $this;
    }

    /**
     * Returns the array indexes of the given square.
     *
     * @param string $sq
     * @return array
     */
    public static function fromAlgebraicToIndex(string $sq): array
    {
        $j = ord($sq[0]) - 97;
        if (strlen($sq) === 2) {
            $i = $sq[1] - 1;
        } elseif (strlen($sq) === 3) {
            $i = intval($sq[1].$sq[2]) - 1;
        }

        return [
            $i,
            $j,
        ];
    }
}
