<?php
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 05.02.18
 * Time: 19:33
 */

namespace TestV2;


use PHPUnit\Framework\TestCase;
use v2\Entity\Building;
use v2\Entity\Command;
use v2\Entity\Elevator;
use v2\Entity\Passengers;
use v2\Service\Module;
use v2\Entity\Route;

class TestPart1 extends TestCase
{
    /**
     * @var Module
     */
    private $module;

    public function setUp()
    {
        parent::setUp();
        $elevator = new Elevator(1, 700);
        $building = new Building(4, 1);
        $this->module = new Module($elevator, $building);
        $this->module->addElevator($elevator);
    }

    public function dataAddCommand()
    {
        return [
            'button direction' => [new Command(4, 'up', Command::BUTTON_DIRECTION), true],
            'button number' => [new Command(2, '2', Command::BUTTON_NUMBER), true],
            'button stop' => [new Command(1, 'stop', Command::BUTTON_STOP), false],
            'button call' => [new Command(2, 'call', Command::BUTTON_CALL), true],
        ];
    }

    /**
     * @dataProvider dataAddCommand
     * @param Command $command
     * @param bool $expected
     */
    public function testAddCommand($command, $expected)
    {
        $command->setElevator($this->module->getElevators()[0]);

        $this->module->addCommand($command);
        if ($this->module->getCommands() !== null) {
            $this->assertEquals($expected, in_array($command, $this->module->getCommands()));
        } else {
            $this->assertFalse($expected);
        }
    }

    public function dataDuplicateCommands()
    {
        return [
            'two equals up buttons' => [
                new Command(2, 'up', Command::BUTTON_DIRECTION),
                new Command(2, 'up', Command::BUTTON_DIRECTION),
                1
            ],
            'two not equals up buttons' => [
                new Command(2, 'up', Command::BUTTON_DIRECTION),
                new Command(3, 'up', Command::BUTTON_DIRECTION),
                2
            ],
            'two equals down up buttons' => [
                new Command(2, 'down', Command::BUTTON_DIRECTION),
                new Command(2, 'down', Command::BUTTON_DIRECTION),
                1
            ],
            'two not equals down buttons' => [
                new Command(2, 'down', Command::BUTTON_DIRECTION),
                new Command(3, 'down', Command::BUTTON_DIRECTION),
                2
            ],
            'two equals number buttons' => [
                new Command(2, '2', Command::BUTTON_NUMBER),
                new Command(2, '2', Command::BUTTON_NUMBER),
                1
            ],
            'two not equals number buttons' => [
                new Command(2, '2', Command::BUTTON_NUMBER),
                new Command(3, '3', Command::BUTTON_NUMBER),
                2
            ],
            'up and number buttons with same destination level' => [
                new Command(2, 'up', Command::BUTTON_DIRECTION),
                new Command(2, '2', Command::BUTTON_NUMBER),
                2
            ],
            'down and number buttons with same destination level' => [
                new Command(2, 'down', Command::BUTTON_DIRECTION),
                new Command(2, '2', Command::BUTTON_NUMBER),
                2
            ],
            'down and up buttons with same destination level' => [
                new Command(2, 'up', Command::BUTTON_DIRECTION),
                new Command(2, 'down', Command::BUTTON_DIRECTION),
                2
            ],
            'call and number buttons with same destination level' => [
                new Command(2, 'call', Command::BUTTON_CALL),
                new Command(2, '2', Command::BUTTON_DIRECTION),
                2
            ],
            'call and number buttons with different destination level' => [
                new Command(2, 'call', Command::BUTTON_CALL),
                new Command(3, '3', Command::BUTTON_DIRECTION),
                2
            ],
            'two equals call buttons' => [
                new Command(2, 'call', Command::BUTTON_CALL),
                new Command(2, 'call', Command::BUTTON_CALL),
                1
            ],
            'two not equals call buttons' => [
                new Command(2, 'call', Command::BUTTON_CALL),
                new Command(3, 'call', Command::BUTTON_CALL),
                2
            ],
        ];
    }

