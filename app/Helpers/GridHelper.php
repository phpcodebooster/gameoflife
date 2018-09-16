<?php


/*
| -------------------------------------------------------------------------
| L2T Framework
| -------------------------------------------------------------------------
|
| User: spatel
| Date: 16/09/18
| Time: 9:24 AM
| Version: 1.0
| Website: http://www.phpcodebooster.com
*/

namespace App\Helpers;

class GridHelper
{
    /**
     * @var int
     */
    private $w;

    /**
     * @var int
     */
    private $h;

    /**
     * @var array
     */
    private $cells = [];

    /**
     * GridHelper constructor.
     * @param int $w
     * @param int $h
     */
    public function __construct(int $w, int $h)
    {
        $this->w = $w;
        $this->h = $h;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->w;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->h;
    }

    /**
     * Function generates cells
     * within specified width and
     * height of the terminal window
     *
     * @return $this
     */
    public function generateCells()
    {
        for ($x = 0; $x < $this->w; $x++) {
            for ($y = 0; $y < $this->h; $y++) {
                $this->cells[$y][$x] = 0;
            }
        }
        return $this;
    }

    /**
     * Setting templates and marking live cells
     *
     * @return $this
     */
    public function setTemplate()
    {
        $fp = fopen(dirname(__DIR__). '/Console/Commands/template.txt', 'r');

        $centerX = (int) floor($this->getWidth() / 2) / 2;
        $centerY = (int) floor($this->getHeight() / 2) / 2;

        $x = $centerX;
        $y = $centerY;

        while (false !== ($char = fgetc($fp))) {
            if ($char == 'O') {
                $this->cells[$y][$x] = 1;
            }
            if ($char == "\n") {
                $y++;
                $x = $centerX;
            }
            else {
                $x++;
            }
        }

        fclose($fp);

        return $this;
    }

    /**
     * Start the game
     */
    public function play()
    {
        while (true) {
            $this->render();
            $this->generateNewCells();
        }
    }

    /**
     * Display dead or live cells
     */
    private function render()
    {
        foreach ($this->cells as $y => $row) {
            foreach ($row as $x => $cell) {
                print($cell ? '0' : ' ');
            }
            print "\n";
        }

        // add some time delay
        usleep(5000);

        echo "\r"; // return to beginning of line
        echo "\033[K"; // erase the end of the line
        echo "\033[0;0H"; // move to 0,0 position of terminal
    }

    /**
     * Processes a new generation for all cells.
     * Base on these rules:
     *
     * 1) Any live cell with fewer than two live neighbours dies, as if caused by underpopulation.
     * 2) Any live cell with two or three live neighbours lives on to the next generation.
     * 3) Any live cell with more than three live neighbours dies, as if by overpopulation.
     * 4) Any dead cell with exactly three live neighbours becomes a live cell, as if by reproduction.
     */
    private function generateNewCells()
    {
        $kill = $born = [];
        $cells = &$this->cells;

        for ($y = 0; $y < $this->getHeight(); $y++) {
            for ($x = 0; $x < $this->getWidth(); $x++) {

                // cell activity is determined by the neighbor count.
                $neighbors = $this->getAliveNeighborCount($x, $y);

                if ($cells[$y][$x] && ($neighbors < 2 || $neighbors > 3)) {
                    $kill[] = [$y, $x];
                }

                if (!$cells[$y][$x] && $neighbors === 3) {
                    $born[] = [$y, $x];
                }
            }
        }

        foreach ($kill as $c) {
            $cells[$c[0]][$c[1]] = 0;
        }

        foreach ($born as $c) {
            $cells[$c[0]][$c[1]] = 1;
        }
    }

    /**
     * Getting count of alive
     * neighbours ...
     *
     * @param $x
     * @param $y
     * @return int
     */
    private function getAliveNeighborCount($x, $y)
    {
        $aliveNeighbour = 0;

        for ($y2 = $y - 1; $y2 <= $y + 1; $y2++) {
            if ($y2 < 0 || $y2 >= $this->getHeight()) {
                continue; // out of range
            }
            for ($x2 = $x - 1; $x2 <= $x + 1; $x2++) {
                if ($x2 == $x && $y2 == $y) {
                    continue; // current position
                }
                if ($x2 < 0 || $x2 >= $this->getWidth()) {
                    continue; // out of range
                }
                if ($this->cells[$y2][$x2]) {
                    $aliveNeighbour += 1;
                }
            }
        }

        return $aliveNeighbour;
    }
}