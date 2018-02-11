<?php
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 04.02.18
 * Time: 22:22
 */

require __DIR__ . '/vendor/autoload.php';

use v2\Entity\Building;
use v2\Entity\Command;
use v2\Entity\Elevator;
use v2\Service\Module;

$elevator = new Elevator(1);
$building = new Building(4, 4);
$module = new Module($elevator, $building);

$stdout = fopen('php://stdout', 'w');
while (true) {
    $line = fgets($stdout);
    $separateSymbols = explode(' ', trim($line));
    switch ($separateSymbols[0]) {
        case 'u':
            $command = new Command($separateSymbols[1], 'up', Command::BUTTON_DIRECTION);
            $module->addCommandToFile($command);
            break;
        case 'd':
            $command = new Command($separateSymbols[1], 'down', Command::BUTTON_DIRECTION);
            $module->addCommandToFile($command);
            break;
        case 's':
            $command = new Command(1, 'stop', Command::BUTTON_STOP);
            $module->addCommandToFile($command);
            break;
        case '1':
            $command = new Command($separateSymbols[0], $separateSymbols[0], Command::BUTTON_NUMBER);
            $module->addCommandToFile($command);
            break;
        case '2':
            $command = new Command($separateSymbols[0], $separateSymbols[0], Command::BUTTON_NUMBER);
            $module->addCommandToFile($command);
            break;
        case '3':
            $command = new Command($separateSymbols[0], $separateSymbols[0], Command::BUTTON_NUMBER);
            $module->addCommandToFile($command);
            break;
        case '4':
            $command = new Command($separateSymbols[0], $separateSymbols[0], Command::BUTTON_NUMBER);
            $module->addCommandToFile($command);
            break;
        default:
            echo 'Not this. This ' . $line ;
            break;
    }
}