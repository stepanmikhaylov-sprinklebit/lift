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
        $building = new Building(4, 1);
        $this->module = new Module($elevator, $building);
    }

    public function testAddCommand()
    {
        $command1 = new Command(1, 4, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(2, 3, 'up', Command::BUTTON_DIRECTION);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);

        $commands = $this->module->getCommands();

        $this->assertEquals([$command1, $command2], $commands);
    }

    public function testAddDuplicateCommand()
    {
        $command1 = new Command(4, 3, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(2, 3, 'up', Command::BUTTON_DIRECTION);
        $command3 = new Command(2, 3, 'down', Command::BUTTON_DIRECTION);
        $command4 = new Command(1, 3, 'down', Command::BUTTON_DIRECTION);
        $command5 = new Command(3, 2, 'up', Command::BUTTON_DIRECTION);
        $command6 = new Command(3, 1, 'down', Command::BUTTON_DIRECTION);
        $command7 = new Command(4, 4, '4', Command::BUTTON_NUMBER);
        $command8 = new Command(2, 4, '4', Command::BUTTON_NUMBER);
        $command9 = new Command(2, 2, '2', Command::BUTTON_NUMBER);
        $command10 = new Command(1, 1, '1', Command::BUTTON_NUMBER);
        $command11 = new Command(1, 3, '3', Command::BUTTON_NUMBER);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->addCommand($command3);
        $this->module->addCommand($command4);
        $this->module->addCommand($command5);
        $this->module->addCommand($command6);
        $this->module->addCommand($command7);
        $this->module->addCommand($command8);
        $this->module->addCommand($command9);
        $this->module->addCommand($command10);
        $this->module->addCommand($command11);

        $commands = [
            $command1,
            $command3,
            $command5,
            $command6,
            $command7,
            $command9,
            $command10,
            $command11,
        ];
        $this->assertEquals($commands, $this->module->getCommands());
    }

    public function testAddDuplicateRoute()
    {
        $command1 = new Command(4, 3, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(2, 3, 'up', Command::BUTTON_DIRECTION);
        $command3 = new Command(2, 3, 'down', Command::BUTTON_DIRECTION);
        $command4 = new Command(1, 3, 'down', Command::BUTTON_DIRECTION);
        $command5 = new Command(3, 2, 'up', Command::BUTTON_DIRECTION);
        $command6 = new Command(3, 1, 'down', Command::BUTTON_DIRECTION);
        $command7 = new Command(4, 4, '4', Command::BUTTON_NUMBER);
        $command8 = new Command(2, 4, '4', Command::BUTTON_NUMBER);
        $command9 = new Command(2, 2, '2', Command::BUTTON_NUMBER);
        $command10 = new Command(1, 1, '1', Command::BUTTON_NUMBER);
        $command11 = new Command(1, 3, '3', Command::BUTTON_NUMBER);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->addCommand($command3);
        $this->module->addCommand($command4);
        $this->module->addCommand($command5);
        $this->module->addCommand($command6);
        $this->module->addCommand($command7);
        $this->module->addCommand($command8);
        $this->module->addCommand($command9);
        $this->module->addCommand($command10);
        $this->module->addCommand($command11);

        $routes = [
            new Route($command10),
            new Route($command5),
            new Route($command2),
            new Route($command6),
            new Route($command7),
        ];
        $this->assertEquals($routes, $this->module->getRoutes());
    }

    //ToDo: continue
    public function testAddDuplicateRoute2()
    {
        $command1 = new Command(4, 3, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(2, 3, 'up', Command::BUTTON_DIRECTION);
        $command12 = new Command(1, 4, 'up', Command::BUTTON_DIRECTION);
        $command3 = new Command(2, 3, 'down', Command::BUTTON_DIRECTION);
        $command4 = new Command(1, 3, 'down', Command::BUTTON_DIRECTION);
        $command5 = new Command(3, 2, 'up', Command::BUTTON_DIRECTION);
        $command6 = new Command(3, 1, 'down', Command::BUTTON_DIRECTION);
        $command7 = new Command(4, 4, '4', Command::BUTTON_NUMBER);
        $command8 = new Command(2, 4, '4', Command::BUTTON_NUMBER);
        $command9 = new Command(2, 2, '2', Command::BUTTON_NUMBER);
        $command10 = new Command(1, 1, '1', Command::BUTTON_NUMBER);
        $command11 = new Command(1, 3, '3', Command::BUTTON_NUMBER);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->addCommand($command12);
        $this->module->addCommand($command3);
        $this->module->addCommand($command4);
        $this->module->addCommand($command5);
        $this->module->addCommand($command6);
        $this->module->addCommand($command7);
        $this->module->addCommand($command8);
        $this->module->addCommand($command9);
        $this->module->addCommand($command10);
        $this->module->addCommand($command11);

        $routes = [
            new Route($command10),
            new Route($command5),
            new Route($command2),
            new Route($command12),
            new Route($command3),
            new Route($command6),
        ];
        $this->assertEquals($routes, $this->module->getRoutes());
    }


    public function testAddRoutes()
    {
        $command1 = new Command(1, 4, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(3, 3, 'up', Command::BUTTON_DIRECTION);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);

        $routes = $this->module->getRoutes();

        $this->assertEquals([new Route($command2), new Route($command1)], $routes);
    }

    public function AddRoutesUp()
    {
        $command1 = new Command(1, 4, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(2, 2, 'up', Command::BUTTON_DIRECTION);

        $this->module->addCommand($command1);

        $status = $this->module->startMoving();
        $this->assertEquals('Lift on 1 level. Moves to 4 level', $status);
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $this->module->addCommand($command2);

        $status = $this->module->startMoving();
        $this->assertEquals('Lift on 1 level. Moves to 2 level', $status);
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $this->module->getElevator()->setPosition(2);
        $status = $this->module->status();
        $this->assertEquals('Lift on 2 level', $status);
        $this->assertEquals('none', $this->module->getElevator()->getDirection());

        $status = $this->module->startMoving();
        $this->assertEquals('Lift on 2 level. Moves to 4 level', $status);
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $this->module->getElevator()->setPosition(3);
        $status = $this->module->status();
        $this->assertEquals('Lift on 3 level. Moves to 4 level',$status);
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $this->module->getElevator()->setPosition(4);
        $status = $this->module->status();
        $this->assertEquals('Lift on 4 level',$status);
        $this->assertEquals('none', $this->module->getElevator()->getDirection());
    }

    public function testListRoutesOnlyUp()
    {
        $command1 = new Command(4, 4, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(3, 3, 'up', Command::BUTTON_DIRECTION);
        $command3 = new Command(2, 2, 'up', Command::BUTTON_DIRECTION);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->addCommand($command3);

        $this->assertEquals([new Route($command3), new Route($command2), new Route($command1)], $this->module->getRoutes());
    }

    public function testListRoutesUpDown1()
    {
        $command1 = new Command(4, 4, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(3, 3, 'up', Command::BUTTON_DIRECTION);
        $command3 = new Command(1, 1, 'up', Command::BUTTON_DIRECTION);
        $command4 = new Command(2, 2, 'down', Command::BUTTON_DIRECTION);
        $command5 = new Command(3, 3, 'down', Command::BUTTON_DIRECTION);
        $command6 = new Command(1, 1, 'down', Command::BUTTON_DIRECTION);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->addCommand($command3);
        $this->module->addCommand($command4);
        $this->module->addCommand($command5);
        $this->module->addCommand($command6);

        $routes = [
            new Route($command3),
            new Route($command2),
            new Route($command1),
            new Route($command5),
            new Route($command4),
            new Route($command6)
        ];
        $this->assertEquals($routes, $this->module->getRoutes());
    }

    public function testRoutesUpDown2()
    {
        $command1 = new Command(3, 3, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(2, 2, 'down', Command::BUTTON_DIRECTION);
        $command3 = new Command(4, 4, 'up', Command::BUTTON_DIRECTION);
        $command4 = new Command(1, 1, 'down', Command::BUTTON_DIRECTION);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->addCommand($command3);
        $this->module->addCommand($command4);

        $routes = [
            new Route($command1),
            new Route($command2),
            new Route($command3),
            new Route($command4),
        ];
        $this->assertEquals($routes, $this->module->getRoutes());
    }

    public function testRoutesUpDown3()
    {
        $command1 = new Command(4, 4, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(1, 1, 'down', Command::BUTTON_DIRECTION);
        $command3 = new Command(3, 3, 'up', Command::BUTTON_DIRECTION);
        $command4 = new Command(2, 2, 'up', Command::BUTTON_DIRECTION);
        $command5 = new Command(3, 3, 'down', Command::BUTTON_DIRECTION);
        $command6 = new Command(2, 2, 'down', Command::BUTTON_DIRECTION);


        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->addCommand($command3);
        $this->module->addCommand($command4);
        $this->module->addCommand($command5);
        $this->module->addCommand($command6);

        $routes = [
            new Route($command4),
            new Route($command3),
            new Route($command1),
            new Route($command5),
            new Route($command6),
            new Route($command2),
        ];
        $this->assertEquals($routes, $this->module->getRoutes());
    }

    public function testRoutesWithNumbersDown()
    {
        $command1 = new Command(4, 4, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(1, 3, '3', Command::BUTTON_NUMBER);
        $command3 = new Command(1, 2, '2', Command::BUTTON_NUMBER);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->addCommand($command3);

        $routes = [
            new Route($command3),
            new Route($command2),
            new Route($command1),
        ];
        $this->assertEquals($routes, $this->module->getRoutes());
    }

    public function testRoutesWithNumbersUpDown()
    {
        $command1 = new Command(4, 4, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(2, 2, '2', Command::BUTTON_NUMBER);
        $command3 = new Command(2, 2, 'down', Command::BUTTON_DIRECTION);
        $command4 = new Command(1, 1, 'down', Command::BUTTON_DIRECTION);
        $command5 = new Command(3, 3, '3', Command::BUTTON_NUMBER);
        $command6 = new Command(3, 3, 'down', Command::BUTTON_DIRECTION);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->addCommand($command3);
        $this->module->addCommand($command4);
        $this->module->addCommand($command5);
        $this->module->addCommand($command6);

        $routes = [
            new Route($command2),
            new Route($command5),
            new Route($command1),
            new Route($command6),
            new Route($command3),
            new Route($command4),
        ];
        $this->assertEquals($routes, $this->module->getRoutes());
    }

    public function MoveOnce()
    {
        $command1 = new Command(1, 4, 'up', Command::BUTTON_DIRECTION);

        $this->module->addCommand($command1);

        $status = $this->module->startMoving();
        $this->assertEquals('Lift on 1 level. Moves to 4 level', $status);
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $this->module->getElevator()->setCurrentLevel(3);
        $status = $this->module->status();
        $this->assertEquals('Lift on 3 level. Moves to 4 level', $status);
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $this->module->getElevator()->setCurrentLevel(4);
        $status = $this->module->status();
        $this->assertEquals('Lift on 4 level',$status);
        $this->assertEquals('none', $this->module->getElevator()->getDirection());
    }

    public function MoveTwiceUp()
    {
        $command1 = new Command(1, 2, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(2, 4, 'up', Command::BUTTON_DIRECTION);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);

        $status = $this->module->startMoving();
        $this->assertEquals('Lift on 1 level. Moves to 2 level', $status);
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $this->module->getElevator()->setCurrentLevel(2);
        $status = $this->module->status();
        $this->assertEquals('Lift on 2 level', $status);
        $this->assertEquals('none', $this->module->getElevator()->getDirection());


        $status = $this->module->startMoving();
        $this->assertEquals('Lift on 2 level. Moves to 4 level', $status);
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $this->module->getElevator()->setCurrentLevel(3);
        $status = $this->module->status();
        $this->assertEquals('Lift on 3 level. Moves to 4 level',$status);
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $this->module->getElevator()->setCurrentLevel(4);
        $status = $this->module->status();
        $this->assertEquals('Lift on 4 level',$status);
        $this->assertEquals('none', $this->module->getElevator()->getDirection());
    }

    public function testStopButtonRoute()
    {
        $command1 = new Command(4, 3, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(2, 3, 'up', Command::BUTTON_DIRECTION);
        $command3 = new Command(2, 2, 'stop', Command::BUTTON_STOP);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->addCommand($command3);

        $this->assertEquals('stop', $this->module->getElevator()->getDirection());
        $this->assertEquals(0, $this->module->getElevator()->getCurrentSpeed());
    }

    public function testStopButtonTwice()
    {
        $command1 = new Command(4, 3, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(2, 3, 'up', Command::BUTTON_DIRECTION);
        $command3 = new Command(2, 2, 'stop', Command::BUTTON_STOP);
        $command4 = new Command(2, 2, 'stop', Command::BUTTON_STOP);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);
        $this->module->addCommand($command3);
        $this->module->addCommand($command4);

        $this->assertEquals('none', $this->module->getElevator()->getDirection());
        $this->assertEquals(0, $this->module->getElevator()->getCurrentSpeed());
    }

    public function testMovingUp()
    {
        $command1 = new Command(2, 2, 'up', Command::BUTTON_DIRECTION);
        $command2 = new Command(4, 4, 'up', Command::BUTTON_DIRECTION);

        $this->module->addCommand($command1);
        $this->module->addCommand($command2);

        $this->module->startMoving();
        $this->module->getCurrentRoute()->setStartTime(1);

        $this->assertEquals(1, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $currentTime = 1.5;

        $this->module->status($currentTime);
        $this->assertEquals(1, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $currentTime = 2;

        $this->module->status($currentTime);
        $this->assertEquals(0, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('none', $this->module->getElevator()->getDirection());
        $this->assertFalse(in_array($command1, $this->module->getCommands()));

        $this->module->startMoving();
        $this->module->getCurrentRoute()->setStartTime(1);
        $this->assertEquals(1, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $currentTime = 3;

        $this->module->status($currentTime);
        $this->assertEquals(0, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('none', $this->module->getElevator()->getDirection());
        $this->assertFalse(in_array($command2, $this->module->getCommands()));
    }

    public function testMovingUpDownNumbers()
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

        $this->module->startMoving();
        $this->module->getCurrentRoute()->setStartTime(1);

        $this->assertEquals(0, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('none', $this->module->getElevator()->getDirection());
        $this->module->status(1);

        $this->module->startMoving();
        $this->module->getCurrentRoute()->setStartTime(1);
        $currentTime = 1.5;

        $this->module->status($currentTime);
        $this->assertEquals(1, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $currentTime = 2;

        $this->module->status($currentTime);
        $this->assertEquals(0, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('none', $this->module->getElevator()->getDirection());
        $this->assertFalse(in_array($command1, $this->module->getCommands()));
        $this->assertFalse(in_array($command8, $this->module->getCommands()));
        $this->assertTrue(in_array($command9, $this->module->getCommands()));

        //3level
        $this->module->startMoving();
        $this->module->getCurrentRoute()->setStartTime(1);
        $this->assertEquals(1, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $currentTime = 2;

        $this->module->status($currentTime);
        $this->assertEquals(0, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('none', $this->module->getElevator()->getDirection());
        $this->assertFalse(in_array($command7, $this->module->getCommands()));

        //4 level
        $this->module->startMoving();
        $this->module->getCurrentRoute()->setStartTime(1);
        $this->assertEquals(1, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('up', $this->module->getElevator()->getDirection());

        $currentTime = 2;

        $this->module->status($currentTime);
        $this->assertEquals(0, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('none', $this->module->getElevator()->getDirection());
        $this->assertFalse(in_array($command2, $this->module->getCommands()));
        $this->assertFalse(in_array($command6, $this->module->getCommands()));

        //3.5 level
        $this->module->startMoving();
        $this->module->getCurrentRoute()->setStartTime(1);
        $this->assertEquals(-1, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('down', $this->module->getElevator()->getDirection());

        $currentTime = 1.5;

        $this->module->status($currentTime);
        $this->assertEquals(-1, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('down', $this->module->getElevator()->getDirection());

        //3 level
        $currentTime = 2;

        $this->module->status($currentTime);
        $this->assertEquals(0, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('none', $this->module->getElevator()->getDirection());
        $this->assertFalse(in_array($command3, $this->module->getCommands()));

        //2 level
        $this->module->startMoving();
        $this->module->getCurrentRoute()->setStartTime(1);
        $this->assertEquals(-1, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('down', $this->module->getElevator()->getDirection());

        $currentTime = 2;

        $this->module->status($currentTime);
        $this->assertEquals(0, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('none', $this->module->getElevator()->getDirection());
        $this->assertFalse(in_array($command9, $this->module->getCommands()));

        //1 level
        $this->module->startMoving();
        $this->module->getCurrentRoute()->setStartTime(1);
        $this->assertEquals(-1, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('down', $this->module->getElevator()->getDirection());

        $currentTime = 2;

        $this->module->status($currentTime);
        $this->assertEquals(0, $this->module->getElevator()->getCurrentSpeed());
        $this->assertEquals('none', $this->module->getElevator()->getDirection());
        $this->assertFalse(in_array($command4, $this->module->getCommands()));
    }
}