<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ValueObject\Building;
use ValueObject\Elevator;
use ValueObject\Command;
use Service\ControlModule;
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 20.01.18
 * Time: 15:58
 */
class TestControlModule extends TestCase
{
    public function setUp()
    {
        $elevator = new Elevator(1, 0, 1, 1);
        $building = new Building(4, 4);
    }

    public function testUp()
    {
        $timeStart = (new \DateTime())->format('U');
        while (true){
            $timeEnd = (new \DateTime())->format('U');
            $destinationPoints = [];
            if ($timeStart + 10 < $timeEnd){
                break;
            }
        }

        $this->assertEquals(10, 10);
    }
}