    /**
     * @dataProvider dataDuplicateCommands
     * @param Command $command1
     * @param Command $command2
     * @param int $expectedCount
     */
    public function testAddDuplicateCommand($command1, $command2, $expectedCount)
    {
        $command1->setElevator($this->module->getElevators()[0]);
        $command2->setElevator($this->module->getElevators()[0]);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);

        $this->assertCount($expectedCount, $this->module->getCommands());
    }

    public function dataRoutes()
    {
        return [
            'up3 up2' => [
                [
                    new Command(3, 'up', Command::BUTTON_DIRECTION),
                    new Command(2, 'up', Command::BUTTON_DIRECTION)
                ],
                [1,0],
            ],
            'up3 up4' => [
                [
                    new Command(3, 'up', Command::BUTTON_DIRECTION),
                    new Command(4, 'up', Command::BUTTON_DIRECTION)
                ],
                [0,1],
            ],
            'up3 down2' => [
                [
                    new Command(3, 'up', Command::BUTTON_DIRECTION),
                    new Command(2, 'down', Command::BUTTON_DIRECTION)
                ],
                [0,1],
            ],
            'up2 down4' => [
                [
                    new Command(2, 'up', Command::BUTTON_DIRECTION),
                    new Command(4, 'down', Command::BUTTON_DIRECTION)
                ],
                [0,1],
            ],
            'up3 down3' => [
                [
                    new Command(3, 'up', Command::BUTTON_DIRECTION),
                    new Command(3, 'down', Command::BUTTON_DIRECTION)
                ],
                [0],
            ],
            'up3 num2' => [
                [
                    new Command(3, 'up', Command::BUTTON_DIRECTION),
                    new Command(2, '2', Command::BUTTON_NUMBER)
                ],
                [1,0],
            ],
            'up3 num4' => [
                [
                    new Command(3, 'up', Command::BUTTON_DIRECTION),
                    new Command(4, '4', Command::BUTTON_NUMBER)
                ],
                [0,1],
            ],
            'up3 num3' => [
                [
                    new Command(3, 'up', Command::BUTTON_DIRECTION),
                    new Command(3, '3', Command::BUTTON_NUMBER)
                ],
                [0],
            ],
            'down3 down2' => [
                [
                    new Command(3, 'down', Command::BUTTON_DIRECTION),
                    new Command(2, 'down', Command::BUTTON_DIRECTION)
                ],
                [0,1],
            ],
            'down3 down4' => [
                [
                    new Command(3, 'down', Command::BUTTON_DIRECTION),
                    new Command(4, 'down', Command::BUTTON_DIRECTION)
                ],
                [0,1],
            ],
            'down3 up2' => [
                [
                    new Command(3, 'down', Command::BUTTON_DIRECTION),
                    new Command(2, 'up', Command::BUTTON_DIRECTION)
                ],
                [1,0],
            ],
            'down3 up4' => [
                [
                    new Command(3, 'down', Command::BUTTON_DIRECTION),
                    new Command(4, 'up', Command::BUTTON_DIRECTION)
                ],
                [0,1],
            ],
            'down3 up3' => [
                [
                    new Command(3, 'down', Command::BUTTON_DIRECTION),
                    new Command(3, 'up', Command::BUTTON_DIRECTION)
                ],
                [0, 1],
            ],
            'down3 num2' => [
                [
                    new Command(3, 'down', Command::BUTTON_DIRECTION),
                    new Command(2, '2', Command::BUTTON_NUMBER)
                ],
                [1,0],
            ],
            'down2 num4' => [
                [
                    new Command(2, 'down', Command::BUTTON_DIRECTION),
                    new Command(4, '4', Command::BUTTON_NUMBER)
                ],
                [0,1],
            ],
            'down2 numb2' => [
                [
                    new Command(2, 'down', Command::BUTTON_DIRECTION),
                    new Command(2, '2', Command::BUTTON_NUMBER)
                ],
                [0],
            ],
            'num3 num2' => [
                [
                    new Command(3, '3', Command::BUTTON_NUMBER),
                    new Command(2, '2', Command::BUTTON_NUMBER)
                ],
                [1,0],
            ],
            'num3 num4' => [
                [
                    new Command(3, '3', Command::BUTTON_NUMBER),
                    new Command(4, '4', Command::BUTTON_NUMBER)
                ],
                [0,1],
            ],
            'num3 up2' => [
                [
                    new Command(3, '3', Command::BUTTON_NUMBER),
                    new Command(2, 'up', Command::BUTTON_DIRECTION)
                ],
                [1,0],
            ],
            'num3 up4' => [
                [
                    new Command(3, '3', Command::BUTTON_NUMBER),
                    new Command(4, 'up', Command::BUTTON_DIRECTION)
                ],
                [0,1],
            ],
            'num3 up3' => [
                [
                    new Command(3, '3', Command::BUTTON_NUMBER),
                    new Command(3, 'up', Command::BUTTON_DIRECTION)
                ],
                [0, 1],
            ],
            'num3 down2' => [
                [
                    new Command(3, '3', Command::BUTTON_NUMBER),
                    new Command(2, 'down', Command::BUTTON_DIRECTION)
                ],
                [0,1],
            ],
            'num3 down3' => [
                [
                    new Command(3, '3', Command::BUTTON_NUMBER),
                    new Command(3, 'down', Command::BUTTON_DIRECTION)
                ],
                [0],
            ],
            'num3 down4' => [
                [
                    new Command(3, '3', Command::BUTTON_NUMBER),
                    new Command(4, 'down', Command::BUTTON_DIRECTION)
                ],
                [0,1],
            ],
            'call3 call2' => [
                [
                    new Command(3, '3', Command::BUTTON_CALL),
                    new Command(2, '2', Command::BUTTON_CALL)
                ],
                [1,0],
            ],
            'call3 call4' => [
                [
                    new Command(3, '3', Command::BUTTON_CALL),
                    new Command(4, '4', Command::BUTTON_CALL)
                ],
                [0,1],
            ],
            'call3 num2' => [
                [
                    new Command(3, '3', Command::BUTTON_CALL),
                    new Command(2, '2', Command::BUTTON_CALL)
                ],
                [1,0],
            ],
            'call3 num4' => [
                [
                    new Command(3, '3', Command::BUTTON_CALL),
                    new Command(4, '4', Command::BUTTON_CALL)
                ],
                [0,1],
            ],
        ];
    }

    /**
     * @dataProvider dataRoutes
     * @param Command[] $commands
     * @param array $expectedRoutesIndexes
     */
    public function testUpdateRoute($commands, $expectedRoutesIndexes)
    {

        foreach ($commands as $command) {
            $command->setElevator($this->module->getElevators()[0]);
            $this->module->addCommand($command);
        }

        $this->assertCount(count($expectedRoutesIndexes), $this->module->getElevators()[0]->getRoutes());

        if (count($expectedRoutesIndexes) > 1) {
            $this->assertEquals([new Route($commands[$expectedRoutesIndexes[0]]), new Route($commands[$expectedRoutesIndexes[1]])],
                $this->module->getElevators()[0]->getRoutes());
        } else {
            $this->assertEquals([new Route($commands[$expectedRoutesIndexes[0]])],
                $this->module->getElevators()[0]->getRoutes());
        }

        $this->assertEquals(new Route($commands[$expectedRoutesIndexes[0]]), $this->module->getElevators()[0]->getCurrentRoute());
    }

    public function dataStop()
    {
        return [
            'lift was stopped' => ['stop', 'none'],
            'lift was not stopped' => ['none', 'stop'],
        ];
    }

    /**
     * @dataProvider dataStop
     * @param string $directionBefore
     * @param string $directionExpected
     */
    public function testStop($directionBefore, $directionExpected)
    {
        $this->module->getElevators()[0]->setDirection($directionBefore);
        $this->module->getElevators()[0]->stop();

        $this->assertEquals($directionExpected, $this->module->getElevator()->getDirection());
    }

    public function dataMove()
    {
        return [
            'lift stopped' => ['stop', new Command(4, 'up', Command::BUTTON_DIRECTION), 1, 0, 'stop'],
            'no routes' => ['none', null, 1, 0, 'none'],
            'move up' => ['none', new Command(4, 'up', Command::BUTTON_DIRECTION), 1, 1, 'up'],
            'move down' => ['none', new Command(1, 'down', Command::BUTTON_DIRECTION), 4, -1, 'down'],
            'move current level' => ['none', new Command(1, 'up', Command::BUTTON_DIRECTION), 1, 0, 'none'],
        ];
    }

    /**
     * @dataProvider dataMove
     * @param string $direction
     * @param Command $command
     * @param int $currentLevel
     * @param float $expectedSpeed
     * @param string $expectedDirection
     */
    public function testStartMove($direction, $command, $currentLevel, $expectedSpeed, $expectedDirection)
    {
        $this->module->getElevators()[0]->setDirection($direction);
        $this->module->getElevators()[0]->setCurrentLevel($currentLevel);
        if ($command ==! null){
            $command->setElevator($this->module->getElevators()[0]);
            $this->module->addCommand($command);
        }

        $this->module->getElevators()[0]->startMoving();

        $this->assertEquals($expectedSpeed, $this->module->getElevators()[0]->getCurrentSpeed());
        $this->assertEquals($expectedDirection, $this->module->getElevators()[0]->getDirection());
    }

    public function dataStatus()
    {
        // direction, route, currentSpeed, startTime, endTime, expected
        return [
            'stop' => [null ,'stop', new Command(3, 'up', Command::BUTTON_DIRECTION), 1, 0, 1, 2, 'stop', 0, 'stop', 0],
            'not moving and then moving' => [null ,'none', new Command(2, '2', Command::BUTTON_NUMBER), 1, 0, 1, 1.5, 'none', 0, 'up', 1],
            'moving up and up' => [new Command(4, 'up', Command::BUTTON_DIRECTION),'up', new Command(4, 'up', Command::BUTTON_DIRECTION), 2, 1, 1, 2, 'up', 1, 'up', 1],
            'moving up and get direction level' => [new Command(3, 'up', Command::BUTTON_DIRECTION),'up', new Command(3, 'up', Command::BUTTON_DIRECTION), 2, 1, 1, 2, 'up', 1, 'none', 0],
            'moving down and down' => [new Command(1, 'down', Command::BUTTON_DIRECTION),'down', new Command(1, 'down', Command::BUTTON_DIRECTION), 3, -1, 1, 2, 'down', -1, 'down', -1],
            'moving down and get direction level' => [new Command(1, 'down', Command::BUTTON_DIRECTION),'down', new Command(1, 'down', Command::BUTTON_DIRECTION), 2, -1, 1, 2, 'down', -1, 'none',0],
        ];
    }

    /**
     * @dataProvider dataStatus
     * @param $firstCommand
     * @param string $direction
     * @param Command $command
     * @param float $currentPosition
     * @param float $currentSpeed
     * @param float $startTime
     * @param float $endTime
     * @param string $expectedStartDirection
     * @param float $expectedStartSpeed
     * @param string $expectedEndDirection
     * @param float $expectedEndSpeed
     */
    public function testStatus($firstCommand ,$direction, $command, $currentPosition, $currentSpeed, $startTime, $endTime,
        $expectedStartDirection, $expectedStartSpeed, $expectedEndDirection, $expectedEndSpeed)
    {
        if ($firstCommand !== null) {
            $firstCommand->setElevator($this->module->getElevators()[0]);
            $this->module->addCommand($firstCommand);
            $this->module->getElevators()[0]->getCurrentRoute()->setStartTime($startTime);
        }
        $this->module->getElevator()->setPosition($currentPosition);
        $this->module->getElevator()->setDirection($direction);
        $this->module->getElevator()->setCurrentSpeed($currentSpeed);

        $this->module->getElevators()[0]->status($startTime);
        $this->assertEquals($expectedStartDirection, $this->module->getElevator()->getDirection());
        $this->assertEquals($expectedStartSpeed, $this->module->getElevator()->getCurrentSpeed());

        $command->setElevator($this->module->getElevators()[0]);
        $this->module->addCommand($command);
        $this->module->getElevators()[0]->startMoving();

        $this->module->getElevators()[0]->status($endTime);
        $this->assertEquals($expectedEndDirection, $this->module->getElevator()->getDirection());
        $this->assertEquals($expectedEndSpeed, $this->module->getElevator()->getCurrentSpeed());
    }

    public function dataEndMoving()
    {
        return [
            'up2 up3' => [
                new Command(2, 'up', Command::BUTTON_DIRECTION),
                new Command(3, 'up', Command::BUTTON_DIRECTION),
                'up', 2,
                false, true, false, 'none', 0
                ],
            'up2 num3' => [
                new Command(2, 'up', Command::BUTTON_DIRECTION),
                new Command(3, '3', Command::BUTTON_NUMBER),
                'up', 2,
                false, true, false, 'none', 0
            ],
            'up2 num2' => [
                new Command(2, 'up', Command::BUTTON_DIRECTION),
                new Command(2, '2', Command::BUTTON_NUMBER),
                'up', 2,
                false, false, true, 'none', 0
            ],
            'down2 num2' => [
                new Command(2, 'down', Command::BUTTON_DIRECTION),
                new Command(2, '2', Command::BUTTON_NUMBER),
                'down', 2,
                false, false, true, 'none', 0
            ],
            'down2 num3' => [
                new Command(2, 'down', Command::BUTTON_DIRECTION),
                new Command(3, '3', Command::BUTTON_NUMBER),
                'down', 2,
                false, true, false, 'none', 0
            ],
            'num2 num3' => [
                new Command(2, '2', Command::BUTTON_NUMBER),
                new Command(3, '3', Command::BUTTON_NUMBER),
                '2', 2,
                false, true, false, 'none', 0
            ],
        ];
    }

    /**
     * @dataProvider dataEndMoving
     * @param Command $command1
     * @param Command $command2
     * @param bool $expFirstInArray
     * @param bool $expSecondInArray
     * @param bool $expRoutesExist
     * @param string $expDirection
     * @param float $expSpeed
     */
    public function testEndMoving($command1, $command2, $direction, $level, $expFirstInArray, $expSecondInArray, $expRoutesExist, $expDirection, $expSpeed)
    {
        $command1->setElevator($this->module->getElevators()[0]);
        $command2->setElevator($this->module->getElevators()[0]);
        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->getElevators()[0]->setCurrentLevel($level);
        $this->module->getElevators()[0]->setDirection($direction);

        $this->module->getElevators()[0]->endMoving();

        $this->assertEquals(null == $this->module->getElevators()[0]->getRoutes(), $expRoutesExist);
        $this->assertEquals($expDirection, $this->module->getElevators()[0]->getDirection());
        $this->assertEquals($expSpeed, $this->module->getElevators()[0]->getCurrentSpeed());
    }

    public function dataAddPassengers()
    {
        return [
            'add passengers on different level' => [
                new Passengers(1,1,1),
                new Passengers(2,2,2),
                2,
                1, 1, 1,
            ],
            'add passengers on same level' => [
                new Passengers(1,1,1),
                new Passengers(1,2,2),
                1,
                1, 3, 3,
            ]
        ];
    }

    /**
     * @dataProvider dataAddPassengers
     * @param Passengers $pass1
     * @param Passengers $pass2
     * @param int $expectedCount
     * @param int $expectedFirstObjectLevel
     * @param int $expectedFirstObjectIn
     * @param int $expectedFirstObjectOut
     */
    public function testAddPassengers($pass1, $pass2, $expectedCount, $expectedFirstObjectLevel,
        $expectedFirstObjectIn, $expectedFirstObjectOut)
    {
        $this->module->getElevators()[0]->addPassangers($pass1);
        $this->module->getElevators()[0]->addPassangers($pass2);

        $this->assertCount($expectedCount ,$this->module->getElevators()[0]->getPassengers());
        $this->assertEquals($expectedFirstObjectLevel, $this->module->getElevators()[0]->getPassengers()[0]->getLevel());
        $this->assertEquals($expectedFirstObjectIn, $this->module->getElevators()[0]->getPassengers()[0]->getIn());
        $this->assertEquals($expectedFirstObjectOut, $this->module->getElevators()[0]->getPassengers()[0]->getOut());
    }

    public function dataPassQty()
    {
        return [
            'there are not people on this level, but on other' => [1, 1, new Passengers(2,2,2), 1, false],
            'there are not people on this level' => [2, 3, new Passengers(2, 0 ,0), 3, false],
            'there are only people to in' => [1, 3, new Passengers(1, 2 ,0), 5, false],
            'there are only people to out' => [2, 5, new Passengers(2, 0 ,4), 1, false],
            'there are people to in and out' => [3, 4, new Passengers(3, 4 ,3), 5, false],
            'there is overweight situation' => [2, 9, new Passengers(2, 5 ,1), 13, true],
        ];
    }

    /**
     * @dataProvider dataPassQty
     * @param int $level
     * @param int $startQty
     * @param Passengers $pass
     * @param int $expectedQty
     * @param bool $isOverWeight
     */
    public function testUpdatePassengersQty($level, $startQty, $pass, $expectedQty, $isOverWeight)
    {
        $this->module->getElevators()[0]->addPassangers($pass);
        $this->module->getElevator()->setPassengersQty($startQty);
        $this->module->getElevators()[0]->updatePassengersQty($level);

        $this->assertEquals($expectedQty, $this->module->getElevator()->getPassengersQty());
        $this->assertEquals($isOverWeight, $this->module->getElevator()->isOverWeight());
    }

    public function testOverWeightStatus()
    {
        $command1 = new Command(2, '2', Command::BUTTON_NUMBER);
        $command2 = new Command(4, '4', Command::BUTTON_NUMBER);
        $command1->setElevator($this->module->getElevators()[0]);
        $command2->setElevator($this->module->getElevators()[0]);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);

        $this->module->getElevators()[0]->startMoving();
        $this->module->getElevators()[0]->getCurrentRoute()->setStartTime(1);
        $this->module->getElevators()[0]->addPassangers(new Passengers(2, 5, 1));
        $this->module->getElevators()[0]->setPassengersQty(10);

        $this->module->getElevators()[0]->status(2);
        $this->module->getElevators()[0]->updatePassengersQty(2);

        $this->assertEquals(0, $this->module->getElevators()[0]->getCurrentSpeed());
        $this->assertEquals(2, $this->module->getElevators()[0]->getCurrentLevel());

        $this->module->getElevators()[0]->exitPassengers(2, 4);

        $this->assertEquals(4, $this->module->getElevators()[0]->getPassengers()[0]->getIn());
        $this->assertEquals(10, $this->module->getElevators()[0]->getPassengersQty());

        $this->module->getElevators()[0]->getCurrentRoute()->setStartTime(1);
        $this->module->getElevators()[0]->startMoving();

        $this->assertEquals(1, $this->module->getElevators()[0]->getCurrentSpeed());
    }

    public function testGetClosestElevator()
    {
        $command = new Command(3, '3', Command::BUTTON_NUMBER);
        $command->setElevator($this->module->getElevators()[0]);
        $this->module->addElevator(new Elevator(1));
        $this->module->addCommand($command);

        $this->assertEquals([new Route($command)], $this->module->getElevators()[0]->getRoutes());
        $this->assertEquals([], $this->module->getElevators()[1]->getRoutes());
    }
}