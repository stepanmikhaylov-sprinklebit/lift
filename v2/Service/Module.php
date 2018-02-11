<?php

namespace v2\Service;
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 27.01.18
 * Time: 17:34
 */

use v2\Entity\Passengers;
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
    private $building;

    /**
     * @var Command[]
     */
    private $commands;

    /**
     * @var Route[]
     */
    private $routes;

    /**
     * @var Passengers[]
     */
    private $passengers;

    /**
     * @var Elevator[]
     */
    private $elevators;

    public function __construct(Elevator $elevator, Building $building)
    {
        $this->elevator = $elevator;
        $this->building = $building;
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
    public function setElevator(Elevator $elevator): void
    {
        $this->elevator = $elevator;
    }

    /**
     * @return Building
     */
    public function getBuilding(): Building
    {
        return $this->building;
    }

    /**
     * @param Building $building
     */
    public function setBuilding(Building $building): void
    {
        $this->building = $building;
    }

    /**
     * @return Command[]
     */
    public function getCommands(): ?array
    {
        return $this->commands;
    }

    /**
     * @param Command[] $commands
     */
    public function setCommands($commands): void
    {
        $this->commands = $commands;
    }

    /**
     * @param Command $command
     */
    public function addCommand($command) : void
    {
        $elevator = $this->getClosestElevator($command);

        if ($this->commands === null && $command->getType() !== Command::BUTTON_STOP) {
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

    public function removeCommands(string $direction, int $level) : void
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
    public function setRoute(array $routes) : void
    {
        $this->routes = $routes;
    }

    public function updateRoute(Command $command) : void
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
                    case Command::BUTTON_CALL:
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

    public function getCurrentRoute() : ?Route
    {
        return $this->routes[0] ?? null;
    }

    public function startMoving(): void
    {
        $route = $this->getCurrentRoute();

        if ($this->elevator->isOverWeight()) {
            return;
        }

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
        } else {
            $this->elevator->setDirection(Elevator::DIRECTION_NONE);
            $this->endMoving();
        }

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
                $position = $this->elevator->getPosition() + $speed * ($time - $this->getCurrentRoute()->getStartTime()) / $this->building->getLevelHigh();
                $this->getCurrentRoute()->setStartTime($time);
                $this->elevator->setPosition($position);
                $this->elevator->setCurrentLevel(floor($position));
                break;
            case 'down' :
                $position = $this->elevator->getPosition() + $speed * ($time - $this->getCurrentRoute()->getStartTime()) / $this->building->getLevelHigh();
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
    }

    /**
     * @param Command $command
     * @return bool
     */
    public function isDuplicate($command): bool
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
            case Command::BUTTON_CALL:
                foreach ($this->commands as $item) {
                    if ($item->getDestinationLevel() === $command->getDestinationLevel()
                        && $item->getType() === Command::BUTTON_CALL) {
                        return true;
                    }
                }
                break;

        }
        return false;
    }

    public function stop(): void
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

    public function endMoving(): void
    {
        $this->removeRoute();
        $this->removeCommands($this->elevator->getDirection(), $this->elevator->getCurrentLevel());
        $this->elevator->setDirection(Elevator::DIRECTION_NONE);
        $this->elevator->setCurrentSpeed(0);
        echo 'Lift on ' . $this->elevator->getCurrentLevel() . ' level' . PHP_EOL ;
        $this->openDoors();
    }

    public function removeRoute(): void
    {
        array_splice($this->routes, 0, 1);
    }

    public function calculateMovingTime(): void
    {

    }

    public function openDoors(): void
    {
        echo "The doors are opening" . PHP_EOL;
        echo "The doors are closing" . PHP_EOL;
    }

    public function run($time): void
    {
        $startTime = microtime(true);
        while (microtime(true) - $startTime < $time) {
            $this->startMoving();
            $this->status(microtime(true));
            $this->updateCommands();
        }
    }

    public function updateCommands(): void
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
    public function addCommandToFile($command): void
    {
        $file = fopen(Module::FILE_COMMANDS, 'a');
        flock($file, LOCK_EX);
        fwrite($file, serialize($command) . PHP_EOL);
        flock($file, LOCK_UN);
        fclose($file);
    }

    public function isOverWeight(): bool
    {
        return $this->elevator->getPassengersQty() * Elevator::MAN_WEIGHT > Elevator::MAX_WEIGHT;
    }

    /**
     * @param Passengers $passengers
     */
    public function addPassangers($passengers): void
    {
        if (empty($this->passengers)) {
            $this->passengers[] = $passengers;
        } else {
            $foundedPass = $this->findPassengersByLevel($passengers->getLevel());
            if ($foundedPass !== null) {
                $foundedPass->setIn($foundedPass->getIn() + $passengers->getIn());
                $foundedPass->setout($foundedPass->getOut() + $passengers->getOut());

                return;
            }

            $this->passengers[] = $passengers;
        }

    }

    /**
     * @param int $level
     * @return Passengers
     */
    public function findPassengersByLevel($level): ?Passengers
    {
        foreach ($this->passengers as $item) {
            if ($item->getLevel() === $level)
            {
                return $item;
            }
        }

        return null;
    }

    /**
     * @return Passengers[]
     */
    public function getPassengers(): array
    {
        return $this->passengers;
    }

    /**
     * @param Passengers $passengers
     */
    public function setPassengers(Passengers $passengers): void
    {
        $this->passengers = $passengers;
    }

    public function updatePassengersQty($level)
    {
        $passengers = $this->findPassengersByLevel($level);

        if ($passengers !== null) {
            $this->elevator->setPassengersQty($this->elevator->getPassengersQty() - $passengers->getOut() + $passengers->getIn());
            $passengers->setIn(0);
            $passengers->setOut(0);
        }

        if ($this->elevator->getPassengersQty() * Elevator::MAN_WEIGHT > Elevator::MAX_WEIGHT) {
            $this->elevator->setIsOverWeight(true);
        }
    }

    public function exitPassengers($level, $passengersQty)
    {
        $this->elevator->setPassengersQty($this->elevator->getPassengersQty() - $passengersQty);

        $passengers = $this->findPassengersByLevel($level);
        $passengers->setIn($passengers->getIn() + $passengersQty);


        $this->elevator->setIsOverWeight($this->elevator->getPassengersQty() * Elevator::MAN_WEIGHT > Elevator::MAX_WEIGHT);
    }

    public function addElevator($elevator)
    {
        $this->elevators[] = $elevator;
    }

    /**
     * @return Elevator[]
     */
    public function getElevators()
    {
        return $this->elevators;
    }

    private function getClosestElevator($command)
    {
        return $this->elevators[0];
    }
}