<?php
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 21.01.18
 * Time: 18:41
 */

namespace ValueObject;


class Route
{
    private $startLevel;

    private $endLevel;

    private $startTime;

    private $endTime;

    public function __construct(int $startLevel, int $endLevel)
    {
        $this->startLevel = $startLevel;
        $this->endLevel = $endLevel;
    }
}