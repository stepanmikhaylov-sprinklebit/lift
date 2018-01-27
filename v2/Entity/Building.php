<?php
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 27.01.18
 * Time: 16:55
 */

namespace v2\Entity;


class Building
{
    private $levelsCount;

    private $levelHigh;

    public function __construct(int $levelsCount, float $levelHigh)
    {
        $this->levelHigh = $levelHigh;
        $this->levelsCount = $levelsCount;
    }

    /**
     * @return int
     */
    public function getLevelsCount(): int
    {
        return $this->levelsCount;
    }

    /**
     * @param int $levelsCount
     */
    public function setLevelsCount(int $levelsCount)
    {
        $this->levelsCount = $levelsCount;
    }

    /**
     * @return float
     */
    public function getLevelHigh(): float
    {
        return $this->levelHigh;
    }

    /**
     * @param float $levelHigh
     */
    public function setLevelHigh(float $levelHigh)
    {
        $this->levelHigh = $levelHigh;
    }
}