<?php
namespace PGNChess;

use DeepCopy\DeepCopy;
use PGNChess\Board;
use PGNChess\Exception\CastlingException;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;

/**
 * Analyze class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Analyze
{
    /**
     * Validates the board's castling object.
     *
     * @return boolean
     */
    public static function castling($board)
    {
        $castlingArr = (array)$board->getCastling();

        if (!empty($castlingArr)) {
            !isset($castlingArr[Symbol::WHITE]) ?: $wCastlingArr = (array)$castlingArr[Symbol::WHITE];
            !isset($castlingArr[Symbol::BLACK]) ?: $bCastlingArr = (array)$castlingArr[Symbol::BLACK];
        }

        // check castling object

        if (empty($castlingArr)) {
            throw new CastlingException("The castling object is empty.");
        }

        if (count($castlingArr) !== 2) {
            throw new CastlingException("The castling object must have two properties.");
        }

        // check white's castling object

        if (empty($wCastlingArr)) {
            throw new CastlingException("White's castling object is not set.");
        }

        if (count($wCastlingArr) !== 3) {
            throw new CastlingException("White's castling object must have three properties.");
        }

        if (!isset($wCastlingArr['castled'])) {
            throw new CastlingException("The castled property is not set.");
        }

        if (!isset($wCastlingArr[Symbol::CASTLING_SHORT])) {
            throw new CastlingException("White's castling short property is not set.");
        }

        if (!isset($wCastlingArr[Symbol::CASTLING_LONG])) {
            throw new CastlingException("White's castling long property is not set.");
        }

        // check black's castling object

        if (empty($bCastlingArr)) {
            throw new CastlingException("Black's castling object is not set.");
        }

        if (count($bCastlingArr) !== 3) {
            throw new CastlingException("Black's castling object must have three properties.");
        }

        if (!isset($bCastlingArr['castled'])) {
            throw new CastlingException("Black's castled property is not set.");
        }

        if (!isset($bCastlingArr[Symbol::CASTLING_SHORT])) {
            throw new CastlingException("Black's castling short property is not set.");
        }

        if (!isset($bCastlingArr[Symbol::CASTLING_LONG])) {
            throw new CastlingException("Black's castling long property is not set.");
        }

        // check castling object's info

        $wKing = $board->getPieceByPosition('e1');
        $wRookA1 = $board->getPieceByPosition('a1');
        $wRookH1 = $board->getPieceByPosition('h1');

        $bKing = $board->getPieceByPosition('e8');
        $bRookA8 = $board->getPieceByPosition('a8');
        $bRookH8 = $board->getPieceByPosition('h8');

        // check white's castling info

        if ($board->getCastling()->{Symbol::WHITE}->{Symbol::CASTLING_LONG} === true &&
            !(isset($wKing) && $wKing->getIdentity() === Symbol::KING && $wKing->getColor() === Symbol::WHITE)) {
                throw new CastlingException("White's king was already moved.");
        }

        if ($board->getCastling()->{Symbol::WHITE}->{Symbol::CASTLING_SHORT} === true &&
            !(isset($wKing) && $wKing->getIdentity() === Symbol::KING && $wKing->getColor() === Symbol::WHITE)) {
                throw new CastlingException("White's king was already moved.");
        }

        if ($board->getCastling()->{Symbol::WHITE}->{Symbol::CASTLING_LONG} === true &&
            !(isset($wRookA1) && $wRookA1->getIdentity() === Symbol::ROOK && $wRookA1->getColor() === Symbol::WHITE)) {
                throw new CastlingException("White's a1 rook was already moved.");
        }

        if ($board->getCastling()->{Symbol::WHITE}->{Symbol::CASTLING_SHORT} === true &&
            !(isset($wRookH1) && $wRookH1->getIdentity() === Symbol::ROOK && $wRookH1->getColor() === Symbol::WHITE)) {
                throw new CastlingException("White's h1 rook was already moved.");
        }

        // check black's castling info

        if ($board->getCastling()->{Symbol::BLACK}->{Symbol::CASTLING_LONG} === true &&
            !(isset($bKing) && $bKing->getIdentity() === Symbol::KING && $bKing->getColor() === Symbol::BLACK)) {
                throw new CastlingException("Black's king was already moved.");
        }

        if ($board->getCastling()->{Symbol::BLACK}->{Symbol::CASTLING_SHORT} === true &&
            !(isset($bKing) && $bKing->getIdentity() === Symbol::KING && $bKing->getColor() === Symbol::BLACK)) {
                throw new CastlingException("Black's king was already moved.");
        }

        if ($board->getCastling()->{Symbol::BLACK}->{Symbol::CASTLING_LONG} === true &&
            !(isset($bRookA8) && $bRookA8->getIdentity() === Symbol::ROOK && $bRookA8->getColor() === Symbol::BLACK)) {
                throw new CastlingException("Black's a8 rook was already moved.");
        }

        if ($board->getCastling()->{Symbol::BLACK}->{Symbol::CASTLING_SHORT} === true &&
            !(isset($bRookH8) && $bRookH8->getIdentity() === Symbol::ROOK && $bRookH8->getColor() === Symbol::BLACK)) {
                throw new CastlingException("Black's h8 rook was already moved.");
        }

        return true;
    }

    /**
     * Computes whether the current player is checked.
     *
     * @param PGNChess\Game\Board $board
     * @return boolean
     */
    public static function check($board)
    {
        $king = $board->getPiece($board->getTurn(), Symbol::KING);

        if (in_array($king->getPosition()->current, $board->getControl()->attack->{$king->getOppositeColor()})) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Computes whether the current player is checkmated.
     *
     * @param PGNChess\Game\Board $board
     * @return boolean
     */
    public static function mate($board)
    {
        if (self::check($board)) {
            
            $moves = 0;
            $pieces = $board->getPiecesByColor($board->getTurn());
            
            foreach ($pieces as $piece) {
                
                foreach($piece->getLegalMoves() as $square) {
                    
                    $deepCopy = new DeepCopy();
                    $that = $deepCopy->copy($board);
                    
                    switch($piece->getIdentity()) {
                        
                        case Symbol::KING:
                            if (in_array($square, $board->getSquares()->used->{$piece->getOppositeColor()})) {
                                $moves += (int) $that->play(
                                    Convert::toObject($board->getTurn(),
                                    Symbol::KING . "x$square")
                                );
                            }
                            elseif (!in_array($square, $board->getControl()->space->{$piece->getOppositeColor()})) {
                                $moves += (int) $that->play(
                                    Convert::toObject($board->getTurn(),
                                    Symbol::KING . $square)
                                );
                            }
                            break;
                            
                        case Symbol::PAWN:
                            if (in_array($square, $board->getSquares()->used->{$piece->getOppositeColor()})) {
                                $moves += (int) $that->play(
                                    Convert::toObject($board->getTurn(),
                                    $piece->getFile() . "x$square")
                                );
                            } else {
                                $moves += (int) $that->play(
                                    Convert::toObject($board->getTurn(),
                                    $square)
                                );
                            }
                            break;
                            
                        default:
                            if (in_array($square, $board->getSquares()->used->{$piece->getOppositeColor()})) {
                                $moves += (int) $that->play(
                                    Convert::toObject($board->getTurn(),
                                    $piece->getIdentity() . "x$square")
                                );
                            } else {
                                $moves += (int) $that->play(
                                    Convert::toObject($board->getTurn(),
                                    $piece->getIdentity() . $square)
                                );                                
                            }
                            break;
                    }
                }
            }
            
            if ($moves === 0) {
                return true;
            } else {
                return false;
            }
        }
    }
}
