<?php
namespace BGame\ZhaJinHua;

use BGame\Base\Poker;

class Player
{
    /**
     * 用户标识
     * @var string
     */
    private $flag;

    /**
     * 玩家的Poker牌
     * @var Poker
     */
    private $poker;

    /**
     * __construct
     * @param string $flag 用户标识
     */
    public function __construct($flag)
    {
        $this->flag = $flag;
    }

    /**
     * 为玩家分配poker牌
     * @param Poker $poker
     */
    public function setPoker(Poker $poker)
    {
        $this->poker = $poker;
        return $this;
    }
}