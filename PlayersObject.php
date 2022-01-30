<?php

/*
    Development Exercise

      The following code is poorly designed and error prone. Refactor the objects below to follow a more SOLID design.
      Keep in mind the fundamentals of MVVM/MVC and Single-responsibility when refactoring.

      Further, the refactored code should be flexible enough to easily allow the addition of different display
        methods, as well as additional read and write methods.

      Feel free to add as many additional classes and interfaces as you see fit.

      Note: Please create a fork of the https://github.com/BrandonLegault/exercise repository and commit your changes
        to your fork. The goal here is not 100% correctness, but instead a glimpse into how you
        approach refactoring/redesigning bad code. Commit often to your fork.

*/


interface IReadWritePlayers {
    function readPlayers($source, $filename = null);
    function writePlayer($source, $player, $filename = null);
    function display($isCLI, $course, $filename = null);
}

class PlayersObject implements IReadWritePlayers {

    private $playersArray;

    private $playerJsonString;

    public function __construct() {
        //We're only using this if we're storing players as an array.
        $this->playersArray = [];

        //We'll only use this one if we're storing players as a JSON string
        $this->playerJsonString = null;
    }

    /**
     * @param $source string Where we're retrieving the data from. 'json', 'array' or 'file'
     * @param $filename string Only used if we're reading players in 'file' mode.
     * @return string json
     */
    function readPlayers($source, $filename = null) {
        $playerData = null;
        $content = extract_file_content($filename = null);
        switch ($source) {
            case 'array':
                // Extract information from json file
                $playerData = $this->getPlayerDataArray($content);
                break;
            case 'json':
                $playerData = $this->content;
                break;
            case 'file':
                $playerData = $this->content;
                break;
        }

        if (is_string($playerData)) {
            $playerData = json_decode($playerData);
        }

        return $playerData;

    }

    /**
     * @param $source string Where to write the data. 'json', 'array' or 'file'
     * @param $filename string Only used if we're writing in 'file' mode
     * @param $player \stdClass Class implementation of the player with name, age, job, salary.
     */
    function writePlayer($source, $player, $filename = null) {
        switch ($source) {
            case 'array':
                $this->playersArray[] = $player;
                break;
            case 'json':
                $players = [];
                if ($this->playerJsonString) {
                    $players = json_decode($this->playerJsonString);
                }
                $players[] = $player;
                $this->playerJsonString = json_encode($player);
                break;
            case 'file':
                $players = json_decode($this->getPlayerDataFromFile($filename));
                if (!$players) {
                    $players = [];
                }
                $players[] = $player;
                file_put_contents($filename, json_encode($players));
                break;
        }
    }


    function getPlayerDataArray($content) {
        // Extract information using json file
        $players = [];
        foreach ($content as $playerData) {
            $data = new \stdClass();
            $jonas->name = $playerData['name'];
            $jonas->age = $playerData['age'];
            $jonas->job = $playerData['job'];
            $jonas->salary = $playerData['salary'];
            $players[] = $data;
        }

        // $jonas = new \stdClass();
        // $jonas->name = 'Jonas Valenciunas';
        // $jonas->age = 26;
        // $jonas->job = 'Center';
        // $jonas->salary = '4.66m';
        // $players[] = $jonas;

        // $kyle = new \stdClass();
        // $kyle->name = 'Kyle Lowry';
        // $kyle->age = 32;
        // $kyle->job = 'Point Guard';
        // $kyle->salary = '28.7m';
        // $players[] = $kyle;

        // $demar = new \stdClass();
        // $demar->name = 'Demar DeRozan';
        // $demar->age = 28;
        // $demar->job = 'Shooting Guard';
        // $demar->salary = '26.54m';
        // $players[] = $demar;

        // $jakob = new \stdClass();
        // $jakob->name = 'Jakob Poeltl';
        // $jakob->age = 22;
        // $jakob->job = 'Center';
        // $jakob->salary = '2.704m';
        // $players[] = $jakob;

        return $players;

    }

    function extractFileContent($filename=null) {
        if (!$filename) {
            $file = file_get_contents($filename);
            return $file;
        } else {
            // Read the JSON file 
            $json = file_get_contents('playerdata.json');
            // Display data
            return $json;
        }
    }

    function display($isCLI, $source, $filename = null) {

        $players = $this->readPlayers($source, $filename);

        if ($isCLI) {
            echo "Current Players: \n";
            foreach ($players as $player) {

                echo "\tName: $player->name\n";
                echo "\tAge: $player->age\n";
                echo "\tSalary: $player->salary\n";
                echo "\tJob: $player->job\n\n";
            }
        } else {

            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    li {
                        list-style-type: none;
                        margin-bottom: 1em;
                    }
                    span {
                        display: block;
                    }
                </style>
            </head>
            <body>
            <div>
                <span class="title">Current Players</span>
                <ul>
                    <?php foreach($players as $player) { ?>
                        <li>
                            <div>
                                <span class="player-name">Name: <?= $player->name ?></span>
                                <span class="player-age">Age: <?= $player->age ?></span>
                                <span class="player-salary">Salary: <?= $player->salary ?></span>
                                <span class="player-job">Job: <?= $player->job ?></span>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </body>
            </html>
            <?php
        }
    }

}

$playersObject = new PlayersObject();
// Our first call was to the display method. 
// We check if we make use of a CLI, and pass in an array as a source.
$playersObject->display(php_sapi_name() === 'cli', 'array');

?>