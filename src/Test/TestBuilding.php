<?php
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 21.01.18
 * Time: 14:01
 */

namespace Tests;


use PHPUnit\Framework\TestCase;
use ValueObject\Building;

class TestBuilding extends TestCase
{
    public function testGetNumericButtons()
    {
        $building = new Building(4, 4);
        $this->assertEquals([1,2,3,4], $building->getNumericButtons());
    }
}