<?php

namespace ValueObject;
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 20.01.18
 * Time: 15:56
 */
class Elevator
{
    private $maxSpeed;

    private $speed;

    private $currentLevel;

    private $position;

    public function __construct($maxSpeed, $speed, $position, $currentLevel)
    {
        $this->maxSpeed = $maxSpeed;
        $this->speed = $speed;
        $this->position = $position;
        $this->currentLevel = $currentLevel;
    }

    /**
     * @return mixed
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @param mixed $speed
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getMaxSpeed()
    {
        return $this->maxSpeed;
    }

    /**
     * @param mixed $maxSpeed
     */
    public function setMaxSpeed($maxSpeed)
    {
        $this->maxSpeed = $maxSpeed;
    }

    /**
     * @return mixed
     */
    public function getCurrentLevel()
    {
        return $this->currentLevel;
    }

    /**
     * @param mixed $currentLevel
     */
    public function setCurrentLevel($currentLevel)
    {
        $this->currentLevel = $currentLevel;
    }
}