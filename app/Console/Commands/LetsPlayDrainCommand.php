<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LetsPlayDrainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drain:play';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Play the game of your life - Drain';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("You are about to play, one the best games of your life, brace yourself!");
        $startGame = $this->confirm('Do you wish to continue?');
        if ($startGame) {
            $inputA = $this->sanitizeInputs('Team A');
            $inputB = $this->sanitizeInputs('Team B');

            $this->predictResult($inputB, $inputA);
        }
    }

    /**
     * @param $teamName
     * @return array
     */
    protected function sanitizeInputs($teamName): array
    {
        $team = $this->ask("Please enter five 'comma' separated numeric values for " . $teamName);
        $input = explode(',', $team);
        if (!$this->validateTeamInput($input)) {
            $this->error("Please enter at least five numeric values");
            die();
        }
        return $input;
    }

    /**
     * @param $input
     * @return bool
     */
    protected function validateTeamInput($input)
    {
        $isValid = true;

        if (sizeof($input) <= 1) {
            $isValid = false;
        }
        $teamInput = collect($input);

        if ($teamInput->count() != 5) {
            $isValid = false;
        }

        $containsNumbers = [];
        foreach ($input as $value) {
            $output = preg_match('/[^0-9]/', $value);
            if ($output) {
                $containsNumbers[] = false;
            } else {
                $containsNumbers[] = true;
            }
        }

        if (in_array(false, $containsNumbers)) {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * @param array $inputB
     * @param array $inputA
     */
    protected function predictResult(array $inputB, array $inputA): void
    {
        $newArray = $arr = [];
        foreach ($inputB as $player) {
            foreach ($inputA as $key => $drain) {
                if (abs($player - $drain) != 0) {
                    $newArray[$player][$drain] = abs($player - $drain);
                }
            }
            $minDrainValue = min($newArray[$player]);
            $minDrain = array_search($minDrainValue, $newArray[$player]);
            if ($minDrain < $player) {
                $arr[] = false;
            } else {
                $arr[] = true;
            }
        }

        if (in_array(false, $arr)) {
            $this->info("TEAM A Loses");
        } else {
            $this->info("TEAM A Wins");
        }
    }
}
