<?php

namespace Chess\UciEngine\Details;

/**
 * UCI Limit for handling the analysis limit.
 */
class Limit
{
    /**
     * Time to search in milliseconds.
     *
     * @var int|null
     */
    private ?int $movetime;

    /**
     * Depth to search.
     *
     * @var int|null
     */

    private ?int $depth;

    /**
     * Nodes to search.
     *
     * @var int|null
     */
    private ?int $nodes;

    /**
     * Search for Mate in x moves. Not supported by all engines.
     *
     * @var int|null
     */
    private ?int $mate;

    /**
     * White has x msec left on the clock.
     *
     * @var int|null
     */
    private ?int $wtime;

    /**
     * Black has x msec left on the clock.
     *
     * @var int|null
     */
    private ?int $btime;

    /**
     * White increment per move in mseconds.
     *
     * @var int|null
     */
    private ?int $winc;

    /**
     * Black increment per move in mseconds.
     *
     * @var int|null
     */
    private ?int $binc;

    /**
     * Remaining moves to the next time control.
     *
     * @var int|null
     */
    private ?int $movestogo;

    public function __construct(
        $movetime = null,
        $depth = null,
        $nodes = null,
        $mate = null,
        $wtime = null,
        $btime = null,
        $winc = null,
        $binc = null,
        $movestogo = null
    ) {
        $this->movetime = $movetime;
        $this->depth = $depth;
        $this->nodes = $nodes;
        $this->mate = $mate;
        $this->wtime = $wtime;
        $this->btime = $btime;
        $this->winc = $winc;
        $this->binc = $binc;
        $this->movestogo = $movestogo;
    }

    public function getMovetime(): ?int
    {
        return $this->movetime;
    }

    public function setMovetime(int $movetime): Limit
    {
        $this->movetime = $movetime;

        return $this;
    }

    public function getDepth(): ?int
    {
        return $this->depth;
    }

    public function setDepth(int $depth): Limit
    {
        $this->depth = $depth;

        return $this;
    }

    public function getNodes(): ?int
    {
        return $this->nodes;
    }

    public function setNodes(int $nodes): Limit
    {
        $this->nodes = $nodes;

        return $this;
    }

    public function getMate(): ?int
    {
        return $this->mate;
    }

    public function setMate(int $mate): Limit
    {
        $this->mate = $mate;

        return $this;
    }

    public function getWtime(): ?int
    {
        return $this->wtime;
    }

    public function setWtime(int $wtime): Limit
    {
        $this->wtime = $wtime;

        return $this;
    }

    public function getBtime(): ?int
    {
        return $this->btime;
    }

    public function setBtime(int $btime): Limit
    {
        $this->btime = $btime;

        return $this;
    }

    public function getWinc(): ?int
    {
        return $this->winc;
    }

    public function setWinc(int $winc): Limit
    {
        $this->winc = $winc;

        return $this;
    }

    public function getBinc(): ?int
    {
        return $this->binc;
    }

    public function setBinc(int $binc): Limit
    {
        $this->binc = $binc;

        return $this;
    }

    public function getMovestogo(): ?int
    {
        return $this->movestogo;
    }

    public function setMovestogo(int $movestogo): Limit
    {
        $this->movestogo = $movestogo;

        return $this;
    }
}
