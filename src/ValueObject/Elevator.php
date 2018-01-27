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

    private $direction = 'none';

    private $startMoving;

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

    /**
     * @return mixed
     */
    public function getStartMoving()
    {
        return $this->startMoving;
    }

    /**
     * @param mixed $startMoving
     */
    public function setStartMoving($startMoving)
    {
        $this->startMoving = $startMoving;
    }

    public function updateDirection(Command $command)
    {
        if ($command->getDestinationLevel() - $this->getCurrentLevel() > 0) {
            $this->setDirection('up');
            $this->setSpeed(1);
            $this->setStartMoving(microtime(true));
        } else if ($command->getDestinationLevel() - $this->getCurrentLevel() < 0) {
            $this->setDirection('down');
            $this->setSpeed(-1);
            $this->setStartMoving(microtime(true));
        } else if ($command->getDestinationLevel() - $this->getCurrentLevel() === 0) {
            $this->setDirection('none');
        }
    }

    public function move(Command $command)
    {

        switch ($this->direction) {
            case 'none':
                if ($command->getDestinationLevel() - $this->getCurrentLevel() > 0) {
                    $this->setDirection('up');
                    $this->setSpeed(1);
                    $this->setStartMoving(microtime(true));
                } else if ($command->getDestinationLevel() - $this->getCurrentLevel() < 0) {
                    $this->setDirection('down');
                    $this->setSpeed(-1);
                    $this->setStartMoving(microtime(true));
                } else if ($command->getDestinationLevel() - $this->getCurrentLevel() === 0) {
                    $this->setDirection('none');
                }
                break;
            case 'up':
                $this->setPosition($this->calculatePosition());
                $this->setStartMoving(microtime(true));
                if ($this->getPosition() > $this->getCurrentLevel()) {
                    $this->setCurrentLevel(floor($this->getPosition()));
                }
                if ($this->getCurrentLevel() == $command->getDestinationLevel()) {
                    $this->setSpeed(0);
                    $command->setIsDone(true);                }
                break;
            case 'down':
                $this->setPosition($this->calculatePosition());
                $this->setStartMoving(microtime(true));
                if ($this->getPosition() < $this->getCurrentLevel()) {
                    $this->setCurrentLevel(ceil($this->getPosition()));
                }
                if ($this->getCurrentLevel() == $command->getDestinationLevel()) {
                    $this->setSpeed(0);
                    $command->setIsDone(true);
                }
                break;
            default:
                break;
        }
    }

    public function calculatePosition()
    {
        return $this->getPosition() + $this->getSpeed() * (microtime(true) - $this->getStartMoving());
    }

    public function stop()
    {

    }
}