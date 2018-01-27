<?php

namespace Service;

use ValueObject\Building;
use ValueObject\Command;
use ValueObject\Elevator;
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 21.01.18
 * Time: 12:03
 */
class ControlModule
{
    /**
     * @var Command[]
     */
    private $commands;

    /**
     * @var Building
     */
    private $building;

    /**
     * @var Elevator
     */
    private $elevator;

    public function __construct($elevator, $building)
    {
        $this->elevator = $elevator;
        $this->building = $building;
    }

    public function run()
    {
        $this->setActiveCommand();
        $this->getElevator()->move($this->getActiveCommand());
        $this->setActiveCommand();
        $this->getElevator()->updateDirection($this->getActiveCommand());
    }

    public function setActiveCommand()
    {
        $activeCommand = $this->findNotDoneCommand();

        if ($activeCommand !== null) {
            $activeCommand->setIsActive(true);

            foreach ($this->commands as $command) {
                if ($activeCommand->getCreatedAtDate() > $command->getCreatedAtDate()
                    && !$command->isDone()) {
                    $activeCommand->setIsActive(false);
                    $command->setIsActive(true);
                    $activeCommand = $command;
                }
            }
        }


    }

    public function openDoors()
    {

    }

    public function closeDoors()
    {

    }

    public function updateCommandList($commands)
    {
        return $commands;
    }

    /**
     * @return mixed
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param mixed $commands
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;
    }

    /**
     * @return mixed
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * @param mixed $building
     */
    public function setBuilding($building)
    {
        $this->building = $building;
    }

    /**
     * @return Elevator
     */
    public function getElevator()
    {
        return $this->elevator;
    }

    /**
     * @param mixed $elevator
     */
    public function setElevator($elevator)
    {
        $this->elevator = $elevator;
    }

    /**
     * @param Command $command
     */
    public function addCommand($command)
    {
        $this->commands[] = $command;
    }


    /**
     * @return Command
     */
    public function getCommandToActivate()
    {
        $nextCommand = $this->findNotDoneCommand();
        foreach ($this->commands as $command) {
            if ($nextCommand->getCreatedAtDate() > $command->getCreatedAtDate() && !$command->isDone()) {
                $nextCommand = $command;
            }
        }

        return $nextCommand;
    }

    public function findNotDoneCommand()
    {
        foreach ($this->commands as $command) {
            if (!$command->isDone()) {
                return $command;
            }
        }

        return null;
    }

    public function getActiveCommand()
    {
        foreach ($this->commands as $command){
            if ($command->isActive() === true && $command->isDone() === false){
                return $command;
            }
        }
    }
}