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
        $elevator = new Elevator(1);
        $building = new Building(4, 1);
        $this->module = new Module($elevator, $building);
    }

    public function dataAddCommand()
    {
        return [
            'button direction' => [new Command(4, 'up', Command::BUTTON_DIRECTION), true],
            'button number' => [new Command(2, '2', Command::BUTTON_NUMBER), true],
            'button stop' => [new Command(1, 'stop', Command::BUTTON_STOP), false],
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
    }
}