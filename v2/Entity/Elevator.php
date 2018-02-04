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

    private $maxSpeed;

    private $currentSpeed = 0;

    private $currentLevel = 1;

    private $position = 1.0;

    private $direction = self::DIRECTION_NONE;

    public function __construct($maxSpeed)
    {
        $this->maxSpeed = $maxSpeed;
    }

    /**
     * @return int
     */
    public function getMaxSpeed()
    {
        return $this->maxSpeed;
    }

    /**
     * @param int $maxSpeed
     */
    public function setMaxSpeed($maxSpeed)
    {
        $this->maxSpeed = $maxSpeed;
    }

    /**
     * @return int
     */
    public function getCurrentSpeed(): int
    {
        return $this->currentSpeed;
    }

    /**
     * @param int $currentSpeed
     */
    public function setCurrentSpeed(int $currentSpeed)
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
}