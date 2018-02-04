<?php

namespace v2\Service;
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 27.01.18
 * Time: 17:34
 */

use \v2\Entity\Elevator;
use \v2\Entity\Building;
use \v2\Entity\Command;
use \v2\Entity\Route;

class Module
{
    const FILE_COMMANDS = '/home/stepan/Documents/LiftControl/docs/Commands';

    /**
     * @var Elevator
     */
    private $elevator;

    /**
     * @var Building
     */
    private $biulding;

    /**
     * @var Command[]
     */
    private $commands;

    /**
     * @var Route[]
     */
    private $routes;

    public function __construct(Elevator $elevator, Building $building)
    {
        $this->elevator = $elevator;
        $this->biulding = $building;
    }

    /**
     * @return Elevator
     */
    public function getElevator(): Elevator
    {
        return $this->elevator;
    }

    /**
     * @param Elevator $elevator
     */
    public function setElevator(Elevator $elevator)
    {
        $this->elevator = $elevator;
    }

    /**
     * @return Building
     */
    public function getBiulding(): Building
    {
        return $this->biulding;
    }

    /**
     * @param Building $biulding
     */
    public function setBiulding(Building $biulding)
    {
        $this->biulding = $biulding;
    }

    /**
     * @return Command[]
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param Command[] $commands
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;
    }

    /**
     * @param Command $command
     */
    public function addCommand($command)
    {
        if ($this->commands === null) {
            $this->commands[] = $command;
            $this->updateRoute($command);
        } else {
            if ($command->getType() === Command::BUTTON_STOP) {
                $this->stop();
                return;
            } else if ($this->isDuplicate($command)) {
                return;
            }
            $this->commands[] = $command;
            $this->updateRoute($command);
        }
    }

