## PGN Chess for PHP

![PGN Chess](/resources/chess-move.jpg?raw=true)

This is a simple, friendly, and powerful PGN (Portable Game Notation) library for running chess games from within PHP applications. PGN Chess is a PHP chess engine that can be used in chess applications and chess algorithms because it understands the rules of chess; is capable of validating and playing PGN notated games.

PGN Chess comes to the rescue in the following scenarios:

- Develop APIs on the server side for validating chess games
- Create funny, random games for fun
- Analyze games of chess
- Validate games
- Build chess-related web apps
- Play chess moves

### 1. Install

Via composer:

    $ composer require programarivm/pgn-chess

### 2. Instantiation

Instantiate a game and play PGN moves converted into PHP objects:

```php
<?php
use PGNChess\Game;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;

$game = new Game;

$isLegalMove = $game->play(Convert::toObject(Symbol::WHITE, 'e4');
```
The call to the `$board->play` method returns `true` or `false` depending on whether or not a chess move can be run on the board. It is up to you to process the result accordingly.

### 3. Game methods

#### 3.1. `isChecked()`

In order to know whether or not a move checks the opponent's king.

```php
$isChecked = $game->isChecked(Symbol::WHITE);
```

#### 3.2. `isMated()`

Find out if a player is checkmated.

```php
$isMated = $game->isMated(Symbol::WHITE);
```

#### 3.3. `status()`

Gets the current status of the game.

    $status = $game->status();

`$status` is a PHP object containing useful information about the game being played.

| Property       | Description                                |
|----------------|--------------------------------------------|
| `turn`         | The current player's turn                  |
| `squares`      | Free/used squares on the board             |
| `control`      | Squares controlled by both players         |
| `castling`     | The castling status of the two kings       |
| `previousMove` | The previous move                          |

The following sequence of moves:

```php
<?php
use PGNChess\Game;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;

$game = new Game;

$game->play(Convert::toObject(Symbol::WHITE, 'd4'));
$game->play(Convert::toObject(Symbol::BLACK, 'c6'));
$game->play(Convert::toObject(Symbol::WHITE, 'Bf4'));
$game->play(Convert::toObject(Symbol::BLACK, 'd5'));
$game->play(Convert::toObject(Symbol::WHITE, 'Nc3'));
$game->play(Convert::toObject(Symbol::BLACK, 'Nf6'));
$game->play(Convert::toObject(Symbol::WHITE, 'Bxb8'));
$game->play(Convert::toObject(Symbol::BLACK, 'Rxb8'));

$status = $game->status();
```

