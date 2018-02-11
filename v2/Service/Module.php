<?php

namespace v2\Service;
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 27.01.18
 * Time: 17:34
 */

use v2\Entity\Passengers;
use \v2\Entity\Elevator;
use \v2\Entity\Building;
use \v2\Entity\Command;
use \v2\Entity\Route;

class Module
{
    const FILE_COMMANDS = '/home/stepan/Documents/LiftControl/docs/Commands';

    /**
     * @var Elevator
     */
    private $elevator;

    /**
     * @var Building
     */
    private $building;

    /**
     * @var Command[]
     */
    private $commands;

    /**
     * @var Route[]
     */
    private $routes;

    /**
     * @var Passengers[]
     */
    private $passengers;

    /**
     * @var Elevator[]
     */
    private $elevators;

    public function __construct(Elevator $elevator, Building $building)
    {
        $this->elevator = $elevator;
        $this->building = $building;
    }

    /**
     * @return Elevator
     */
    public function getElevator(): Elevator
    {
        return $this->elevator;
    }

    /**
     * @param Elevator $elevator
     */
    public function setElevator(Elevator $elevator): void
    {
        $this->elevator = $elevator;
    }

    /**
     * @return Building
     */
    public function getBuilding(): Building
    {
        return $this->building;
    }

    /**
     * @param Building $building
     */
    public function setBuilding(Building $building): void
    {
        $this->building = $building;
    }

    /**
     * @return Command[]
     */
    public function getCommands(): ?array
    {
        return $this->commands;
    }

    /**
     * @param Command[] $commands
     */
    public function setCommands($commands): void
    {
        $this->commands = $commands;
    }

    /**
     * @param Command $command
     */
    public function addCommand($command) : void
    {
        if ($command->getType() === Command::BUTTON_DIRECTION) {
            $elevator = $this->getClosestElevator($command);

            if ($this->commands === null) {
//                $this->commands[] = $command;
                $elevator->updateRoute($command);
            } else {
//                if ($this->isDuplicate($command)) {
//                    return;
//                }
//                $this->commands[] = $command;
                $elevator->updateRoute($command);
            }
        }
        else if ($command->getType() === Command::BUTTON_CALL) {
            $elevator = $this->getClosestElevator($command);

            if ($this->commands === null) {
//                $this->commands[] = $command;
                $elevator->updateRoute($command);
            } else {
                if ($this->isDuplicate($command)) {
                    return;
                }
//                $this->commands[] = $command;
                $elevator->updateRoute($command);
            }
        }
        else {
            $elevator = $command->getElevator();

            if ($this->commands === null && $command->getType() !== Command::BUTTON_STOP) {
//                $this->commands[] = $command;
                $elevator->updateRoute($command);
            } else {
                if ($command->getType() === Command::BUTTON_STOP) {
                    $elevator->stop();
                    return;
                }
//                else if ($this->isDuplicate($command)) {
//                    return;
//                }
//                $this->commands[] = $command;
                $elevator->updateRoute($command);
            }
        }
    }

    public function removeCommands(string $direction, int $level) : void
    {
        $commands = $this->getCommands();
        foreach ($this->commands as $key => $value)
        {
            if ($commands[$key]->getType() === 1) {
                if ($commands[$key]->getValue() === $direction && $commands[$key]->getDestinationLevel() == $level) {
                    unset($this->commands[$key]);
                }
            } else {
                if ( $commands[$key]->getDestinationLevel() == $level ) {
                    unset($this->commands[$key]);
                }
            }
        }
    }

    /**
     * @param Command $command
     * @return bool
     */
    public function isDuplicate($command): bool
    {
        switch ($command->getType()) {
            case Command::BUTTON_DIRECTION:
                foreach ($this->commands as $item) {
                    if ($item->getValue() === $command->getValue()
                        && $item->getDestinationLevel() === $command->getDestinationLevel()) {
                        return true;
                    }
                }
                break;
            case Command::BUTTON_NUMBER:
                foreach ($this->commands as $item) {
                    if ($item->getDestinationLevel() === $command->getDestinationLevel()
                        && $item->getType() === Command::BUTTON_NUMBER) {
                        return true;
                    }
                }
                break;
            case Command::BUTTON_CALL:
                foreach ($this->commands as $item) {
                    if ($item->getDestinationLevel() === $command->getDestinationLevel()
                        && $item->getType() === Command::BUTTON_CALL) {
                        return true;
                    }
                }
                break;

        }
        return false;
    }

    public function run($time): void
    {
        $startTime = microtime(true);
        while (microtime(true) - $startTime < $time) {
            $this->startMoving();
            $this->status(microtime(true));
            $this->updateCommands();
        }
    }

    public function updateCommands(): void
    {
        $file = fopen(Module::FILE_COMMANDS, 'r+');
        flock($file, LOCK_EX);

        $commandsSerialize = fread($file, 10000);
        $commands = explode(PHP_EOL, $commandsSerialize);
        array_pop($commands);

        foreach ($commands as $command) {
            $this->addCommand(unserialize($command, [Command::class]));
        }
        ftruncate($file, 0);
        flock($file, LOCK_UN);

        fclose($file);
    }

    /**
     * @param Command $command
     */
    public function addCommandToFile($command): void
    {
        $file = fopen(Module::FILE_COMMANDS, 'a');
        flock($file, LOCK_EX);
        fwrite($file, serialize($command) . PHP_EOL);
        flock($file, LOCK_UN);
        fclose($file);
    }

    public function addElevator($elevator)
    {
        $this->elevators[] = $elevator;
    }

    /**
     * @return Elevator[]
     */
    public function getElevators()
    {
        return $this->elevators;
    }

    /**
     * @param $command
     * @return Elevator
     */
    private function getClosestElevator($command)
    {
        $closestElevator = $this->elevators[0];

        foreach ($this->elevators as $elevator)
        {
            if ($elevator->calculateDistance($command) < $closestElevator->calculateDistance($command)) {
                $closestElevator = $elevator;
            }
        }

        return $closestElevator;
    }
}