    public function removeCommands($direction, $level)
    {
        $commands = $this->getCommands();
        foreach ($this->commands as $key => $value)
        {
            if ($commands[$key]->getType() === 1) {
                if ($commands[$key]->getValue() === $direction && $commands[$key]->getDestinationLevel() == $level) {
                    unset($this->commands[$key]);
                }
            } else {
                if ( $commands[$key]->getDestinationLevel() == $level ) {
                    unset($this->commands[$key]);
                }
            }
        }
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param Route[] $routes
     */
    public function setRoute(array $routes)
    {
        $this->routes = $routes;
    }

    public function updateRoute(Command $command)
    {
        $route = new Route($command);
        if ($this->routes === null) {
            $this->routes[] = $route;
        } else {
            $routesCount = count($this->routes);
            $elevator = clone $this->elevator;
            for ($i = 0; $i < $routesCount; $i++) {
                switch ($command->getType()) {
                    case Command::BUTTON_DIRECTION:
                        $delta = $this->routes[$i]->getEndLevel() - $elevator->getCurrentLevel();
                        if ($delta > 0) {
                            $elevator->setDirection('up');
                        } else if ($delta < 0) {
                            $elevator->setDirection('down');
                        }

                        if (
                            $elevator->getDirection() === $command->getValue()
                            && $this->routes[$i]->getEndLevel() * $delta > $route->getEndLevel() * $delta
                            && $elevator->getCurrentLevel() * $delta <= $route->getEndLevel() * $delta
                        ) {
                            array_splice($this->routes, $i, 0, [$route]);
                            return;
                        } else if ($elevator->getDirection() !== $command->getValue()
                            && $route->getEndLevel() === $this->routes[$i]->getEndLevel()) {
                            if (!isset($this->routes[$i+1])) {
                                return;
                            } else {
                                if ($this->routes[$i+1]->getEndLevel() * $delta < $route->getEndLevel() * $delta){
                                    return;
                                }
                            }
                        }
                        $elevator->setCurrentLevel($this->routes[$i]->getEndLevel());

                        break;
                    case Command::BUTTON_NUMBER:
                        $delta = $this->routes[$i]->getEndLevel() - $elevator->getCurrentLevel();
                        if ($this->routes[$i]->getEndLevel() * $delta > $route->getEndLevel() * $delta
                            && $elevator->getCurrentLevel() * $delta <= $route->getEndLevel() * $delta
                        ) {
                            array_splice($this->routes, $i, 0, [$route]);
                            return;
                        } else if ($this->routes[$i]->getEndLevel() === $route->getEndLevel()) {
                            return;
                        }
                        $elevator->setCurrentLevel($this->routes[$i]->getEndLevel());

                        break;
                }

            }

            $this->routes[] = $route;
        }

    }

    public function getCurrentRoute()
    {
        return isset($this->routes[0]) ? $this->routes[0] : null;
    }

    public function startMoving()
    {
        $route = $this->getCurrentRoute();

        if ($route === null || $this->elevator->getDirection() === 'stop') {
            return;
        }

        if ($route->getEndLevel() > $this->elevator->getCurrentLevel()) {
            if ($this->elevator->getDirection() === 'none') {
                $this->getCurrentRoute()->setStartTime(microtime(true));
                echo 'Lift on ' . $this->elevator->getCurrentLevel()
                    . ' level. Moves to ' . $this->getCurrentRoute()->getEndLevel() . ' level' . PHP_EOL;
            }

            $this->elevator->setDirection(Elevator::DIRECTION_UP);
            $this->elevator->setCurrentSpeed($this->elevator->getMaxSpeed());
            //$this->getCurrentRoute()->setStartTime(microtime(true));

        } else if ($route->getEndLevel() < $this->elevator->getCurrentLevel()) {
            if ($this->elevator->getDirection() === 'none') {
                $this->getCurrentRoute()->setStartTime(microtime(true));
                echo 'Lift on ' . $this->elevator->getCurrentLevel()
                    . ' level. Moves to ' . $this->getCurrentRoute()->getEndLevel() . ' level' . PHP_EOL;
            }

            $this->elevator->setDirection(Elevator::DIRECTION_DOWN);
            $this->elevator->setCurrentSpeed(-$this->elevator->getMaxSpeed());
//            $this->getCurrentRoute()->setStartTime(microtime(true));
        } else {
            $this->elevator->setDirection(Elevator::DIRECTION_NONE);
            $this->endMoving();
        }

//        return 'Lift on ' . $this->elevator->getCurrentLevel() . ' level. Moves to ' . $this->getCurrentRoute()->getEndLevel() . ' level' . PHP_EOL;
    }

    public function status($time)
    {
        $route = $this->getCurrentRoute();

        $direction = $this->elevator->getDirection();
        $speed = $this->elevator->getCurrentSpeed();

        switch ($direction) {
            case 'none' :
                return;
            case 'up' :
                $position = $this->elevator->getPosition() + $speed * ($time - $this->getCurrentRoute()->getStartTime());
                $this->getCurrentRoute()->setStartTime($time);
                $this->elevator->setPosition($position);
                $this->elevator->setCurrentLevel(floor($position));
                break;
            case 'down' :
                $position = $this->elevator->getPosition() + $speed * ($time - $this->getCurrentRoute()->getStartTime());
                $this->getCurrentRoute()->setStartTime($time);
                $this->elevator->setPosition($position);
                $this->elevator->setCurrentLevel(ceil($position));
                break;
            case 'stop' :
                return;
            default:
                break;
        }
        if ($speed * $route->getEndLevel() <= $speed * $this->elevator->getPosition())
        {
            $this->endMoving();
            return 'Lift on ' . $this->elevator->getCurrentLevel() . ' level';
        }
        return 'Lift on ' . $this->elevator->getCurrentLevel() . ' level. Moves to ' . $this->getCurrentRoute()->getEndLevel() . ' level';
    }

    /**
     * @param Command $command
     * @return bool
     */
    public function isDuplicate($command)
    {
        switch ($command->getType()) {
            case Command::BUTTON_DIRECTION:
                foreach ($this->commands as $item) {
                    if ($item->getValue() === $command->getValue()
                        && $item->getDestinationLevel() === $command->getDestinationLevel()) {
                        return true;
                    }
                }
                break;
            case Command::BUTTON_NUMBER:
                foreach ($this->commands as $item) {
                    if ($item->getDestinationLevel() === $command->getDestinationLevel()
                        && $item->getType() === Command::BUTTON_NUMBER) {
                        return true;
                    }
                }
                break;
        }
        return false;
    }

    public function stop()
    {
        if ($this->elevator->getDirection() === 'stop') {
            $this->elevator->setDirection('none');
            echo 'Lift continue moving' . PHP_EOL;

            return;
        }
        $this->elevator->setDirection('stop');
        $this->elevator->setCurrentSpeed(0);

        echo 'Lift stopped' . PHP_EOL;
    }

    public function endMoving()
    {
        $this->removeRoute();
        $this->removeCommands($this->elevator->getDirection(), $this->elevator->getCurrentLevel());
        $this->elevator->setDirection(Elevator::DIRECTION_NONE);
        $this->elevator->setCurrentSpeed(0);
        echo 'Lift on ' . $this->elevator->getCurrentLevel() . ' level' . PHP_EOL ;
        $this->openDoors();
    }

    public function removeRoute()
    {
        array_splice($this->routes, 0, 1);
    }

    public function calculateMovingTime()
    {

    }

    public function openDoors()
    {
        echo "The doors are opening" . PHP_EOL;
        echo "The doors are closing" . PHP_EOL;
    }

    public function run($time)
    {
        $startTime = microtime(true);
        while (microtime(true) - $startTime < $time) {
            $this->startMoving();
            $this->status(microtime(true));
            $this->updateCommands();
        }
    }

    public function updateCommands()
    {
        $file = fopen(Module::FILE_COMMANDS, 'r+');
        flock($file, LOCK_EX);

        $commandsSerialize = fread($file, 10000);
        $commands = explode(PHP_EOL, $commandsSerialize);
        array_pop($commands);

        foreach ($commands as $command) {
            $this->addCommand(unserialize($command, [Command::class]));
        }
        ftruncate($file, 0);
        flock($file, LOCK_UN);

        fclose($file);
    }

    /**
     * @param Command $command
     */
    public function addCommandToFile($command)
    {
        $file = fopen(Module::FILE_COMMANDS, 'a');
        flock($file, LOCK_EX);
        fwrite($file, serialize($command) . PHP_EOL);
        flock($file, LOCK_UN);
        fclose($file);
    }
}