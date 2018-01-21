<?php
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 21.01.18
 * Time: 11:18
 */

namespace ValueObject;


class Building
{
    private $levelQty;

    private $levelHigh;

    public function __construct($levelQty, $levelHigh)
    {
        $this->levelHigh = $levelHigh;
        $this->levelQty = $levelQty;
    }

    /**
     * @return mixed
     */
    public function getLevelQty()
    {
        return $this->levelQty;
    }

    /**
     * @param mixed $levelQty
     */
    public function setLevelQty($levelQty)
    {
        $this->levelQty = $levelQty;
    }

    /**
     * @return mixed
     */
    public function getLevelHigh()
    {
        return $this->levelHigh;
    }

    /**
     * @param mixed $levelHigh
     */
    public function setLevelHigh($levelHigh)
    {
        $this->levelHigh = $levelHigh;
    }

    public function getNumericButtons()
    {
        $buttons = [];
        for ($i = 1; $i<=$this->levelQty; $i++){
            $buttons[] = $i;
        }

        return $buttons;
    }
}