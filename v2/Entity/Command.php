<?php
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 27.01.18
 * Time: 16:58
 */

namespace v2\Entity;


class Command
{
    private $createdAtDate;

    private $createdAtPosition;

    private $destinationLevel;

    private $inProgress = false;

    private $isDone = false;

    private $name;

    public function __construct(int $createdAtPosition, int $destinationLevel, string $name)
    {
        $this->createdAtDate = microtime(true);
        $this->createdAtPosition = $createdAtPosition;
        $this->destinationLevel = $destinationLevel;
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getCreatedAtDate()
    {
        return $this->createdAtDate;
    }

    /**
     * @param float $createdAtDate
     */
    public function setCreatedAtDate($createdAtDate)
    {
        $this->createdAtDate = $createdAtDate;
    }

    /**
     * @return float
     */
    public function getCreatedAtPosition()
    {
        return $this->createdAtPosition;
    }

    /**
     * @param float $createdAtPosition
     */
    public function setCreatedAtPosition($createdAtPosition)
    {
        $this->createdAtPosition = $createdAtPosition;
    }

    /**
     * @return int
     */
    public function getDestinationLevel(): int
    {
        return $this->destinationLevel;
    }

    /**
     * @param int $destinationLevel
     */
    public function setDestinationLevel(int $destinationLevel)
    {
        $this->destinationLevel = $destinationLevel;
    }

    /**
     * @return bool
     */
    public function isInProgress(): bool
    {
        return $this->inProgress;
    }

    /**
     * @param bool $inProgress
     */
    public function setInProgress(bool $inProgress)
    {
        $this->inProgress = $inProgress;
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->isDone;
    }

    /**
     * @param bool $isDone
     */
    public function setIsDone(bool $isDone)
    {
        $this->isDone = $isDone;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
}