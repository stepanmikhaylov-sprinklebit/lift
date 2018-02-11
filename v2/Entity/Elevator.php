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


}