<?php

namespace App\Console\Commands;

use App\Helpers\GridHelper;
use Illuminate\Console\Command;

class Game extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:start {--w=} {--h=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The Game of Life';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // grid width and height
        $w = $this->option('w') ?? exec('tput cols');
        $h = $this->option('h') ?? exec('tput lines');

        // define grid and generate
        // cells under width and height
        $grid = new GridHelper((int)$w, (int)$h);

        // play the game
        $grid->generateCells()->setTemplate()->play();
    }
}