Will generate this `$status` object:

    stdClass Object
    (
        [turn] => w
        [squares] => stdClass Object
            (
                [used] => stdClass Object
                    (
                        [w] => Array
                            (
                                [0] => a1
                                [1] => d1
                                [2] => e1
                                [3] => f1
                                [4] => g1
                                [5] => h1
                                [6] => a2
                                [7] => b2
                                [8] => c2
                                [9] => e2
                                [10] => f2
                                [11] => g2
                                [12] => h2
                                [13] => d4
                                [14] => c3
                            )

                        [b] => Array
                            (
                                [0] => c8
                                [1] => d8
                                [2] => e8
                                [3] => f8
                                [4] => h8
                                [5] => a7
                                [6] => b7
                                [7] => e7
                                [8] => f7
                                [9] => g7
                                [10] => h7
                                [11] => c6
                                [12] => d5
                                [13] => f6
                                [14] => b8
                            )

                    )

                [free] => Array
                    (
                        [0] => a3
                        [1] => a4
                        [2] => a5
                        [3] => a6
                        [4] => a8
                        [5] => b1
                        [6] => b3
                        [7] => b4
                        [8] => b5
                        [9] => b6
                        [10] => c1
                        [11] => c4
                        [12] => c5
                        [13] => c7
                        [14] => d2
                        [15] => d3
                        [16] => d6
                        [17] => d7
                        [18] => e3
                        [19] => e4
                        [20] => e5
                        [21] => e6
                        [22] => f3
                        [23] => f4
                        [24] => f5
                        [25] => g3
                        [26] => g4
                        [27] => g5
                        [28] => g6
                        [29] => g8
                        [30] => h3
                        [31] => h4
                        [32] => h5
                        [33] => h6
                    )

            )

        [control] => stdClass Object
            (
                [space] => stdClass Object
                    (
                        [w] => Array
                            (
                                [0] => a3
                                [1] => a4
                                [2] => b1
                                [3] => b3
                                [4] => b5
                                [5] => c1
                                [6] => c5
                                [7] => d2
                                [8] => d3
                                [9] => e3
                                [10] => e4
                                [11] => e5
                                [12] => f3
                                [13] => g3
                                [14] => h3
                            )

                        [b] => Array
                            (
                                [0] => a5
                                [1] => a6
                                [2] => a8
                                [3] => b5
                                [4] => b6
                                [5] => c4
                                [6] => c7
                                [7] => d6
                                [8] => d7
                                [9] => e4
                                [10] => e6
                                [11] => f5
                                [12] => g4
                                [13] => g6
                                [14] => g8
                                [15] => h3
                                [16] => h5
                                [17] => h6
                            )

                    )

                [attack] => stdClass Object
                    (
                        [w] => Array
                            (
                                [0] => d5
                            )

                        [b] => Array
                            (
                            )

                    )

            )

        [castling] => stdClass Object
            (
                [w] => stdClass Object
                    (
                        [castled] =>
                        [O-O] => 1
                        [O-O-O] => 1
                    )

                [b] => stdClass Object
                    (
                        [castled] =>
                        [O-O] => 1
                        [O-O-O] =>
                    )

            )

        [previousMove] => stdClass Object
            (
                [w] => stdClass Object
                    (
                        [identity] => B
                        [position] => stdClass Object
                            (
                                [current] =>
                                [next] => b8
                            )

                    )

                [b] => stdClass Object
                    (
                        [identity] => R
                        [position] => stdClass Object
                            (
                                [current] =>
                                [next] => b8
                            )

                    )

            )

    )

The game's status properties can be easily accessed this way:

```php
<?php
// current turn
$game->status()->turn;

// used/free squares
$game->status()->squares->used;
$game->status()->squares->free;

// white's control
$game->status()->control->space->{Symbol::WHITE};
$game->status()->control->attack->{Symbol::WHITE};

// black's control
$game->status()->control->space->{Symbol::BLACK};
$game->status()->control->attack->{Symbol::BLACK};

// white's castling
$game->status()->castling->{Symbol::WHITE}->castled;
$game->status()->castling->{Symbol::WHITE}->{Symbol::CASTLING_SHORT};
$game->status()->castling->{Symbol::WHITE}->{Symbol::CASTLING_LONG};

// black's castling
$game->status()->castling->{Symbol::BLACK}->castled;
$game->status()->castling->{Symbol::BLACK}->{Symbol::CASTLING_SHORT};
$game->status()->castling->{Symbol::BLACK}->{Symbol::CASTLING_LONG};

// white's previous move
$game->status()->previousMove->{Symbol::WHITE}->identity;
$game->status()->previousMove->{Symbol::WHITE}->position->next;

// black's previous move
$game->status()->previousMove->{Symbol::BLACK}->identity;
$game->status()->previousMove->{Symbol::BLACK}->position->next;
```

#### 3.4. `getPieceByPosition()`

Gets a piece by its position on the board.

    $piece = $game->getPieceByPosition('c8');

`$piece` is a PHP object containing useful information.

| Property       | Description                                |
|----------------|--------------------------------------------|
| `color`        | The piece's color in PGN format            |
| `identity`     | The piece's identity in PGN format         |
| `position`     | The piece's position on the board          |
| `squares`      | The piece's squares                        |

The following code:

```php
<?php
use PGNChess\Game;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;

$game = new Game;

$piece = $game->getPieceByPosition('b8');
```

Will generate this `$piece` object:

    stdClass Object
    (
        [color] => b
        [identity] => N
        [position] => b8
        [squares] => Array
            (
                [0] => a6
                [1] => c6
            )

    )

