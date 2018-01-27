<?php
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 21.01.18
 * Time: 12:28
 */

namespace ValueObject;


class Command
{
    private $createdAtDate;

    private $destinationLevel;

    private $isActive = false;

    private $isDone = false;

    private $name;

    public function __construct($destinationLevel, $name)
    {
        $this->createdAtDate = microtime(true);
        $this->destinationLevel = $destinationLevel;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCreatedAtDate(): string
    {
        return $this->createdAtDate;
    }

    /**
     * @param string $createdAtDate
     */
    public function setCreatedAtDate(string $createdAtDate)
    {
        $this->createdAtDate = $createdAtDate;
    }

    /**
     * @return mixed
     */
    public function getDestinationLevel()
    {
        return $this->destinationLevel;
    }

    /**
     * @param mixed $destinationLevel
     */
    public function setDestinationLevel($destinationLevel)
    {
        $this->destinationLevel = $destinationLevel;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return Command
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;

        return $this;
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}