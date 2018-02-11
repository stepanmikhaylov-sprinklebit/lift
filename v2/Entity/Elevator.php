<?php

namespace v2\Entity;
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 27.01.18
 * Time: 14:55
 */
class Elevator
{
    const DIRECTION_NONE = 'none';

    const DIRECTION_UP = 'up';

    const DIRECTION_DOWN = 'down';

    const DIRECTION_STOP = 'stop';
    const MAX_WEIGHT = 700;
    const MAN_WEIGHT = 70;

    private $maxSpeed;

    private $currentSpeed = 0;

    private $currentLevel = 1;

    private $position = 1.0;

    private $direction = self::DIRECTION_NONE;

    private $weight;
    private $passengersQty;
    /**
     * @var bool
     */
    private $isOverWeight = false;

    /**
     * @var Route[]
     */
    private $routes = [];
    private $levelHigh = 1;

    /**
     * @var Passengers[]
     */
    private $passengers;


    public function __construct($maxSpeed)
    {
        $this->maxSpeed = $maxSpeed;
    }

    /**
     * @return float
     */
    public function getMaxSpeed()
    {
        return $this->maxSpeed;
    }

    /**
     * @param float $maxSpeed
     */
    public function setMaxSpeed($maxSpeed)
    {
        $this->maxSpeed = $maxSpeed;
    }

    /**
     * @return float
     */
    public function getCurrentSpeed(): float
    {
        return $this->currentSpeed;
    }

    /**
     * @param float $currentSpeed
     */
    public function setCurrentSpeed(float $currentSpeed)
    {
        $this->currentSpeed = $currentSpeed;
    }

    /**
     * @return int
     */
    public function getCurrentLevel(): int
    {
        return $this->currentLevel;
    }

    /**
     * @param int $currentLevel
     */
    public function setCurrentLevel(int $currentLevel)
    {
        $this->currentLevel = $currentLevel;
    }

    /**
     * @return float
     */
    public function getPosition(): float
    {
        return $this->position;
    }

    /**
     * @param float $position
     */
    public function setPosition(float $position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection(string $direction)
    {
        $this->direction = $direction;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    public function setPassengersQty($passengersQty)
    {
        $this->passengersQty = $passengersQty;
    }

    /**
     * @return mixed
     */
    public function getPassengersQty()
    {
        return $this->passengersQty;
    }


    public function isOverWeight()
    {
        return $this->isOverWeight;
    }

    /**
     * @param bool $isOverWeight
     */
    public function setIsOverWeight(bool $isOverWeight)
    {
        $this->isOverWeight = $isOverWeight;
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
    public function setRoutes(array $routes)
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
            $elevator = clone $this;
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

    public function stop(): void
    {
        if ($this->getDirection() === 'stop') {
            $this->setDirection('none');
            echo 'Lift continue moving' . PHP_EOL;

            return;
        }
        $this->setDirection('stop');
        $this->setCurrentSpeed(0);

        echo 'Lift stopped' . PHP_EOL;
    }

    public function getCurrentRoute() : ?Route
    {
        return $this->routes[0] ?? null;
    }

    public function startMoving(): void
    {
        $route = $this->getCurrentRoute();

        if ($this->isOverWeight()) {
            return;
        }

        if ($route === null || $this->getDirection() === 'stop') {
            return;
        }

        if ($route->getEndLevel() > $this->getCurrentLevel()) {
            if ($this->getDirection() === 'none') {
                $this->getCurrentRoute()->setStartTime(microtime(true));
                echo 'Lift on ' . $this->getCurrentLevel()
                    . ' level. Moves to ' . $this->getCurrentRoute()->getEndLevel() . ' level' . PHP_EOL;
            }

            $this->setDirection(Elevator::DIRECTION_UP);
            $this->setCurrentSpeed($this->getMaxSpeed());
            //$this->getCurrentRoute()->setStartTime(microtime(true));

        } else if ($route->getEndLevel() < $this->getCurrentLevel()) {
            if ($this->getDirection() === 'none') {
                $this->getCurrentRoute()->setStartTime(microtime(true));
                echo 'Lift on ' . $this->getCurrentLevel()
                    . ' level. Moves to ' . $this->getCurrentRoute()->getEndLevel() . ' level' . PHP_EOL;
            }

            $this->setDirection(Elevator::DIRECTION_DOWN);
            $this->setCurrentSpeed(-$this->getMaxSpeed());
        } else {
            $this->setDirection(Elevator::DIRECTION_NONE);
            $this->endMoving();
        }

    }

    public function endMoving(): void
    {
        $this->removeRoute();
        $this->setDirection(Elevator::DIRECTION_NONE);
        $this->setCurrentSpeed(0);
        echo 'Lift on ' . $this->getCurrentLevel() . ' level' . PHP_EOL ;
        $this->openDoors();
    }

    public function removeRoute(): void
    {
        array_splice($this->routes, 0, 1);
    }

    public function openDoors(): void
    {
        echo "The doors are opening" . PHP_EOL;
        echo "The doors are closing" . PHP_EOL;
    }

    public function status($time)
    {
        $route = $this->getCurrentRoute();

        $direction = $this->getDirection();
        $speed = $this->getCurrentSpeed();

        switch ($direction) {
            case 'none' :
                return;
            case 'up' :
                $position = $this->getPosition() + $speed * ($time - $this->getCurrentRoute()->getStartTime()) / $this->getLevelHigh();
                $this->getCurrentRoute()->setStartTime($time);
                $this->setPosition($position);
                $this->setCurrentLevel(floor($position));
                break;
            case 'down' :
                $position = $this->getPosition() + $speed * ($time - $this->getCurrentRoute()->getStartTime()) / $this->getLevelHigh();
                $this->getCurrentRoute()->setStartTime($time);
                $this->setPosition($position);
                $this->setCurrentLevel(ceil($position));
                break;
            case 'stop' :
                return;
            default:
                break;
        }
        if ($speed * $route->getEndLevel() <= $speed * $this->getPosition())
        {
            $this->endMoving();
            return 'Lift on ' . $this->getCurrentLevel() . ' level';
        }
    }

    private function getLevelHigh()
    {
        return $this->levelHigh;
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

    public function updatePassengersQty($level)
    {
        $passengers = $this->findPassengersByLevel($level);

        if ($passengers !== null) {
            $this->setPassengersQty($this->getPassengersQty() - $passengers->getOut() + $passengers->getIn());
            $passengers->setIn(0);
            $passengers->setOut(0);
        }

        if ($this->getPassengersQty() * Elevator::MAN_WEIGHT > Elevator::MAX_WEIGHT) {
            $this->setIsOverWeight(true);
        }
    }

    public function exitPassengers($level, $passengersQty)
    {
        $this->setPassengersQty($this->getPassengersQty() - $passengersQty);

        $passengers = $this->findPassengersByLevel($level);
        $passengers->setIn($passengers->getIn() + $passengersQty);


        $this->setIsOverWeight($this->getPassengersQty() * Elevator::MAN_WEIGHT > Elevator::MAX_WEIGHT);
    }

    /**
     * @return Passengers[]
     */
    public function getPassengers(): array
    {
        return $this->passengers;
    }

    /**
     * @param Passengers[] $passengers
     */
    public function setPassengers(array $passengers)
    {
        $this->passengers = $passengers;
    }

    /**
     * @param int $levelHigh
     */
    public function setLevelHigh(int $levelHigh)
    {
        $this->levelHigh = $levelHigh;
    }

}