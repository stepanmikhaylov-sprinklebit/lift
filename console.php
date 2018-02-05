<?php
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 04.02.18
 * Time: 21:33
 */
require __DIR__ . '/vendor/autoload.php';

use v2\Entity\Building;
use v2\Entity\Command;
use v2\Entity\Elevator;
use v2\Service\Module;

$elevator = new Elevator(1);
$building = new Building(4, 4);
$module = new Module($elevator, $building);

$command1 = new Command(2, 2, 'up', Command::BUTTON_DIRECTION);
$command2 = new Command(4, 4, 'up', Command::BUTTON_DIRECTION);
$command3 = new Command(2, 3, 'down', Command::BUTTON_DIRECTION);
$command4 = new Command(4, 1, 'down', Command::BUTTON_DIRECTION);
$command5 = new Command(2, 1, '1', Command::BUTTON_NUMBER);
$command6 = new Command(4, 4, '4', Command::BUTTON_NUMBER);
$command7 = new Command(4, 3, '3', Command::BUTTON_NUMBER);
$command8 = new Command(4, 2, '2', Command::BUTTON_NUMBER);
$command9 = new Command(4, 2, 'down', Command::BUTTON_DIRECTION);

$module->addCommand($command1);
$module->addCommand($command2);
$module->addCommand($command3);
$module->addCommand($command4);
$module->addCommand($command5);
$module->addCommand($command6);
$module->addCommand($command7);
$module->addCommand($command8);
$module->addCommand($command9);

$module->run(200);