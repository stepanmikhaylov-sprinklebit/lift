<?php
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 04.02.18
 * Time: 14:58
 */

namespace TestV2;
use v2\Entity\Building;
use v2\Entity\Command;
use v2\Entity\Elevator;
use v2\Service\Module;
use v2\Entity\Route;

use PHPUnit\Framework\TestCase;

class TestIntegration extends  TestCase
{
    /**
     * @var Module
     */
    private $module;

    public function setUp()
    {
        parent::setUp();
        $elevator = new Elevator(1);
        $building = new Building(4, 4);
        $this->module = new Module($elevator, $building);
    }

    public function testRun1()
    {
        $command1 = new Command(2, 2, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(4, 4, 'up', Command::BUTTON_DIRECTION);
        $command3 = new Command(2, 3, 'down', Command::BUTTON_DIRECTION);
        $command4 = new Command(4, 1, 'down', Command::BUTTON_DIRECTION);
        $command5 = new Command(2, 1, '1', Command::BUTTON_NUMBER);
        $command6 = new Command(4, 4, '4', Command::BUTTON_NUMBER);
        $command7 = new Command(4, 3, '3', Command::BUTTON_NUMBER);
        $command8 = new Command(4, 2, '2', Command::BUTTON_NUMBER);
        $command9 = new Command(4, 2, 'down', Command::BUTTON_DIRECTION);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->addCommand($command3);
        $this->module->addCommand($command4);
        $this->module->addCommand($command5);
        $this->module->addCommand($command6);
        $this->module->addCommand($command7);
        $this->module->addCommand($command8);
        $this->module->addCommand($command9);

        $this->module->run(8);
    }

    public function testRun2()
    {
        $command1 = new Command(2, 2, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(4, 4, 'up', Command::BUTTON_DIRECTION);
        $command3 = new Command(2, 3, 'down', Command::BUTTON_DIRECTION);
        $command4 = new Command(4, 1, 'down', Command::BUTTON_DIRECTION);
        $command5 = new Command(2, 1, '1', Command::BUTTON_NUMBER);
        $command6 = new Command(4, 4, '4', Command::BUTTON_NUMBER);
        $command7 = new Command(3, 3, 'stop', Command::BUTTON_STOP);
        $command8 = new Command(4, 4, 'stop', Command::BUTTON_STOP);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->addCommand($command3);
        $this->module->addCommand($command7);

        $startTime = microtime(true);
        $isActivated = true;
        while (microtime(true) - $startTime < 20) {

            $this->module->startMoving();
            $this->module->status(microtime(true));
            $this->module->updateCommands();
            if ($isActivated && microtime(true) - $startTime > 4) {
                $isActivated = false;
                $this->module->addCommandToFile($command4);
                $this->module->addCommandToFile($command5);
                $this->module->addCommandToFile($command6);
                $this->module->addCommandToFile($command8);
            }
        }
    }

    public function testRun3()
    {
        $command1 = new Command(2, 2, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(4, 4, 'up', Command::BUTTON_DIRECTION);
        $command3 = new Command(2, 3, 'down', Command::BUTTON_DIRECTION);
        $command4 = new Command(4, 1, 'down', Command::BUTTON_DIRECTION);
        $command5 = new Command(2, 1, '1', Command::BUTTON_NUMBER);
        $command6 = new Command(4, 4, '4', Command::BUTTON_NUMBER);
        $command7 = new Command(3, 3, 'stop', Command::BUTTON_STOP);
        $command8 = new Command(4, 4, 'stop', Command::BUTTON_STOP);

//        $this->module->addCommandToFile($command4);
//        $this->module->addCommandToFile($command5);
//        $this->module->addCommandToFile($command6);
//        $this->module->addCommandToFile($command8);
//        $this->module->addCommandToFile($command1);
//        $this->module->addCommandToFile($command2);
//        $this->module->addCommandToFile($command3);
        $this->module->addCommandToFile($command7);
    }
}