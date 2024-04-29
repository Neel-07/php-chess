<?php

namespace Chess\Play;

use Chess\Exception\PlayException;
use Chess\Movetext\SanMovetext;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\Move;

/**
 * Standard Algebraic Notation.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class SanPlay extends AbstractPlay
{
    /**
     * SAN movetext.
     *
     * @var array
     */
    protected SanMovetext $sanMovetext;

    /**
     * Constructor.
     *
     * @param string $movetext
     * @param Board $board
     */
    public function __construct(string $movetext, Board $board = null)
    {
        $this->initialBoard = $board ?? new Board();
        $this->board = unserialize(serialize($board)) ?? new Board();
        $this->fen = [$this->board->toFen()];
        $this->sanMovetext = new SanMovetext($this->board->getMove(), $movetext);
        $this->sanMovetext->validate();
    }

    /**
     * Returns the SAN movetext.
     *
     * @return SanMovetext
     */
    public function getSanMovetext(): SanMovetext
    {
        return $this->sanMovetext;
    }

    /**
     * Semantically validated movetext.
     *
     * Makes the moves in a SAN movetext.
     *
     * @throws \Chess\Exception\PlayException
     * @return \Chess\Play\SanPlay
     */
    public function validate(): SanPlay
    {
        foreach ($this->sanMovetext->getMoves() as $key => $val) {
            if ($val !== Move::ELLIPSIS) {
                if (!$this->board->play($this->board->getTurn(), $val)) {
                    throw new PlayException();
                }
                $this->fen[] = $this->board->toFen();
            }
        }

        return $this;
    }
}