The selected piece's properties can be easily accessed this way:

```php
<?php
$piece->color;
$piece->identity;
$piece->position;
$piece->squares;
```

#### 3.5. `getPiecesByColor()`

Gets the pieces on the board by color.

    $blackPieces = $game->getPiecesByColor(Symbol::BLACK);

`$blackPieces` is an array of PHP objects containing useful information about black pieces.

| Property       | Description                                |
|----------------|--------------------------------------------|
| `identity`     | The piece's identity in PGN format         |
| `position`     | The piece's position on the board          |
| `squares`      | The piece's squares                        |

The following code:

```php
<?php
use PGNChess\Game;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;

$game = new Game;

$blackPieces = $game->getPiecesByColor(Symbol::BLACK);
```

Will generate this `$blackPieces` array of objects:

    Array
    (
        [0] => stdClass Object
            (
                [identity] => R
                [position] => a8
                [squares] => Array
                    (
                    )

            )

        [1] => stdClass Object
            (
                [identity] => N
                [position] => b8
                [squares] => Array
                    (
                        [0] => a6
                        [1] => c6
                    )

            )

        [2] => stdClass Object
            (
                [identity] => B
                [position] => c8
                [squares] => Array
                    (
                    )

            )

        [3] => stdClass Object
            (
                [identity] => Q
                [position] => d8
                [squares] => Array
                    (
                    )

            )

        [4] => stdClass Object
            (
                [identity] => K
                [position] => e8
                [squares] => Array
                    (
                    )

            )

        [5] => stdClass Object
            (
                [identity] => B
                [position] => f8
                [squares] => Array
                    (
                    )

            )

        [6] => stdClass Object
            (
                [identity] => N
                [position] => g8
                [squares] => Array
                    (
                        [0] => f6
                        [1] => h6
                    )

            )

        [7] => stdClass Object
            (
                [identity] => R
                [position] => h8
                [squares] => Array
                    (
                    )

            )

        [8] => stdClass Object
            (
                [identity] => P
                [position] => a7
                [squares] => Array
                    (
                        [0] => a6
                        [1] => a5
                    )

            )

        [9] => stdClass Object
            (
                [identity] => P
                [position] => b7
                [squares] => Array
                    (
                        [0] => b6
                        [1] => b5
                    )

            )

        [10] => stdClass Object
            (
                [identity] => P
                [position] => c7
                [squares] => Array
                    (
                        [0] => c6
                        [1] => c5
                    )

            )

        [11] => stdClass Object
            (
                [identity] => P
                [position] => d7
                [squares] => Array
                    (
                        [0] => d6
                        [1] => d5
                    )

            )

        [12] => stdClass Object
            (
                [identity] => P
                [position] => e7
                [squares] => Array
                    (
                        [0] => e6
                        [1] => e5
                    )

            )

        [13] => stdClass Object
            (
                [identity] => P
                [position] => f7
                [squares] => Array
                    (
                        [0] => f6
                        [1] => f5
                    )

            )

        [14] => stdClass Object
            (
                [identity] => P
                [position] => g7
                [squares] => Array
                    (
                        [0] => g6
                        [1] => g5
                    )

            )

        [15] => stdClass Object
            (
                [identity] => P
                [position] => h7
                [squares] => Array
                    (
                        [0] => h6
                        [1] => h5
                    )

            )

    )

Pieces' properties can be easily accessed this way:

```php
<?php
$blackPieces[1]->identity;
$blackPieces[1]->position;
$blackPieces[1]->squares;
```

### 4. Sample Games

The `/examples` folder contains a few basic examples showing how PGN chess games can be processed in PHP apps -- for further information please look at the `/tests` folder.

This is the output obtained when running `game01.php`:

    $ php examples/game01.php
    w played d4
    b played d6
    w played c4
    b played g6
    w played Nc3
    b played Bg7
    w played e4
    b played e6
    w played Nf3
    b played Ne7
    w played Bd3
    b played O-O
    w played Qc2
    b played Nd7
    w played O-O-O
    Illegal move

