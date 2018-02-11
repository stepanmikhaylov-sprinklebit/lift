<?php
/**
 * Created by PhpStorm.
 * User: stepan
 * Date: 10.02.18
 * Time: 19:29
 */

namespace v2\Entity;


class Passengers
{
    private $level;
    private $in;
    private $out;

    /**
     * Passengers constructor.
     * @param $level
     * @param $in
     * @param $out
     */
    public function __construct($level, $in, $out)
    {
        $this->level = $level;
        $this->in = $in;
        $this->out = $out;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getIn()
    {
        return $this->in;
    }

    /**
     * @param mixed $in
     */
    public function setIn($in)
    {
        $this->in = $in;
    }

    /**
     * @return mixed
     */
    public function getOut()
    {
        return $this->out;
    }

    /**
     * @param mixed $out
     */
    public function setOut($out)
    {
        $this->out = $out;
    }
}