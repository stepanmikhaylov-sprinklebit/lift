<?php
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 27.01.18
 * Time: 17:16
 */

namespace v2\Entity;


class Route
{
    private $endLevel;

    private $startTime;

    public function __construct(Command $command)
    {
        $this->endLevel = $command->getDestinationLevel();
    }

    /**
     * @return int
     */
    public function getEndLevel(): int
    {
        return $this->endLevel;
    }

    /**
     * @param int $endLevel
     */
    public function setEndLevel(int $endLevel)
    {
        $this->endLevel = $endLevel;
    }

    /**
     * @return float
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param float $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }
}