This particular example prints the output and exits the game if an illegal chess move is found. But obviously you can take a different approach, for example go into a loop till the player runs a valid move.

Again, it is up to you how to process the moves accordingly -- ask the player to please try again, play a sound, exit the game or whatever thing you consider appropriate. The important thing is that PGN Chess understands chess rules, internally replicating the game being played on the board.

Let's look at another example, the output generated by `game02.php`:

    $ php examples/game02.php
    w played e4
    b played c5
    w played Nf3
    b played Nc6
    w played Bb5
    b played e6
    w played O-O
    b played a6
    w played Ba4
    b played Qc7
    w played c3
    b played b5
    w played Bb3
    b played Bb7
    w played d4
    b played c4
    w played Bc2
    b played Be7
    w played d5
    b played exd5
    w played exd5
    b played Ne5
    w played Nxe5
    b played Qxe5
    w played Re1
    b played Qxd5
    w played Qxd5
    b played Bxd5
    w played Bg5
    b played f6
    w played Bh4
    b played Kf7
    w played Na3
    b played Nh6
    w played Be4
    b played Bxe4
    w played Rxe4
    b played Bxa3
    w played bxa3
    b played Rhe8
    w played Rd4
    b played Re7
    w played Rad1
    b played Nf5
    w played Rxd7
    b played Nxh4
    w played R1d4
    b played Nf5
    w played R4d5
    b played g6
    w played g4
    b played Nh4
    w played f4
    b played Re8
    w played Kf2
    b played g5
    w played Kg3
    b played Ng6
    w played fxg5
    b played Ne5
    w played Rxe7+
    Check!
    b played Rxe7
    w played gxf6
    b played Kxf6
    w played g5+
    Check!
    b played Ke6
    w played Rd1
    b played Nd3
    w played Rd2
    b played Kf7
    w played h4
    b played Kg6
    w played Kg4
    b played Re4+
    Check!
    w played Kf3
    b played Rxh4
    w played Re2
    b played Rf4+
    Check!
    w played Kg3
    b played Rf5
    w played Re6+
    Check!
    b played Kxg5

> **Remember**: PGN Chess games are actually run in the computer's memory. So, if it turns out that for whatever reason a player forgets to append the + symbol to their check moves, PGN Chess will anyway understand that it is a check move, as it is shown below. The same thing goes for checkmate moves.

    w played e4
    b played c5
    w played Nf3
    b played Nc6
    w played Bb5
    b played e6
    w played O-O
    b played a6
    w played Ba4
    b played Qc7
    w played c3
    b played b5
    w played Bb3
    b played Bb7
    w played d4
    b played c4
    w played Bc2
    b played Be7
    w played d5
    b played exd5
    w played exd5
    b played Ne5
    w played Nxe5
    b played Qxe5
    w played Re1
    b played Qxd5
    w played Qxd5
    b played Bxd5
    w played Bg5
    b played f6
    w played Bh4
    b played Kf7
    w played Na3
    b played Nh6
    w played Be4
    b played Bxe4
    w played Rxe4
    b played Bxa3
    w played bxa3
    b played Rhe8
    w played Rd4
    b played Re7
    w played Rad1
    b played Nf5
    w played Rxd7
    b played Nxh4
    w played R1d4
    b played Nf5
    w played R4d5
    b played g6
    w played g4
    b played Nh4
    w played f4
    b played Re8
    w played Kf2
    b played g5
    w played Kg3
    b played Ng6
    w played fxg5
    b played Ne5
    w played Rxe7
    Check!
    b played Rxe7
    w played gxf6
    b played Kxf6
    w played g5
    Check!
    b played Ke6
    w played Rd1
    b played Nd3
    w played Rd2
    b played Kf7
    w played h4
    b played Kg6
    w played Kg4
    b played Re4
    Check!
    w played Kf3
    b played Rxh4
    w played Re2
    b played Rf4
    Check!
    w played Kg3
    b played Rf5
    w played Re6
    Check!
    b played Kxg5

### 5. About PGN format

