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
use \v2\Entity\Route;

class Module
{
    /**
     * @var Elevator
     */
    private $elevator;

    /**
     * @var Building
     */
    private $biulding;

    /**
     * @var Command[]
     */
    private $commands;

    /**
     * @var Route[]
     */
    private $routes;

    public function __construct(Elevator $elevator, Building $building)
    {
        $this->elevator = $elevator;
        $this->biulding = $building;
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
    public function setElevator(Elevator $elevator)
    {
        $this->elevator = $elevator;
    }

    /**
     * @return Building
     */
    public function getBiulding(): Building
    {
        return $this->biulding;
    }

    /**
     * @param Building $biulding
     */
    public function setBiulding(Building $biulding)
    {
        $this->biulding = $biulding;
    }

    /**
     * @return Command[]
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param Command[] $commands
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;
    }

    /**
     * @param Command $command
     */
    public function addCommand($command)
    {
        $this->commands[] = $command;
        $this->updateRoute($command);
    }

    public function removeCommand($command)
    {

    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param Route[] $routes
     */
    public function setRoute(array $routes)
    {
        $this->routes = $routes;
    }

    public function updateRoute(Command $command)
    {
        $this->routes[] = new Route($command);
    }

    public function getCurrentRoute()
    {
        return $this->routes[0];
    }

    public function move()
    {
        $route = $this->getCurrentRoute();
        if (true) {

        }
        return 'Lift on ' . $this->elevator->getCurrentLevel() . ' level. Moves to ' . $this->getCurrentRoute()->getEndLevel() . ' level';
    }

    public function status()
    {
        return 'Lift on ' . $this->elevator->getCurrentLevel() . ' level. Moves to ' . $this->getCurrentRoute()->getEndLevel() . ' level';
    }
}