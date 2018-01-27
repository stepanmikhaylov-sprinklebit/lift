<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ValueObject\Building;
use ValueObject\Elevator;
use ValueObject\Command;
use Service\ControlModule;
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 20.01.18
 * Time: 15:58
 */
class TestControlModule extends TestCase
{
    /**
     * @var ControlModule
     */
    private $control;

    public function setUp()
    {
        $elevator = new Elevator(1, 0, 1, 1);
        $building = new Building(4, 4);
        $this->control = new ControlModule($elevator, $building);
    }

    public function testRunUp()
    {
        $command = new Command(4, 4);
        $this->control->addCommand($command);
        $timeStart = microtime(true);
        $timeEnd = microtime(true);
        // $timeStart + 10 > $timeEnd
        while ($timeStart + 5 > $timeEnd){
            $timeEnd = microtime(true);
            $this->control->run();
        }


        $this->assertEquals(4, $this->control->getElevator()->getCurrentLevel());
    }

    public function testRunDown()
    {
        $command = new Command(4, 4);
        $command2 = new Command(2, 2);
        $this->control->addCommand($command);
        $this->control->addCommand($command2);
        $timeStart = microtime(true);
        $timeEnd = microtime(true);
        // $timeStart + 10 > $timeEnd
        while ($timeStart + 10 > $timeEnd){
            $timeEnd = microtime(true);
            $this->control->run();
        }

        print_r($this->control->getElevator()->getDirection());
        $this->assertEquals(2, $this->control->getElevator()->getCurrentLevel());
    }

    public function testAddCommand()
    {
        $command = new Command(1, 4);
        $this->control->addCommand($command);

        $this->assertEquals([$command], $this->control->getCommands());
    }

    public function dataCommandToActivate()
    {
        return [
            'stop command in list' =>
                [[new Command(1,1), new Command(1, 'up'), new Command(1, 'stop')], 2],
            'up and down commands' =>
                [[new Command(1, 'up'), new Command(2, 'up'), new Command(3, 'up')], 0],
            ''
        ];
    }

    /**
     * @dataProvider dataCommandToActivate
     * @param Command[] $commands
     * @param $index
     */
    public function testCommandToActivate($commands, $index)
    {
        foreach ($commands as $command) {
            $this->control->addCommand($command);
        }

        $this->assertEquals($commands[$index], $this->control->getCommandToActivate());
    }

    public function dataGetActiveCommand()
    {
        return [
            'first command active' =>
                [[(new Command(1,1))->setIsActive(true), new Command(2, 'stop')], 0],
            'second command active' =>
                [[new Command(1,1), (new Command(2, 'stop'))->setIsActive(true)], 1]
        ];
    }

    /**
     * @dataProvider dataGetActiveCommand
     * @param Command[] $commands
     * @param $index
     */
    public function testGetActiveCommand($commands, $index)
    {
        foreach ($commands as $command) {
            $this->control->addCommand($command);
        }

        $this->assertEquals($commands[$index], $this->control->getActiveCommand());
    }

    public function testActivateCommand()
    {
        $command = new Command(1, 2);
        $command->setIsActive(true);
        $commandStop = new Command(1, 'stop');
        $this->control->addCommand($command);
        $this->control->addCommand($commandStop);

        $this->control->activateCommand();

        $this->assertTrue($commandStop->isActive());
        $this->assertFalse($command->isActive());
    }

    public function testUpdateCommand()
    {
        $command1 = new Command(1, 1);
        $command2 = new Command(2, 2);
        $command3 = new Command(3, 3);

        $this->control->addCommand($command1);
        $this->control->addCommand($command2);
        $this->control->addCommand($command3);

        $this->control->setActiveCommand();
        $this->assertTrue($command1->isActive());

        $command1->setIsDone(true);

        $this->control->setActiveCommand();
        $this->assertTrue($command2->isActive());

        $command2->setIsDone(true);

        $this->control->setActiveCommand();
        $this->assertTrue($command3->isActive());
    }

    public function testSetActiveCommand()
    {
        $command1 = new Command(1, 1);
        $command2 = new Command(2, 2);

        $this->control->addCommand($command1);
        $this->control->addCommand($command2);

        $this->control->setActiveCommand();

        $this->assertTrue($command1->isActive());
    }
}