Need some more examples? Look at the format used in the following moves, they all will be processed OK by PGN Chess.

    1. e4 e5 2. Nc3 Nf6 3. f4 exf4 4. d4 Bb4 5. e5 Bxc3+ 6. bxc3 Nd5 7. c4 Qh4+
    8. g3 fxg3 9. Nf3 Qe4+ 10. Qe2 Qxe2+ 11. Bxe2 gxh2 12. cxd5 d6 13. Rxh2 Bg4
    14. Ba3 dxe5 15. dxe5 Nd7 16. Rg2 Bxf3 17. Bxf3 Nxe5 18. Re2 f6 19. d6 O-O-O
    20. Bg2 cxd6 21. Rd1 Kc7 22. Red2 Rd7 23. Bxd6+ Kc8 24. Rd4 Rhd8 25. Bh3
    Nf3+ 26. Kf2 Nxd4 27. Bxd7+ Rxd7 28. Rxd4 Rxd6 29. Rxd6 Kc7 30. Rd3 b6 31.
    Ke3 g6 32. Rd5 h5 33. c4 g5 34. Kf3 h4 35. Kg4 Kc6 36. Kf5 b5 37. Kxf6 bxc4
    38. Rd1 g4 39. Kg5 g3 40. Kxh4 g2 41. Rg1 c3 42. Rxg2 a5 43. Rc2 Kb5 44.
    Rxc3 Kb4 45. Rg3 a4 46. Kg4 Kc4 47. Kf4 Kd4 48. Kf5 Kc4 49. Ke5 Kb4 50. Kd5
    Kb5 51. Kd4 Kb4 52. Ke4 Kc4 53. Ke5 Kb4 54. Kd5 Kb5 55. Rg4 a3 56. Rg3 Kb4
    57. Kd6 Ka4 58. Kc5 Ka5 59. Rxa3# {Black checkmated} 1-0

    1. e4 c5 2. Nc3 Nc6 3. Bb5 g6 4. Bxc6 bxc6 5. d3 Bg7 6. f4 e6 7. Nf3 Ne7 8.
    O-O O-O 9. Qe1 d5 10. Qh4 d4 11. Ne2 Qc7 12. g4 e5 13. f5 gxf5 14. gxf5 Qd6
    15. Bh6 Bxh6 16. Qg3+ Bg7 17. Nh4 Qh6 18. f6 Ng6 19. fxg7 Kxg7 20. Nf5+ Bxf5
    21. exf5 Qe3+ 22. Qxe3 dxe3 23. fxg6 fxg6 24. Rxf8 Rxf8 25. Rf1 Rb8 26. Rf3
    Rxb2 27. Rxe3 Rxc2 28. a4 Ra2 29. Rxe5 Rxa4 30. Rxc5 Ra3 31. Rxc6 Rxd3 32.
    Kf2 Rd7 33. Ra6 h5 34. Nf4 Rf7 35. Ke3 Re7+ 36. Kf3 Rf7 37. Ke4 Re7+ 38. Re6
    Rxe6+ 39. Nxe6+ Kf6 40. Nd4 g5 41. h3 a5 42. Nc6 a4 43. Nb4 a3 44. Na2 g4
    45. hxg4 Kg5 46. Ke3 hxg4 47. Kf2 Kf4 48. Kg2 g3 49. Nc3 Kg4 50. Na2 Kf4 51.
    Nc3 Kg4 52. Na2 Kf4 53. Nc1 Ke3 54. Kxg3 Kd2 55. Nb3+ Kc2 56. Kf3 a2 57. Ke3
    Kb2 58. Kd2 Kxb3 59. Kc1 Ka3 60. Kd2 Kb2 61. Kd3 a1=Q 62. Kc4 Ka3 63. Kc5
    Qb2 64. Kd6 Qc2 65. Ke6 Qd2 {White forfeits on time} 0-1

### 6. License

The MIT License (MIT).

### 7. Contributions

Would you help make this library better? Contributions are welcome.

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "PGNChess Contributions"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)
- Say hello on [Google+](https://plus.google.com/+Programarivm)

Many thanks.
