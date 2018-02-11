<?php

namespace v2\Service;
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 27.01.18
 * Time: 17:34
 */

use \v2\Entity\Elevator;
use \v2\Entity\Building;
use \v2\Entity\Command;

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
     * @param Command $command
     */
    public function addCommand($command) : void
    {
        if ($command->getType() === Command::BUTTON_DIRECTION) {
            $elevator = $this->getClosestElevator($command);
            $elevator->updateRoute($command);
        } else if ($command->getType() === Command::BUTTON_CALL) {
            $elevator = $this->getClosestElevator($command);
            $elevator->updateRoute($command);
        }else {
            $elevator = $command->getElevator();

            if ($command->getType() !== Command::BUTTON_STOP) {
                $elevator->updateRoute($command);
            } else {
                if ($command->getType() === Command::BUTTON_STOP) {
                    $elevator->stop();
                    return;
                }
                $elevator->updateRoute($command);
            }
        }
    }

    /**
     * @deprecated
     */
    public function run($time): void
    {
        $startTime = microtime(true);
        while (microtime(true) - $startTime < $time) {
            $this->startMoving();
            $this->status(microtime(true));
            $this->updateCommands();
        }
    }

    /**
     * @deprecated
     */
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

    /**
     * @param Elevator $elevator
     */
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