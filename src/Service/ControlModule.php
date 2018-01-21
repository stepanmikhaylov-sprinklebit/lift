<?php

namespace Service;

use ValueObject\Building;
use ValueObject\Elevator;
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 21.01.18
 * Time: 12:03
 */
class ControlModule
{
    private $commands;

    private $building;

    private $elevator;

    public function run($command)
    {
        switch ($command->name){
            case 'up':
                $this->move();
                break;
            case 'down':
                $this->move();
                break;
            case 'stop':
                $this->stop();
                break;
            default:
                break;
        }
    }

    public function move()
    {

    }

    public function stop()
    {

    }

    public function openDoors()
    {

    }

    public function closeDoors()
    {

    }

    public function updateCommandList()
    {

    }
}