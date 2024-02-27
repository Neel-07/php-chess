<?php

namespace Chess\Tests\Unit\UciEngine;

use Chess\Grandmaster;
use Chess\Tests\AbstractUnitTestCase;
use Chess\UciEngine\UciEngine;
use Chess\UciEngine\Details\Limit;
use Chess\Variant\Classical\Board;

class UciEngineTest extends AbstractUnitTestCase
{
    const FILEPATH = __DIR__.'/../../data/json/players.json';

    /**
     * @test
     */
    public function uci_engine_stockfish()
    {
        $stockfish = new UciEngine('/usr/games/stockfish');

        $this->assertTrue(is_a($stockfish, UciEngine::class));
    }

    /**
     * @test
     */
    public function get_options()
    {
        $stockfish = new UciEngine('/usr/games/stockfish');

        $expected = [
            'Debug Log File',
            'Threads',
            'Hash',
            'Clear Hash',
            'Ponder',
            'MultiPV',
            'Skill Level',
            'Move Overhead',
            'Slow Mover',
            'nodestime',
            'UCI_Chess960',
            'UCI_AnalyseMode',
            'UCI_LimitStrength',
            'UCI_Elo',
            'UCI_ShowWDL',
            'SyzygyPath',
            'SyzygyProbeDepth',
            'Syzygy50MoveRule',
            'SyzygyProbeLimit',
            'Use NNUE',
            'EvalFile',
        ];

        $options = $stockfish->getOptions();

        $this->assertSame($expected, array_keys($options));
    }

    /**
     * @test
     */
    public function analysis_e4_limit()
    {
        $board = new Board();
        $board->play('w', 'e4');

        $limit = (new Limit())->setDepth(8);
        $stockfish = new UciEngine('/usr/games/stockfish');
        $analysis = $stockfish->analysis($board, $limit);

        $expected = 'c7c5';

        $this->assertSame($expected, $analysis['bestmove']);
    }

    /**
     * @test
     */
    public function analysis_e4_limit_set_options()
    {
        $board = new Board();
        $board->play('w', 'e4');

        $limit = (new Limit())->setDepth(8);

        $stockfish = (new UciEngine('/usr/games/stockfish'))
            ->setOption('Skill Level', 20)
            ->setOption('UCI_Elo', 1500);

        $analysis = $stockfish->analysis($board, $limit);

        $expected = 'c7c5';

        $this->assertSame($expected, $analysis['bestmove']);
    }

    /**
     * @test
     */
    public function play_against_itself()
    {
        $skillLevel = 14;
        $skillLevelOffset = 3;

        $depth = 6;
        $depthOffset = 2;

        $stockfish = (new UciEngine('/usr/games/stockfish'))
            ->setOption('Skill Level', $skillLevel + rand(0, $skillLevelOffset));

        $board = new Board();
        $move = (new Grandmaster(self::FILEPATH))->move($board);

        do {
            $limit = (new Limit())->setDepth($depth + rand(0, $depthOffset));
            $analysis = $stockfish
                ->setOption('Skill Level', $skillLevel + rand(0, $skillLevelOffset))
                ->analysis($board, $limit);
            $this->assertTrue($board->playLan($board->getTurn(), $analysis['bestmove']));
        } while (
            !$board->isMate() &&
            !$board->isStalemate() &&
            count($board->getHistory()) < 250
        );
    }
}
