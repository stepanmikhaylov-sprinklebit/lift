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
            $this->module->addCommand($command);
        }

        $this->assertCount(count($expectedRoutesIndexes), $this->module->getRoutes());

        if (count($expectedRoutesIndexes) > 1) {
            $this->assertEquals([new Route($commands[$expectedRoutesIndexes[0]]), new Route($commands[$expectedRoutesIndexes[1]])],
                $this->module->getRoutes());
        } else {
            $this->assertEquals([new Route($commands[$expectedRoutesIndexes[0]])],
                $this->module->getRoutes());
        }

        $this->assertEquals(new Route($commands[$expectedRoutesIndexes[0]]), $this->module->getCurrentRoute());
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
        $this->module->getElevator()->setDirection($directionBefore);
        $this->module->stop();

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
        $this->module->getElevator()->setDirection($direction);
        $this->module->getElevator()->setCurrentLevel($currentLevel);
        if ($command ==! null){
            $this->module->addCommand($command);
        }

        $this->module->startMoving(1);

        $this->assertEquals($expectedSpeed, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals($expectedDirection, $this->module->getElevator()->getDirection());
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
            $this->module->addCommand($firstCommand);
            $this->module->getCurrentRoute()->setStartTime($startTime);
        }
        $this->module->getElevator()->setPosition($currentPosition);
        $this->module->getElevator()->setDirection($direction);
        $this->module->getElevator()->setCurrentSpeed($currentSpeed);

        $this->module->status($startTime, 1);
        $this->assertEquals($expectedStartDirection, $this->module->getElevator()->getDirection());
        $this->assertEquals($expectedStartSpeed, $this->module->getElevator()->getCurrentSpeed());

        $this->module->addCommand($command);
        $this->module->startMoving(1);

        $this->module->status($endTime, 1);
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
        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->getElevator()->setCurrentLevel($level);
        $this->module->getElevator()->setDirection($direction);

        $this->module->endMoving(1);

        if ($this->module->getCommands() !== null) {
            $this->assertEquals($expFirstInArray, in_array($command1, $this->module->getCommands()));
            $this->assertEquals($expSecondInArray, in_array($command2, $this->module->getCommands()));
        } else {
            $this->assertFalse($expFirstInArray);
            $this->assertFalse($expSecondInArray);
        }

        $this->assertEquals(null == $this->module->getRoutes(), $expRoutesExist);
    }
}