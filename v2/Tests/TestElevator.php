<?php

namespace TestV2;
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 27.01.18
 * Time: 14:56
 */

use \v2\Entity\Elevator;

class TestElevator extends \PHPUnit\Framework\TestCase
{
    public function testEl()
    {
        $el = new Elevator(1);
    }
}