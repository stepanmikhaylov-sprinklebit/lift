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
    const BUTTON_DIRECTION = 1;

    const BUTTON_STOP = 0;

    const BUTTON_NUMBER = 10;

    private $createdAtDate;

    private $destinationLevel;

    private $inProgress = false;

    private $isDone = false;

    private $value;

    private $type;

    public function __construct(int $destinationLevel, string $value, int $type)
    {
        $this->createdAtDate = microtime(true);
        $this->destinationLevel = $destinationLevel;
        $this->value = $value;
        $this->type = $type;
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
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }
}