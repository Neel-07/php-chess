<?php

namespace PGNChess\PGN;

use PGNChess\Exception\UnknownNotationException;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Tag;

/**
 * Validation class.
 *
 * @author Jordi Bassagañas
 * @link https://programarivm.com
 * @license GPL
 */
class Validate
{
    /**
     * Validates a color.
     *
     * @param string $color
     * @return string if the color is valid
     * @throws UnknownNotationException
     */
    public static function color(string $color): string
    {
        if ($color !== Symbol::WHITE && $color !== Symbol::BLACK) {
            throw new UnknownNotationException("This is not a valid color: $color.");
        }

        return $color;
    }

    /**
     * Validates a square.
     *
     * @param string $square
     * @return string if the square is valid
     * @throws UnknownNotationException
     */
    public static function square(string $square): string
    {
        if (!preg_match('/^' . Symbol::SQUARE . '$/', $square)) {
            throw new UnknownNotationException("This square is not valid: $square.");
        }

        return $square;
    }

    /**
     * Validates a tag.
     *
     * @param string $tag
     * @return \stdClass if the tag is valid
     * @throws UnknownNotationException
     */
    public static function tag(string $tag): \stdClass
    {
        $isValid = false;
        foreach (Tag::all() as $key => $val) {
            if (preg_match('/^\[' . $val . ' \"(.*)\"\]$/', $tag)) {
                $isValid = true;
            }
        }

        if (!$isValid) {
            throw new UnknownNotationException("This tag is not valid: $tag.");
        }

        $exploded = explode(' "', $tag);

        $result = (object) [
            'name' => substr($exploded[0], 1),
            'value' => substr($exploded[1], 0, -2),
        ];

        return $result;
    }

    /**
     * Validates a PGN move.
     *
     * @param string $move
     * @return bool
     * @throws UnknownNotationException
     */
    public static function move(string $move): bool
    {
        switch (true) {
            case preg_match('/^' . Move::KING . '$/', $move):
                return true;
            case preg_match('/^' . Move::KING_CASTLING_SHORT . '$/', $move):
                return true;
            case preg_match('/^' . Move::KING_CASTLING_LONG . '$/', $move):
                return true;
            case preg_match('/^' . Move::KING_CAPTURES . '$/', $move):
                return true;
            case preg_match('/^' . Move::PIECE . '$/', $move):
                return true;
            case preg_match('/^' . Move::PIECE_CAPTURES . '$/', $move):
                return true;
            case preg_match('/^' . Move::KNIGHT . '$/', $move):
                return true;
            case preg_match('/^' . Move::KNIGHT_CAPTURES . '$/', $move):
                return true;
            case preg_match('/^' . Move::PAWN . '$/', $move):
                return true;
            case preg_match('/^' . Move::PAWN_CAPTURES . '$/', $move):
                return true;
            case preg_match('/^' . Move::PAWN_PROMOTES . '$/', $move):
                return true;
            case preg_match('/^' . Move::PAWN_CAPTURES_AND_PROMOTES . '$/', $move):
                return true;
            default:
                throw new UnknownNotationException("Unknown PGN notation.");
        }
    }

    /**
     * Validates a PGN movetext.
     *
     * @param string $movetext
     * @return mixed bool|string true if the movetext is valid; otherwise the filtered movetext
     */
    public static function movetext(string $text)
    {
        $movetext = (object) [
            'numbers' => [],
            'notations' => [],
        ];

        // remove comments
        $text = preg_replace("/\{[^)]+\}/", '', $text);
        // remove spaces between dots
        $text = preg_replace('/\s+\./', '.', $text);

        $moves = array_filter(explode(' ', $text));
        foreach ($moves as $move) {
            if (preg_match('/^[1-9][0-9]*\.(.*)$/', $move)) {
                $exploded = explode('.', $move);
                $movetext->numbers[] = $exploded[0];
                $movetext->notations[] = $exploded[1];
            } else {
                $movetext->notations[] = $move;
            }
        }

        $movetext->notations = array_values(array_filter($movetext->notations));

        $areConsecutiveNumbers = 1;

        for ($i = 0; $i < count($movetext->numbers); $i++) {
            $areConsecutiveNumbers *= (int) $movetext->numbers[$i] == $i + 1;
        }

        if (!$areConsecutiveNumbers) {
            return false;
        }

        foreach ($movetext->notations as $move) {
            if ($move !== Symbol::RESULT_WHITE_WINS &&
                $move !== Symbol::RESULT_BLACK_WINS &&
                $move !== Symbol::RESULT_DRAW &&
                $move !== Symbol::RESULT_UNKNOWN
               ) {
                try {
                    self::move($move);
                } catch (UnknownNotationException $e) {
                    return false;
                }
            }
        }

        /*
         * Filters the movetext.
         *
         *      Example:
         *
         *          1.e4  e5 2.  f4 exf4 3. Bc4 d5 4.Bxd5 Qh4+
         *
         *      is filtered this way:
         *
         *          1.e4 e5 2.f4 exf4 3.Bc4 d5 4.Bxd5 Qh4+
         */
        $filtered = '';
        for ($i = 0; $i < count($movetext->numbers); $i++) {
            $filtered .= $movetext->numbers[$i] . '.' . $movetext->notations[$i*2] . ' ';
            if (isset($movetext->notations[$i*2+1])) {
                $filtered .= $movetext->notations[$i*2+1] . ' ';
            }
        }

        return trim($filtered);
    }

    /**
     * Validates a set of tags.
     *
     * @param array $tags
     * @return bool true if the tags are valid; otherwise false
     */
    public static function tags(array $tags): bool
    {
        $keys = array_keys($tags);

        return !array_diff(Tag::mandatory(), $keys);
    }
}
