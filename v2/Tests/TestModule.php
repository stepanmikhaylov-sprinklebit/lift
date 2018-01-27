<?php
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 27.01.18
 * Time: 18:57
 */

namespace TestV2;


use PHPUnit\Framework\TestCase;
use v2\Entity\Building;
use v2\Entity\Command;
use v2\Entity\Elevator;
use v2\Service\Module;
use v2\Entity\Route;

class TestModule extends TestCase
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

    public function testAddCommand()
    {
        $command1 = new Command(1, 4, 'up');
        $command2 = new Command(2, 3, 'up');

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);

        $commands = $this->module->getCommands();

        $this->assertEquals([$command1, $command2], $commands);
    }

    public function testAddRoutes()
    {
        $command1 = new Command(1, 4, 'up');
        $command2 = new Command(2, 3, 'up');

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);

        $routes = $this->module->getRoutes();

        $this->assertEquals([new Route($command1), new Route($command2)], $routes);
    }

    public function testMove()
    {
        $command1 = new Command(1, 4, 'up');

        $this->module->addCommand($command1);

        $status = $this->module->move();
        $this->assertEquals('Lift on 1 level. Moves to 4 level', $status);

        $this->module->getElevator()->setCurrentLevel(3);
        $status = $this->module->status();
        $this->assertEquals('Lift on 3 level. Moves to 4 level', $status);

        $this->module->getElevator()->setCurrentLevel(4);
        $status = $this->module->status();
        $this->assertEquals('Lift on 4 level',$status);
    }
}